<?php
require_once ('../model/Conta.class.php');
require_once ('../model/Pedido.class.php');
require_once ('../model/Usuario.class.php');

function buscarContas(){
    debug(3, "Buscando contas...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT m.numMesa,
            CASE c.status WHEN 3 THEN NULL ELSE c.id END AS id,
            CASE c.status WHEN 3 THEN NULL ELSE c.dataHoraAbertura END AS dataHoraAbertura,
            CASE c.status WHEN 3 THEN NULL ELSE c.dataHoraFechamento END AS dataHoraFechamento,
            CASE c.status WHEN 3 THEN NULL ELSE c.qtdPessoas END AS qtdPessoas,
            CASE c.status WHEN 3 THEN '' ELSE IFNULL(c.descricao,'') END AS descricao,
            CASE c.status WHEN 3 THEN NULL ELSE c.status END AS status,
            CASE c.status WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 3 END statusOrder,
            CASE c.status WHEN 3 THEN NULL ELSE TRUNCATE(((((SELECT SUM(p.quantidade*p.valorUnitario) FROM pedido p WHERE p.contaId = c.id)-IFNULL(c.desconto,0))*(CASE c.taxaServico WHEN 1 THEN (SELECT (valorTxServico/100)+1 FROM parametroSistema) ELSE 1 END))-(SELECT IFNULL(SUM(valor),0) FROM pagamento pa WHERE pa.contaId = c.id)),2) END AS totalAtual
            FROM mesa m 
            LEFT JOIN conta c 
            ON c.numMesa = m.numMesa 
            WHERE (c.id IN (SELECT MAX(c2.id) FROM conta c2 WHERE c.numMesa = c2.numMesa) OR c.id IS NULL) 
            ORDER BY statusOrder ASC, m.numMesa");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = $connect->get_array($result);
    return $arraydados;
}

function buscarProdutos(){
    debug(3, "Buscando produtos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,nome,descricao,categoriaId,preco,status,(SELECT GROUP_CONCAT(DISTINCT CONCAT(adicionalId,';',(SELECT descricao FROM adicional ad WHERE ad.id = ca.adicionalId))) FROM categoriaAdicional ca WHERE ca.categoriaId = p.categoriaId) adicionais FROM produto p");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = array();
    while($row = mysqli_fetch_array($result)){
        $arraydados[$row["id"]] = $row;
    }
    return $arraydados;
}

function buscarCategorias(){
    debug(3, "Buscando produtos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id, descricao, categoriaPaiId, status, (SELECT GROUP_CONCAT(adicionalId) FROM categoriaAdicional ca WHERE ca.categoriaId = c.Id) adicionais FROM categoria c WHERE status = 1");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = array();
    while($row = mysqli_fetch_array($result)){
        $arraydados[$row["id"]] = $row;
    }
    return $arraydados;
}

function buscarAdicionais(){
    debug(3, "Buscando produtos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id, descricao,(SELECT GROUP_CONCAT(categoriaId) FROM categoriaAdicional WHERE a.Id = adicionalId) AS categorias FROM adicional a "); //WHERE status = 1
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = array();
    while($row = mysqli_fetch_array($result)){
        $arraydados[$row["id"]] = $row;
    }
    return $arraydados;
}

function buscarFormaPagamentos(){
    debug(3, "Buscando pedidos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,descricao,pedeObservacao FROM formaPagamento");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = $connect->get_array($result);
    return $arraydados;
}

function buscarPedidos($conta){
    debug(3, "Buscando pedidos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,dataHora,quantidade,valorUnitario,produtoId,observacao,usuarioId,(quantidade*valorUnitario) valor FROM pedido p WHERE contaId = ".$conta);
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = $connect->get_array($result);
    return $arraydados;
}

function buscarPagamentos($conta){
    debug(3, "Buscando pagamentos...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,formaPagamentoId,(SELECT descricao FROM formaPagamento fp WHERE fp.id = formaPagamentoId) formaPagamento,valor,observacao,dataHora,usuarioId FROM pagamento p WHERE contaId = ".$conta);
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = $connect->get_array($result);
    return $arraydados;
}

function buscarValorTaxaServico(){
    debug(3, "Buscando taxa servico...");
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT valorTxServico/100 AS valor FROM parametroSistema");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    $arraydados = $connect->get_array($result);
    return $arraydados[0]["valor"];
}

function salvar($id,$qtdPessoas,$numMesa,$dataHoraAbertura,$descricao,$taxaServico,$status,$dataHoraFechamento){
    $retorno = "";
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se conta existe.");
        $objC = new Conta();
        $ret = $objC->load($id);
        $idAntigo = $objC->__get("id");
        debug(3, "Conta localizado: ".$idAntigo);
        
        $objC->__set("id",$id);
        if($qtdPessoas != ""){          $objC->__set("qtdPessoas",$qtdPessoas);}
        if($numMesa != ""){             $objC->__set("numMesa",$numMesa);}
        if($dataHoraAbertura != ""){    $objC->__set("dataHoraAbertura",$dataHoraAbertura);}
        if($dataHoraFechamento != ""){  $objC->__set("dataHoraFechamento",$dataHoraFechamento);}
        if($descricao != ""){           $objC->__set("descricao",$descricao);}
        if($taxaServico != ""){         $objC->__set("taxaServico",$taxaServico);}
        if($status != ""){              $objC->__set("status",$status);}

        if($idAntigo){
            $ret = $objC->update();
        }else{
            $ret = $objC->add();
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Conta salvo com sucesso");
        
        if($objC->__get("id")){
            $array = desenharArray($objC);
        }else{
            $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao buscar dados!");
        }
        
    }catch(Exception $e){
        debug(1, "Erro ao salvar conta: ".$e->getMessage());

        ConexaoSingleton::getConexao()->rollback();

        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar conta!");
    }
    return $array;
}

function inserirPedido($id,$qtdProduto,$idProduto,$dataHora,$idConta,$adicionais){
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Salvando novo pedido.");
        
        $sql = 
            "INSERT INTO pedido ".
            " (id,quantidade,produtoId,contaId,dataHora,valorUnitario,usuarioId) ".
            "VALUES".
            " (NULL,'".$qtdProduto."','".$idProduto."','".$idConta."','".$dataHora."',(SELECT preco FROM produto WHERE id = '".$idProduto."'),'".$GLOBALS["usuarioLogado"]->__get("usuario")."')";
        
        $ret = ConexaoSingleton::getConexao()->executar($sql);
        if(!$ret) {
            debug(3, "Erro ao adicionar pedido: ".$ret);
            throw new Exception ("");
        }

        $idPedido = ConexaoSingleton::getConexao()->getLastId();

        if($adicionais != ""){
            debug(3, "Salvando adicionais: ".$adicionais);
            $arrayAdicionais = explode(",", $adicionais);
            for($i=0;$i<count($arrayAdicionais);$i++){
                $dadosAdicionais = explode(";", $arrayAdicionais[$i]);
                $sql = 
                    "INSERT INTO pedidoAdicional ".
                    " (id,pedidoId,adicionalId,quantidade) ".
                    "VALUES".
                    " (NULL,'".$idPedido."','".$dadosAdicionais[0]."','".$dadosAdicionais[1]."')";

                $ret = ConexaoSingleton::getConexao()->executar($sql);
                if(!$ret) {
                    debug(3, "Erro ao adicionar pedido: ".$ret);
                    throw new Exception ("");
                }
                debug(3, "Adicional ".$arrayAdicionais[$i]." salvo para o pedido ".$idPedido);
            }
        }

        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Pedido salvo com sucesso");
        
        $array = 1;        
    }catch(Exception $e){
        debug(1, "Erro ao salvar conta: ".$e->getMessage());

        ConexaoSingleton::getConexao()->rollback();

        $array = 0;
    }
    return $array;
}

function salvarPagamento($idConta,$formaPagamento,$valor,$observacao,$dataHora){
    $retorno = "";
    try {
        $conexao = ConexaoSingleton::getConexao();
        $conexao->startTransaction();
        
        debug(3, "Salvando novo pagamento.");
        
        $sql = 
            "INSERT INTO pagamento ".
            " (id,contaId,formaPagamentoId,valor,observacao,dataHora,usuarioId) ".
            "VALUES".
            " (NULL,'".$idConta."','".$formaPagamento."','".$valor."','".$observacao."','".$dataHora."','".$GLOBALS["usuarioLogado"]->__get("usuario")."')";
        
        $ret = $conexao->executar($sql);
        if(!$ret) {
            debug(3, "Erro ao salvar pagamento: ".$ret);
            throw new Exception ("");
        }
        
        debug(3, "Pagamento salvo com sucesso");        

        $objC = new Conta();
        $objC->load($idConta);

        //Verifica se a conta esta totalmente paga e fecha ela
        //Não fará mais isso.
        /*$totalmentePaga = 0;
        $sql = "SELECT ((SELECT CASE c.taxaServico WHEN 1 THEN SUM(p.quantidade*p.valorUnitario)*(SELECT (valorTxServico/100)+1 FROM parametroSistema) ELSE SUM(p.quantidade*p.valorUnitario) END FROM pedido p WHERE p.contaId = c.id)-IFNULL(c.desconto,0)-(SELECT IFNULL(SUM(valor),0) FROM pagamento pa WHERE pa.contaId = c.id)) total FROM conta c WHERE c.id = $idConta";
        $result = $conexao->executar($sql);
        $arraydados = $conexao->get_array($result);
        if($arraydados[0]["total"] == 0){
            $totalmentePaga = 1;
        }
        debug(3, "Verificando se a conta esta totalmente paga: ".$totalmentePaga);
                
        if($totalmentePaga){
            $objC->__set("status",3);
            $ret = $objC->update();
            if(!$ret) {
                debug(3, "Erro ao alterar o status da conta: ".$ret);
                throw new Exception ("");
            }
            debug(3, "Status da conta alterado com sucesso.");
        }*/
        
        $conexao->commit();
        
        $array = desenharArray($objC);
        
    }catch(Exception $e){
        debug(1, "Erro ao salvar pagamento: ".$e->getMessage());

        $conexao->rollback();

        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar pagamento!");
    }
    return $array;
}

function desenharArray($objC){
    $array = array(
        "retorno"=>true,
        "id"=>$objC->__get("id"),
        "dataHoraAbertura"=>$objC->__get("dataHoraAbertura"),
        "dataHoraFechamento"=>$objC->__get("dataHoraFechamento"),
        "numMesa"=>$objC->__get("numMesa"),
        "qtdPessoas"=>$objC->__get("qtdPessoas"),
        "descricao"=>$objC->__get("descricao"),
        "taxaServico"=>$objC->__get("taxaServico"),
        "status"=>$objC->__get("status"),
        "desconto"=>$objC->__get("desconto"),
        "valorTaxaServico"=>buscarValorTaxaServico(),
        "pedidos"=>buscarPedidos($objC->__get("id")),
        "pagamentos"=>buscarPagamentos($objC->__get("id")),
        "contas"=>buscarContas(),
        "categorias"=>  buscarCategorias()
    );
    return $array;
}

function aplicarDesconto($idConta,$desconto){
    $retorno = "";
    try {

        debug(3, "Aplicando desconto");

        $objC = new Conta();
        $objC->load($idConta);

        $objC->__set("desconto",$desconto);
        $ret = $objC->update();

        //Verifica se a conta esta totalmente paga e fecha ela
        //Não fará mais isso
        /*
        $totalmentePaga = 0;
        $sql = "SELECT ((SELECT CASE c.taxaServico WHEN 1 THEN SUM(p.quantidade*p.valorUnitario)*(SELECT (valorTxServico/100)+1 FROM parametroSistema) ELSE SUM(p.quantidade*p.valorUnitario) END FROM pedido p WHERE p.contaId = c.id)-IFNULL(c.desconto,0)-(SELECT IFNULL(SUM(valor),0) FROM pagamento pa WHERE pa.contaId = c.id)) total FROM conta c WHERE c.id = $idConta";
        $conexao = ConexaoSingleton::getConexao();
        $result = $conexao->executar($sql);
        $arraydados = $conexao->get_array($result);
        if($arraydados[0]["total"] == 0){
            $totalmentePaga = 1;
        }

        if($totalmentePaga){
            $objC->__set("status",3);
            $ret = $objC->update();
            if(!$ret) {
                debug(3, "Erro ao alterar o status da conta: ".$ret);
                throw new Exception ("");
            }
            debug(3, "Status da conta alterado com sucesso.");
        }*/

        $array = desenharArray($objC);
        
    }catch(Exception $e){
        debug(1, "Erro ao salvar desconto: ".$e->getMessage());
        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar pagamento!");
    }
    return $array;
}

function excluirPedido($id,$idConta){
    $retorno = "";
    try {
        $conexao = ConexaoSingleton::getConexao();
        $conexao->startTransaction();

        debug(3, "Removendo pedido");

        $sql = "DELETE FROM pedido WHERE id = $id";
        $ret = $conexao->executar($sql);
        if(!$ret) {
            debug(3, "Erro ao remover pedido: ".$ret);
            throw new Exception ("");
        }
        debug(3, "Pedido removido com com sucesso.");

        $conexao->commit();

        $objC = new Conta();
        $objC->load($idConta);
        $array = desenharArray($objC);
        
    }catch(Exception $e){
        debug(1, "Erro ao salvar pagamento: ".$e->getMessage());

        $conexao->rollback();

        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar pagamento!");
    }
    return $array;
}

function trocarMesa($idConta,$novaMesa,$contaAntiga){
    $retorno = "";
    try {
        $conexao = ConexaoSingleton::getConexao();
        $conexao->startTransaction();

        debug(3, "Trocando mesa.");

        $objC = new Conta();
        $objC->load($idConta);

        $objC->__set("numMesa",$novaMesa);
        $ret = $objC->update();
        
        if($contaAntiga != ""){
            //Caso seja uma união de mesas, tem que trocar os pedidos
            $sql = "UPDATE pedido SET contaId = $idConta WHERE contaId = $contaAntiga";
            $ret = $conexao->executar($sql);
            if(!$ret) {
                debug(3, "Erro ao mudar pedidos: ".$ret);
                throw new Exception ("");
            }
            $sql = "DELETE FROM conta WHERE id = $contaAntiga";
            $ret = $conexao->executar($sql);
            if(!$ret) {
                debug(3, "Erro ao remover conta: ".$ret);
                throw new Exception ("");
            }
        }

        $conexao->commit();

        $array = desenharArray($objC);
        
    }catch(Exception $e){
        $conexao->rollback();

        debug(1, "Erro ao salvar desconto: ".$e->getMessage());
        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar pagamento!");
    }
    return $array;
}

function salvarPedido($id,$idConta,$qtdProduto,$idProduto,$dataHora,$observacao,$valor,$adicionais){
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Salvando pedido: ".$id);
        
        $objP = new Pedido();
        if($id != ""){
            $objP->load($id);
        }
        
        $objP->__set("contaId",$idConta);
        $objP->__set("quantidade",$qtdProduto);
        $objP->__set("produtoId",$idProduto);
        if($observacao != ""){
            $objP->__set("observacao",$observacao);
        }
        if($valor != ""){
            $objP->__set("valorUnitario",$valor);
        }
        $objP->__set("usuarioId",$GLOBALS["usuarioLogado"]->__get("usuario"));
        if($dataHora != ""){
            $objP->__set("dataHora",$dataHora);
        }

        if($id != ""){
            $ret = $objP->update();
        }else{
            $ret = $objP->add();
            $id = $ret;
        }
        if(!$ret) {
            debug(3, "Erro ao adicionar pedido.");
            throw new Exception ("Erro ao adicionar pedido.");
        }
        debug(3, "Pedido salvo com sucesso.");
        debug(3, "Pedido: ".$objP);
        
        if($adicionais != ""){
            debug(3, "Salvando adicionais: ".$adicionais);
            $arrayAdicionais = explode(",", $adicionais);
            for($i=0;$i<count($arrayAdicionais);$i++){
                $dadosAdicionais = explode(";", $arrayAdicionais[$i]);
                $sql = 
                    "INSERT INTO pedidoAdicional ".
                    " (id,pedidoId,adicionalId,quantidade) ".
                    "VALUES".
                    " (NULL,'".$idPedido."','".$dadosAdicionais[0]."','".$dadosAdicionais[1]."')";

                $ret = ConexaoSingleton::getConexao()->executar($sql);
                if(!$ret) {
                    debug(3, "Erro ao adicionar pedido: ".$ret);
                    throw new Exception ("");
                }
                debug(3, "Adicional ".$arrayAdicionais[$i]." salvo para o pedido ".$idPedido);
            }
        }
                
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Pedido realizado com sucesso.");
        
        $objC = new Conta();
        $objC->load($idConta);
        
        $array = desenharArray($objC);
        
    }catch(Exception $e){
        debug(1, "Erro ao salvar conta: ".$e->getMessage());

        ConexaoSingleton::getConexao()->rollback();

        $array = array(
                "retorno"=>false,
                "msg"=>"Erro ao salvar conta!");
    }
    return $array;
}

?>

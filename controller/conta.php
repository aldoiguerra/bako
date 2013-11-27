<?php
require_once ('../controller/sessao.php');
require_once ('../controller/contaMetodosComum.php');

if(isset($_POST["buscarContas"])){
    debug(3, "Recebido pedido para buscar dados.");
    $arrayretorno = array("retorno"=>1,
                            "contas"=>buscarContas(),
                            "produtos"=>buscarProdutos(),
                            "formapagamentos"=>buscarFormaPagamentos());
    echo json_encode($arrayretorno);
}if(isset($_POST["buscarProdutos"])){
    debug(3, "Recebido pedido para buscar produtos.");
    $arrayretorno = array("retorno"=>1,
                            "produtos"=>buscarProdutos());
    echo json_encode($arrayretorno);
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar conta: ".$_POST["consultar"]);
    $objC = new Conta();
    $ret = $objC->load($_POST["consultar"]);
    debug(3, "Conta consultado: ".$objC->__get("id"));
    if($objC->__get("id")){
        $array = desenharArray($objC);
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else if(isset($_POST["abrirMesa"])){
    debug(3, "Recebido pedido para salvar categoria: ".$_POST["id"]);
    $array = salvar($_POST["id"],$_POST["qtdPessoas"],$_POST["numMesa"],$_POST["dataHora"],$_POST["descricao"],$_POST["taxaServico"],1,"");
    echo json_encode($array);
}else if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar categoria: ".$_POST["id"]);
    $array = salvar($_POST["id"],$_POST["qtdPessoas"],$_POST["numMesa"],$_POST["dataHora"],$_POST["descricao"],$_POST["taxaServico"],$_POST["status"],$_POST["dataHoraFechamento"]);
    echo json_encode($array);
}else if(isset($_POST["fecharConta"])){
    debug(3, "Recebido pedido para fechar conta: ".$_POST["id"]);
    $array = salvar($_POST["id"],$_POST["qtdPessoas"],$_POST["numMesa"],$_POST["dataHoraAbertura"],$_POST["descricao"],$_POST["taxaServico"],2,$_POST["dataHoraFechamento"]);
    echo json_encode($array);
}else if(isset($_POST["liberarMesa"])){
    debug(3, "Recebido pedido para fechar conta: ".$_POST["id"]);
    $array = salvar($_POST["id"],$_POST["qtdPessoas"],$_POST["numMesa"],$_POST["dataHoraAbertura"],$_POST["descricao"],$_POST["taxaServico"],3,$_POST["dataHoraFechamento"]);
    echo json_encode($array);
}else if(isset($_POST["inserirPedido"])){
    debug(3, "Recebido pedido para inserir novo pedido: ".$_POST["id"]);
    $array = inserirPedido($_POST["id"],$_POST["qtdProduto"],$_POST["idProduto"],$_POST["dataHora"],$_POST["idConta"]);
    echo json_encode($array);
}else if(isset($_POST["salvarPagamento"])){
    debug(3, "Recebido pedido para inserir novo pagamento: ".$_POST["id"]);
    $array = salvarPagamento($_POST["id"],$_POST["formaPagamento"],$_POST["valor"],$_POST["observacao"],$_POST["dataHora"]);
    echo json_encode($array);
}else if(isset($_POST["aplicarDesconto"])){
    debug(3, "Recebido pedido para inserir novo pagamento: ".$_POST["id"]);
    $array = aplicarDesconto($_POST["id"],$_POST["desconto"]);
    echo json_encode($array);
}else if(isset($_POST["trocarMesa"])){
    debug(3, "Recebido pedido para inserir novo pagamento: ".$_POST["id"]);
    $array = trocarMesa($_POST["id"],$_POST["novaMesa"]);
    echo json_encode($array);
}else if(isset($_POST["excluirPedido"])){
    debug(3, "Recebido pedido para excluir pedido: ".$_POST["id"]);
    $array = excluirPedido($_POST["id"],$_POST["idConta"]);
    echo json_encode($array);
}else if(isset($_POST["salvarPedido"])){
    debug(3, "Recebido pedido para salvar pedido: ".$_POST["id"]);
    $array = salvarPedido($_POST["id"],$_POST["idConta"],$_POST["quantidadePedido"],$_POST["codigoProduto"],$_POST["dataHora"],$_POST["observacaoPedido"],$_POST["valorProduto"],$_POST["adicionais"]);
    echo json_encode($array);
}else if(isset($_POST["consultarPedido"])){
    debug(3, "Recebido pedido para consultar conta: ".$_POST["consultarPedido"]);
    $objP = new Pedido();
    $ret = $objP->load($_POST["consultarPedido"]);
    debug(3, "Pedido consultado: ".$objP);
    if($objP->__get("id")){

        $sql = "SELECT GROUP_CONCAT(adicionalId) adicionais FROM pedidoAdicional WHERE pedidoId = ".$objP->__get("id");
        $conexao = ConexaoSingleton::getConexao();
        $result = $conexao->executar($sql);
        $arraydados = $conexao->get_array($result);

        $array = array(
                "retorno"=>true,
                "id"=>$objP->__get("id"),
               "quantidade"=>$objP->__get("quantidade"),
               "produtoId"=>$objP->__get("produtoId"),
               "contaId"=>$objP->__get("contaId"),
               "observacao"=>$objP->__get("observacao"),
               "dataHora"=>$objP->__get("dataHora"),
               "usuarioId"=>$objP->__get("usuarioId"),
               "valorUnitario"=>$objP->__get("valorUnitario"),
                "adicionais"=>$arraydados[0]["adicionais"]
          );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}


?>

<?php
require_once ('../controller/sessao.php');
require_once ('../model/FormaPagamento.class.php');

function retornarArray(){
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT f.id, f.descricao, f.pedeObservacao FROM formaPagamento f");
    return $connect->get_array($result);
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT f.id, f.descricao, f.pedeObservacao FROM formaPagamento f");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("id","descricao","pedeObservacao"),
            "dados"=>$arraydados
        );
        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno retornarDadosLista: ".$array["retorno"]);
    return $array;
}

function salvar($id,$descricao,$pedeObservacao){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se produto existe.");
        $objF = new FormaPagamento();
        $ret = $objF->load($id);
        $idAntigo = $objF->__get("id");
        debug(3, "Código localizado: ".$idAntigo);
        
        $objF->__set("id",$id);
        $objF->__set("descricao",$descricao);
        $objF->__set("pedeObservacao",$pedeObservacao);
        if($idAntigo){
            $ret = $objF->update();
        }else{
            $ret = $objF->add();            
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "formaPagamento salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar formaPagamento: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar formaPagamento: ".$_POST["id"]);
    $ret = salvar($_POST["id"],$_POST["descricao"],$_POST["pedeObservacao"]);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Forma de Pagamento salvo com sucesso!"
            ));
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>"Erro ao salvar forma de pagamento!"));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar formaPagemtno: ".$_POST["consultar"]);
    $obj = new FormaPagamento();
    $ret = $obj->load($_POST["consultar"]);
    debug(3, "formaPagametno consultado: ".$obj->__get("id"));
    if($obj->__get("id")){
        $array = array(
            "retorno"=>true,
            "id"=>$obj->__get("id"),
            "descricao"=>$obj->__get("descricao"),
            "pedeObservacao"=>$obj->__get("pedeObservacao"),
            );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else if(isset($_POST["listar"])){
    debug(3, "Recebido pedido para listar os dados.");
    echo json_encode(retornarDadosLista());
}else if(isset($_POST["excluir"])){
    debug(3, "Recebido pedido para excluir formaPagamento: ".$_POST["excluir"]);
    $obj = new FormaPagamento();
    $ret = $obj->load($_POST["excluir"]);
    $ret = $obj->remove();
    debug(3, "Forma Pagamento excluido: ".$obj->__get("id")."-".$ret);
    if($ret){
        $array = array(
            "retorno"=>true,
            "msg"=>"Forma de pagamento excluido com sucesso!"
            );
    }else{
        $array = array(
            "retorno"=>false,
            "msg"=>"Erro ao excluir forma de pagamento!"
            );
    }
    echo json_encode($array);
}
?>

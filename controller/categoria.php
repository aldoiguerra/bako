<?php
require_once ('../controller/sessao.php');
require_once ('../model/Categoria.class.php');

function popularSelect(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,descricao FROM categoriaProduto");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        
        $tamanho = $connect->getNumResultados();
        for($i=0;$i<$tamanho;$i++){
            $arraylinha = $arraydados[$i];
            $arrayFinal[$arraylinha["id"]] = $arraylinha["descricao"];
        }
        $array = array(
            "retorno"=>true,
            "dados"=>$arrayFinal
        );        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno retornarDadosLista: ".$array["retorno"]);
    return $array;
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,descricao,categoriaProdutoPaiId FROM categoriaProduto");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("id","descricao","categoriaProdutoPaiId"),
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

function salvar($id,$descricao,$categoriaPai){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se categoria existe.");
        $objC = new Categoria();
        $ret = $objC->load($id);
        $idAntigo = $objC->__get("id");
        debug(3, "Categoria localizado: ".$idAntigo);
        
        $objC->__set("id",$id);
        $objC->__set("descricao",$descricao);
        $objC->__set("categoriaProdutoPaiId",$categoriaPai);

        if($idAntigo){
            $ret = $objC->update();
        }else{
            $ret = $objC->add();            
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Categoria salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar categoria: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar categoria: ".$_POST["id"]);
    $ret = salvar($_POST["id"],$_POST["descricao"],$_POST["categoriaPai"]);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Categoria salvo com sucesso!"
            ));
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>"Erro ao salvar categoria!"));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar categoria: ".$_POST["consultar"]);
    $objC = new Categoria();
    $ret = $objC->load($_POST["consultar"]);
    debug(3, "Categoria consultado: ".$objC->__get("id"));
    if($objC->__get("id")){
        $array = array(
            "retorno"=>true,
            "id"=>$objC->__get("id"),
            "descricao"=>$objC->__get("descricao"),
            "categoriaPai"=>$objC->__get("categoriaProdutoPaiId")
            );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else if(isset($_POST["popularSelect"])){
    debug(3, "Recebido pedido para select dos dados.");
    echo json_encode(popularSelect());
}else if(isset($_POST["listar"])){
    debug(3, "Recebido pedido para listar os dados.");
    echo json_encode(retornarDadosLista());
}else if(isset($_POST["excluir"])){
    debug(3, "Recebido pedido para excluir categoria: ".$_POST["excluir"]);
    $objC = new Categoria();
    $ret = $objC->load($_POST["excluir"]);
    $ret = $objC->remove();
    debug(3, "Categoria excluido: ".$objC->__get("id"));
    if($ret){
        $array = array(
            "retorno"=>true,
            "msg"=>"Categoria excluido com sucesso!"
            );
    }else{
        $array = array(
            "retorno"=>false,
            "msg"=>"Erro ao excluir categoria!"
            );
    }
    echo json_encode($array);
}

?>

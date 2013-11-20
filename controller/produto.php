<?php
require_once ('../controller/sessao.php');
require_once ('../model/Produto.class.php');

function retornarArray(){
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT p.id, p.nome, p.descricao, p.categoriaId, p.preco, p.status FROM produto p");
    return $connect->get_array($result);
}

function popularSelect(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,buscarDescricao(id) AS descricao FROM categoria");
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
    debug(3, "Retorno popular select produtos: ".$array["retorno"]);
    return $array;
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT p.id, p.nome, p.descricao, buscarDescricao(p.categoriaId) As categoria, p.preco, p.status FROM produto p");
    debug(3, "Numero de produtos listados: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("id","nome","descricao","categoria","preco","status"),
            "dados"=>$arraydados
        );
        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno produtos listados: ".$array["retorno"]);
    return $array;
}

function salvar($codigo,$nome,$descricao,$categoriaId,$preco,$status){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se produto existe.");
        $objP = new Produto();
        $ret = $objP->load($codigo);
        $idAntigo = $objP->__get("id");
        debug(3, "Código do produto localizado: ".$idAntigo);
        
        $objP->__set("codigo",$codigo);
        $objP->__set("nome",$nome);
        $objP->__set("descricao",$descricao);
        $objP->__set("categoriaId",$categoriaId);
        $objP->__set("preco",$preco);
        $objP->__set("status",$status);
        if($idAntigo){
            $ret = $objP->update();
        }else{
            $ret = $objP->add();            
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Produto salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar produto: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar produto: ".$_POST["codigo"]);
    $ret = salvar($_POST["codigo"],$_POST["nome"],$_POST["descricao"],$_POST["categoriaId"],$_POST["preco"],$_POST["status"]);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Produto salvo com sucesso!"
            ));
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>"Erro ao salvar produto!"));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar produto: ".$_POST["consultar"]);
    $objP = new Produto();
    $ret = $objP->load($_POST["consultar"]);
    debug(3, "Produto consultado: ".$objP->__get("id"));
    if($objP->__get("id")){
        $array = array(
            "retorno"=>true,
            "codigo"=>$objP->__get("id"),
            "nome"=>$objP->__get("nome"),
            "descricao"=>$objP->__get("descricao"),
            "categoriaId"=>$objP->__get("categoriaId"),
            "preco"=>$objP->__get("preco"),
            "status"=>$objP->__get("status")
            );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else if(isset($_POST["listar"])){
    debug(3, "Recebido pedido para listar os dados produto.");
    echo json_encode(retornarDadosLista());
}else if(isset($_POST["popularSelect"])){
    debug(3, "Recebido pedido para select dos dados produto.");
    echo json_encode(popularSelect());
}
?>

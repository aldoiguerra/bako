<?php
require_once ('../controller/sessao.php');
require_once ('../model/Categoria.class.php');

function popularSelect(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,buscarDescricao(id) AS descricao, status FROM categoria");
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

function pesquisarAdicionais(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id, descricao FROM adicional");
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
    debug(3, "Retorno retornar listaAdicionais: ".$array["retorno"]);
    return $array;
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT id,descricao,buscarDescricao(categoriaPaiId) AS categoriaPaiId, status FROM categoria");
    debug(3, "Numero de resultado obtidos: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("id","descricao","categoriaPaiId","status"),
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

function salvar($id,$descricao,$categoriaPai,$status,$adicional){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se categoria existe.");
        $objC = new Categoria();
        $ret = $objC->load($id);
        $idAntigo = $objC->__get("id");
        debug(3, "Categoria localizado: ".$idAntigo);
        
        $objC->__set("id",$id);
        $objC->__set("descricao",$descricao);
        $objC->__set("categoriaPaiId",$categoriaPai);
        $objC->__set("status",$status);

        if($idAntigo){
            $ret = $objC->update();
        }else{
            $ret = $objC->add();            
        }
        if(!$ret) throw new Exception ("");
        debug(3, "Categoria Criada: ".$objC->__get("id"));
        
        if($adicional != ""){
            $array = explode(",",$adicional);
            debug(3, "Quantidade Adicionais da Categoria: ".count($array));
            for ($i=0;$i<count($array);$i++){
                $idAdicional = $array[$i];
                $sql = "INSERT INTO categoriaAdicional (categoriaId,adicionalId) VALUES ('".$objC->__get("id")."','".$idAdicional."')";
                $connect = ConexaoSingleton::getConexao();
                $result = $connect->executar($sql);
                debug(3, "Adicionais da Categoria inserido: ".$idAdicional);
                debug(3, "Adicionais da Categoria inserido SQL: ".$sql);
            }
        }
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
    $ret = salvar($_POST["id"],$_POST["descricao"],$_POST["categoriaPai"],$_POST["status"],$_POST["adicional"]);
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
        $sql = "SELECT adicionalId,(SELECT descricao FROM adicional a WHERE a.id = adicionalId) As descricao FROM categoriaAdicional WHERE categoriaId = '".$objC->__get("id")."'";
        $connect = ConexaoSingleton::getConexao();
        $result = $connect->executar($sql);
        $arraydados = $connect->get_array($result);
        
        $array = array(
            "retorno"=>true,
            "id"=>$objC->__get("id"),
            "descricao"=>$objC->__get("descricao"),
            "categoriaPai"=>$objC->__get("categoriaPaiId"),
            "status"=>$objC->__get("status"),
            "adicionais"=>$arraydados
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
    $sql = "UPDATE produto SET categoriaId = NULL WHERE categoriaId = '".$_POST["excluir"]."'";
    $connect = ConexaoSingleton::getConexao();
    $retorno = $connect->executar($sql);
    debug(3, "Update produto antes de excluir: ".$sql."--".$retorno);
    $sql = "DELETE FROM categoriaAdicional WHERE categoriaId = '".$_POST["excluir"]."'";
    $retorno = $connect->executar($sql);
    debug(3, "Delete categAdicional antes de excluir: ".$sql."--".$retorno);
    $objC = new Categoria();
    $ret = $objC->load($_POST["excluir"]);
    $ret = $objC->remove();
    debug(3, "Categoria excluido: ".$objC->__get("id").$ret);
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
}else if(isset($_POST["pesquisarAdicionais"])){
    debug(3, "Recebido pedido para listar os adicionais categoria.");
    echo json_encode(pesquisarAdicionais());
}
?>

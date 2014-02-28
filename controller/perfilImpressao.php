<?php
require_once ('../controller/sessao.php');
require_once ('../model/PerfilImpressao.class.php');

function retornarArray(){
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT p.id, p.descricao, p.layout, p.tipoLayout, p.tipoTexto FROM perfilImpressao p");
    return $connect->get_array($result);
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT p.id, p.descricao, p.layout, p.tipoLayout, p.tipoTexto FROM perfilImpressao p");
    debug(3, "Numero de perfis de impressão listadas: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("id","descricao","layout","tipoLayout","tipoTexto"),
            "dados"=>$arraydados
        );
        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno perfis de impressão listadas: ".$array["retorno"]);
    return $array;
}

function salvar($id,$descricao,$layout,$tipoLayout,$tipoTexto){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se perfil de impressão existe.");
        $objP = new PerfilImpressao();
        $ret = $objP->load($id);
        $idAntigo = $objP->__get("id");
        debug(3, "Perfil de impressão localizada: ".$idAntigo);
        
        $objP->__set("id",$id);
        $objP->__set("descricao",$descricao);
        $objP->__set("layout",$layout);
        $objP->__set("tipoTexto",$tipoTexto);
        $objP->__set("tipoLayout",$tipoLayout);
        if($idAntigo){
            $ret = $objP->update();
        }else{
            $ret = $objP->add();            
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Perfil de impressão salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar perfil de impressão: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar perfil de impressão: ".$_POST["id"]);
    $ret = salvar($_POST["id"],$_POST["descricao"],$_POST["layout"],$_POST["tipoLayout"],$_POST["tipoTexto"]);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Perfil de impressão salvo com sucesso!"
            ));
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>"Erro ao salvar perfil de impressão!"));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar perfil impresssao: ".$_POST["consultar"]);
    $obj = new PerfilImpressao();
    $ret = $obj->load($_POST["consultar"]);
    debug(3, "perfil impressao consultado: ".$obj->__get("id"));
    if($obj->__get("id")){
        $array = array(
            "retorno"=>true,
            "id"=>$obj->__get("id"),
            "descricao"=>$obj->__get("descricao"),
            "layout"=>$obj->__get("layout"),
            "tipoLayout"=>$obj->__get("tipoLayout"),
            "tipoTexto"=>$obj->__get("tipoTexto")
            );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else if(isset($_POST["listar"])){
    debug(3, "Recebido pedido para listar os dados das perfis de impressão.");
    echo json_encode(retornarDadosLista());
}else if(isset($_POST["excluir"])){
    debug(3, "Recebido pedido para excluir perfil de impressão: ".$_POST["excluir"]);
    $obj = new PerfilImpressao();
    $ret = $obj->load($_POST["excluir"]);
    $ret = $obj->remove();
    debug(3, "Perfil Impressao excluida: ".$obj->__get("id")."-".$ret);
    if($ret){
        $array = array(
            "retorno"=>true,
            "msg"=>"Perfil de impressão excluida com sucesso!"
            );
    }else{
        $array = array(
            "retorno"=>false,
            "msg"=>"Erro ao excluir perfil de impressão!"
            );
    }
    echo json_encode($array);
}
?>

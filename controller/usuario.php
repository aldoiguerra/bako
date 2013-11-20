<?php
require_once ('../controller/sessao.php');
require_once ('../model/Usuario.class.php');

function retornarArray(){
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT u.usuario,u.nome,u.tipo,u.status FROM usuario u");
    return $connect->get_array($result);
}

function retornarDadosLista(){
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT u.usuario,u.nome,u.tipo,u.status FROM usuario u");
    debug(3, "Numero de usuários retornados na listagem: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "colunas"=>array("usuario","nome","tipo","status"),
            "dados"=>$arraydados
        );
        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno retornarDadosLista usuario: ".$array["retorno"]);
    return $array;
}

function salvar($usuario,$nome,$senha,$tipo,$status){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se usuário existe. ".$usuario);
        $objU = new Usuario();
        $ret = $objU->load($usuario);
        $idAntigo = $objU->__get("usuario");
        debug(3, "Usuário ".$usuario." localizado: ".$idAntigo.".");
        
        $objU->__set("usuario",$usuario);
        $objU->__set("nome",$nome);
        
        if($senha != ""){
           $objU->__set("senha",sha1($senha));
        }else{
           $objU->__set("senha",$objU->__get("senha"));
        }
        $objU->__set("tipo",$tipo);
        $objU->__set("status",$status);
        
        if($idAntigo){
            $ret = $objU->update();
        }else{
            $ret = $objU->add();            
        }
        
        if(!$ret) throw new Exception ();
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "Usuário salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar usuário: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar usuário: ".$_POST["usuario"]);
    $ret = salvar($_POST["usuario"],$_POST["nome"],$_POST["senha"],$_POST["tipo"],$_POST["status"]);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Usuário salvo com sucesso!"
            ));
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>"Erro ao salvar usuário!"));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar usuário: ".$_POST["consultar"]);
    $objU = new Usuario();
    $ret = $objU->load($_POST["consultar"]);
    debug(3, "Usuário consultado: ".$objU->__get("usuario"));
    if($objU->__get("usuario")){
        $array = array(
            "retorno"=>true,
            "usuario"=>$objU->__get("usuario"),
            "nome"=>$objU->__get("nome"),
            "senha"=>$objU->__get("senha"),
            "tipo"=>$objU->__get("tipo"),
            "status"=>$objU->__get("status")
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
    debug(3, "Recebido pedido para excluir usuário: ".$_POST["excluir"]);
    $objU = new Usuario();
    $ret = $objU->load($_POST["excluir"]);
    $ret = $objU->remove();
    debug(3, "Usuário excluido: ".$objU->__get("usuario"));
    if($ret){
        $array = array(
            "retorno"=>true,
            "msg"=>"Usuário excluido com sucesso!"
            );
    }else{
        $array = array(
            "retorno"=>false,
            "msg"=>"Erro ao excluir usuário!"
            );
    }
    echo json_encode($array);
}

?>

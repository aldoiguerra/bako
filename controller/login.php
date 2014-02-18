<?php
session_start();
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../dao/ConexaoSingleton.class.php');
require_once ('../model/Usuario.class.php');

function logar($nome,$senha,&$mensagem){
    
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $dados[0] = "ss";
    $dados[1] = $nome;
    $dados[2] = sha1($senha);
    $result = $connect->executarStmt("SELECT u.usuario AS usuario,u.status FROM usuario u WHERE u.usuario = ? AND u.senha = ? ",ConexaoSingleton::makeValuesReferenced($dados));
    if($connect->getNumResultados() == 0) return null;
    $ret = $connect->get_array($result);
    if($ret[0]["usuario"] != ""){
        if($ret[0]["status"] == "1"){
            $usuario = new Usuario();
            $retorno = $usuario->load($ret[0]["usuario"]);
            if($retorno){
                $_SESSION["usuario"] = serialize($usuario);
                return $ret[0]["usuario"];
            }else{
                return null;
            }
            return $ret[0]["usuario"];
        }else{
            //$mensagem = "Usuário inativo.";
        }
    }else{
        return null;
    }
    
}


?>

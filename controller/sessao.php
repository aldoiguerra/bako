<?php
session_start();
require_once ('../dao/ConexaoSingleton.class.php');
require_once ('../model/Usuario.class.php');
require_once ('../controller/debug.php');

if (isset($_SESSION["usuario"])){
    //usuario logado, permite que ele acesse
}else{
    //se náo ta logado volta para o login
    header("location: ../view/login.php");
}

$usuarioLogado = null;
if (isset($_SESSION["usuario"]) && ($_SESSION["usuario"] != "")) {
    $usuarioLogado = unserialize($_SESSION["usuario"]);
}

function retornaUrl(){
    $protocolo    = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
    $host         = $_SERVER['HTTP_HOST'];
    $script       = $_SERVER['SCRIPT_NAME'];
    $ascript      = explode("/",$script);
    $count = count($ascript);
    $script = "";
    for ($i = 0; $i < ($count-2); $i++) {
        $script = $script.$ascript[$i]."/";
    }
    return $protocolo."://".$host.$script;
}



?>

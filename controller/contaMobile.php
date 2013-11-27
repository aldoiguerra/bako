<?php
session_start();
require_once ('../dao/ConexaoSingleton.class.php');
require_once ('../controller/debug.php');
require_once ('../model/Usuario.class.php');
require_once ('../controller/contaMetodosComum.php');

debug(3, "Acessando Conta mobile");

if(isset($_POST["verificarLogin"])){
    debug(3, "Verificando Login.");
    if (isset($_SESSION["usuario"])){
        debug(3, "Usuário mobile logado.");
        echo 1;
    }else{
        debug(3, "Usuário não está logado.");
        echo 0;
    }
    return;
}else if(isset($_POST["sairSistema"])){
    debug(3, "Efetuando Logout.");
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])){
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();
    echo 1;
    return;
}else if(isset($_POST["efetuarLogin"])){
    debug(3, "Efetuando Login.");
    debug(3, "Usuário: ".$_POST["usuario"]." - Senha: ".$_POST["senha"]);
    $connect = ConexaoSingleton::getConexao();
    $dados[0] = "ss";
    $dados[1] = $_POST["usuario"];
    $dados[2] = sha1($_POST["senha"]);
    $result = $connect->executarStmt("SELECT u.usuario AS usuario FROM usuario u WHERE u.usuario = ? AND u.senha = ? ",ConexaoSingleton::makeValuesReferenced($dados));
    if($connect->getNumResultados() == 0){
        echo 0;
        return;
    }
    $ret = $connect->get_array($result);
    debug(3, "Usuário Logado: ".$ret[0]["usuario"]);
    if($ret[0]["usuario"] != ""){
        $usuario = new Usuario();
        $retorno = $usuario->load($ret[0]["usuario"]);
        if($retorno){
            $_SESSION["usuario"] = serialize($usuario);
            echo 1; 
        }else{
            echo 0; 
        }
    }else{
        echo 0; 
    }
    return;
}

if (isset($_SESSION["usuario"])){
    //usuario logado, permite que ele acesse
    debug(3, "Usuário mobile logado.");
}else{
    //se náo ta logado volta para o login
    debug(3, "Usuário não está logado.");
    return;
}

$usuarioLogado = null;
if (isset($_SESSION["usuario"]) && ($_SESSION["usuario"] != "")) {
    $usuarioLogado = unserialize($_SESSION["usuario"]);
}

if(isset($_POST["buscarDados"])){
    debug(3, "Recebido pedido para buscar os dados. ");
    $array = array("produtos"=>buscarProdutos(),
                    "categorias"=>buscarCategorias(),
                    "adicionais"=>buscarAdicionais()
        );
    echo json_encode($array);
    return;    
}else if(isset($_POST["buscarMesas"])){
    debug(3, "Recebido pedido para buscar mesas. ");
    $array = array("contas"=>buscarContas());
    echo json_encode($array);
    return;    
}

?>

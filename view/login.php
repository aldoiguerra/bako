<?php
require_once ('../controller/login.php');

$divres = "hidden";
$mensagem = "";
if(isset($_POST["logar"])){
    $ret = logar($_POST["usuario"], $_POST["senha"]);
    if($ret){
        header("location: ../view/index.php");
    }else{
        $divres = "visible";
        $mensagem = "Usuário e/ou senha incorreto.";
    }
}

?>
<!Doctype html>
<html>
<head>
<title>Login</title>
<?php include 'css_include.php';?> 
</head>
<body style="background:rgb(255,69,0)">
<?php include 'cabecalho.php';?>

    <div class="container">
        <div class="row">
            <form method="POST" style="width:300px;margin:0 auto;">
                <div>
                    <div>
                        <input type="text" size="30" placeholder="Usuário" autofocus name="usuario" />
                    </div>
                    <div style="margin:1px 0 5px 0">
                        <input type="password" placeholder="Senha" size="30" name="senha" />
                    </div>
                    <p class="inline-block" style="width:191px;visibility:<?php echo $divres;?>;"><?php echo $mensagem;?></p>
                    <input type="submit" name="logar" value="Logar" />
                </div>
             
            </form>
            
        </div>
    </div>

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 

</body>
</html>
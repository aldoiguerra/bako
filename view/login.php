<?php
require_once ('../controller/login.php');

$divres = "none";
$mensagem = "";
if(isset($_POST["logar"])){
    $ret = logar($_POST["usuario"], $_POST["senha"]);
    if($ret){
        header("location: ../view/index.php");
    }else{
        $divres = "block";
        $mensagem = "Erro. Usuário ou senha incorreto.";
    }
}

?>
<!Doctype html>
<html>
<head>
<title>Login</title>
<?php include 'css_include.php';?> 
</head>
<body>
<?php include 'cabecalho.php';?>

    <div class="row">
        <div class="col-9">
            <h1>Login</h1>
            <div style="display:<?php echo $divres;?>;">
                <h3><?php echo $mensagem;?></h3><br />
            </div>
            <form method="POST">
                <div class="field">
                        <span class="label">Usuário</span>
                        <input type="text" size="30" name="usuario" />
                </div>
                <div  class="field">
                        <span class="label">Senha</span>
                        <input type="password" size="30" name="senha" />
                </div>
                <div class="button-bar">
                        <input type="submit" name="logar" value="Logar" class="bt-success" />
                </div>
            </form>
        </div>
    </div>

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 

</body>
</html>
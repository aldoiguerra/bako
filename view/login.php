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
<title>Home</title>
<?php include 'css_include.php';?> 
</head>
<body>
<?php include 'cabecalho.php';?>

<main>
<h1>Login</h1>
<div style="display:<?php echo $divres;?>;">
    <h3><?php echo $mensagem;?></h3><br />
</div>
<form method="POST">
    <div>
            <label>Usuário</label>
            <input type="text" size="30" name="usuario" />
    </div>
    <div>
            <label>Senha</label>
            <input type="password" size="30" name="senha" />
    </div>
    <div style="margin-top:20px;">
            <input type="submit" name="logar" value="Logar" />
    </div>
</form>

</main>

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 

</body>
</html>
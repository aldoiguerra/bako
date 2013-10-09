<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/usuario.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlUsuario = "<?php echo retornaUrl()."controller/usuario.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>

<main>

    <h1>Cadastro de usuário</h1>
    <div id="retorno">

    </div>
    <div id="lista">

    </div>

    <div id="cadastro">
        <div style="margin-top:20px;">
         
        </div> 
            <div>
                    <label>Usuário</label>
                    <input type="text" size="30" id="usuario" />
            </div>
            <div>
                    <label>Nome</label>
                    <input type="text" size="50" id="nome" >
            </div>
            <div>
                    <label>Senha</label>
                    <input type="password" size="30" id="senha" />
            </div>
            <div>
                    <label>Acesso</label>
                    <select id="tipo">
                            <option value="1" >Administrador</option>
                            <option value="2" >Garçom</option>
                    </select>
            </div>
            <div style="margin-top:20px;">
                <input type="button" value="Limpar" id="btnLimpar" />
                <input type="button" value="Novo" id="btnNovo" />
                <input type="button" value="Editar" id="btnEditar" style='display: none;' />
                <input type="button" value="Salvar" id="btnSalvar" style='display: none;' />
                <input type="button" value="Excluir" id="btnExcluir" style='display: none;' />
            </div>
    </div>

</main>

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/usuarios.js"></script>

</body>
</html>
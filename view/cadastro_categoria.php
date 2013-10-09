<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/categoria.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlCategoria = "<?php echo retornaUrl()."controller/categoria.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>

<main>

    <h1>Cadastro de categoria</h1>
    <div id="retorno">

    </div>
    <div id="lista">

    </div>

    <div id="cadastro">
        <div style="margin-top:20px;">
         
        </div> 
            <div>
                    <label>Código</label>
                    <input type="text" size="30" id="id" />
            </div>
            <div>
                    <label>Descrição</label>
                    <input type="text" size="50" id="descricao" >
            </div>
            <div>
                    <label>Categoria pai</label>
                    <select id="slCategoria">
                        
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
<script type="text/javascript" src="../js/categoria.js"></script>

</body>
</html>
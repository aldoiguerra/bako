<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/relatorio.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlRelatorio = "<?php echo retornaUrl()."controller/relatorio.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>

    <section id="section">
        <div class="title">
            <h1>Relatorio</h1>
        </div>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <div class="field">
                        <label class="label">Data Inicial</label>
                        <input type="text" size="30" id="dtInicial"/>
                </div>
                <div class="field">
                        <label class="label">Data Final</label>
                        <input type="text" size="30" id="dtFinal" >
                </div>
            </div>
         </div>
        <div class="button-bar">
            <!--input type="button" value="Limpar" id="btnLimpar" class="bt-alert"/-->
            <input type="button" value="Gerar" id="btnGerar" class="bt-normal"/>
            <!--input type="button" value="Editar" id="btnEditar" style='display: none;' class="bt-normal"/>
            <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
            <input type="button" value="Excluir" id="btnExcluir" style='display: none;' class="bt-negative" /-->
        </div>

    </section>
  
<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/relatorio.js"></script>

</body>
</html>
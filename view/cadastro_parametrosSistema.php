<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/parametrosSistema.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlParametroSistema = "<?php echo retornaUrl()."controller/parametrosSistema.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>
    
    <section id="section">
        <h1>Cadastro dos parâmetros do sistema</h1>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <div class="field">
                        <label class="label">Quantidade de mesas</label>
                        <input type="text" size="50" id="qtdMesas" >
                </div>
                <div class="field">
                        <label class="label">Valor da taxa de serviço %</label>
                        <input type="text" size="50" id="valorTxServico" >
                </div>
            </div>
         </div>
        <div class="button-bar">
            <!--input type="button" value="Limpar" id="btnLimpar" class="bt-alert"/-->
            <input type="button" value="Editar" id="btnEditar" class="bt-alert"/>
            <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
        </div>

    </section>
  
<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/parametrosSistema.js"></script>

</body>
</html>
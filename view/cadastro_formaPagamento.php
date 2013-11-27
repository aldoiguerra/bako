<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/formaPagamento.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlFormaPagamento = "<?php echo retornaUrl()."controller/formaPagamento.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>
    
    <aside>
        <div class="search-page">
           <!--     <input type="text" id="pesquisar" placeholder="Pesquise pelo produto desejado" size="34" /> -->
        </div>
        <ul id="lista"></ul>
    </aside>

    <section id="section">
        <div class="title">
            <h1>Cadastro de Forma de Pagamento</h1>
        </div>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <input type="hidden" id="codigo" />
                <div class="field">
                        <label class="label">Descrição</label>
                        <input type="text" size="50" id="descricao" >
                </div>
                <div class="field">
                        <label class="label">Pede Observação</label>
                        <input type="checkbox" name="pObs"  id="pObs" />
                </div>
            </div>
         </div>
        <div class="button-bar">
            <input type="button" value="Limpar" id="btnLimpar" class="bt-alert"/>
            <input type="button" value="Novo" id="btnNovo" class="bt-alert"/>
            <input type="button" value="Editar" id="btnEditar" style='display: none;' class="bt-alert"/>
            <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
            <input type="button" value="Excluir" id="btnExcluir" style='display: none;' class="bt-negative" />
        </div>

    </section>
  
<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/formaPagamento.js"></script>

</body>
</html>
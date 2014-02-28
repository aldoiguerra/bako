<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/perfilImpressao.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlPerfil = "<?php echo retornaUrl()."controller/perfilImpressao.php"; ?>";
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
            <h1>Cadastro de Perfil de Impressão</h1>
        </div>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-4" >
                <input type="hidden" id="codigo" />
                <div class="field">
                        <label class="label">Descrição</label>
                        <input type="text" size="50" id="descricao" >
                </div>
            </div>
            <div class="col-2" >
                <div class="field">
                        <label class="label">Tipo Layout</label>
                        <label class="label"><input type="radio" name="tipoLayout" value="1">Conta</label>
                        <label class="label"><input type="radio" name="tipoLayout" value="2">Pedido</label>
                </div>
            </div>
            <div class="col-6" >
                <div class="field">
                        <label class="label">Tipo Texto</label>
                        <label class="label"><input type="radio" name="tipoTexto" value="1">Texto</label>
                        <label class="label"><input type="radio" name="tipoTexto" value="2">Html</label>
                </div>
            </div>
         </div>
        <div class="row">
            <div class="col-12" >
                <div class="field">
                        <label class="label">Layout</label>
                        <textarea id="layout" style="width: 600px;height: 600px;"></textarea>
                </div>
            </div>
         </div>
        <div class="button-bar">
            <input type="button" value="Limpar" id="btnLimpar" class="bt-alert"/>
            <input type="button" value="Novo" id="btnNovo" class="bt-normal"/>
            <input type="button" value="Editar" id="btnEditar" style='display: none;' class="bt-normal"/>
            <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
            <input type="button" value="Excluir" id="btnExcluir" style='display: none;' class="bt-negative" />
        </div>

    </section>
  
<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
    <script type="text/javascript" src="../js/perfilImpressao.js"></script>

</body>
</html>
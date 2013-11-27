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


<aside>
    <div class="search-page">
            <input type="text" id="pesquisar" placeholder="Pesquise pelo produto desejado" size="34" />
    </div>
    <ul id="lista"></ul>
</aside>
    
<section id="section">
    <div class="title">
        <h1>Cadastro de categoria</h1>
    </div>
    <div class="row">
        <div id="retorno">

        </div>
    </div>
    <div class="row">
        <div class="col-12" id="cadastro">
                    <input type="hidden" id="id" readonly/>
            <div class="field">
                    <label class="label">Descrição</label>
                    <input type="text" size="50" id="descricao" >
            </div>
            <div class="field">
                    <label class="label">Categoria pai</label>
                    <select id="slCategoria" onchange="document.getElementById('prefixo').value=this.value;this.title = this.selectedIndex.innerHTML;" style="max-width:280px;">

                    </select>
            </div>
            <div class="field">
                <span class="label">Status</span>
                <div class="toggle">
                    <label><input type="radio" name="rAI" id="ckAtivo" checked="true" value="1"/><span>Ativo</span></label>
                    <label><input type="radio" name="rAI" id="ckInativo" value="0"/><span>Inativo</span></label>
                </div>
            </div>
            <!--div class="field">
                    <label class="label">Adicional</label>
                    <input type="list" size="40" list="adicional" id="adicionais"/>
                    <datalist id="adicional">
                        <option value="teste"></option>
                    </datalist>
            </div-->
            <div class="field">
                <label class="label">Adicionais categoria</label>
                <div id="divAdicionais" class="check-set">
                </div>
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
<script type="text/javascript" src="../js/categoria.js"></script>

</body>
</html>
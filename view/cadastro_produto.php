<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/produto.php');
?>
<!Doctype html>
<html>
<head>
<title>Home</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlProduto = "<?php echo retornaUrl()."controller/produto.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>
    
    <aside>
        <div class="search-page">
                <input type="text" placeholder="Pesquise pelo produto desejado" size="34" />
        </div>
        <ul id="lista"></ul>
    </aside>

    <section id="section">
        <h1>Cadastro de produtos</h1>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <div class="field" style="display: none">
                        <label class="label">Código</label>
                        <input type="text" size="30" id="codigo" disabled/>
                </div>
                <div class="field">
                        <label class="label">Nome</label>
                        <input type="text" size="50" id="nome" >
                </div>
                <div class="field">
                        <label class="label">Descrição</label>
                        <input type="text" size="30" id="descricao" />
                </div>
                <div class="field">
                        <label class="label">Preço</label>
                        <input type="text" size="30" id="preco" />
                </div>
                <div class="field">
                    <label class="label">Categoria</label>
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
<script type="text/javascript" src="../js/produto.js"></script>

</body>
</html>
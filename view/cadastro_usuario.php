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
    
    <aside>
        <div class="search-page">
                <input type="text" id="pesquisar" placeholder="Pesquise pelo usuário desejado" size="34" />
        </div>
        <ul id="lista"></ul>
    </aside>

    <section id="section">
        <h1>Cadastro de usuários</h1>
        <div class="row">
            <div id="retorno">

            </div>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <div class="field">
                        <label class="label">Usuário</label>
                        <input type="text" size="30" id="usuario" />
                </div>
                <div class="field">
                        <label class="label">Nome</label>
                        <input type="text" size="50" id="nome" >
                </div>
                <div class="field">
                        <label class="label">Senha</label>
                        <input type="text" size="30" id="senha" />
                </div>
                <div class="field">
                        <label class="label">Tipo</label>
                        <select id="tipo">
                            <option value="0"></option>
                            <option value="1">Administrador</option>
                            <option value="2">Garçom</option>
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
<script type="text/javascript" src="../js/usuarios.js"></script>

</body>
</html>
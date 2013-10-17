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
    
    <!--h1>Cadastro de usuário</h1>
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
    </div-->


<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/usuarios.js"></script>

</body>
</html>
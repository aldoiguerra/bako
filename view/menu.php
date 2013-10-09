<?php
require_once ('../model/Usuario.class.php');

$protocolo = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$ascript = explode("/", $script);
$count = count($ascript);
$script = "";
for ($i = 0; $i < ($count - 2); $i++) {
    $script = $script . $ascript[$i] . "/";
}
$caminho = $protocolo . "://" . $host . $script;

$usuario = null;
if (isset($_SESSION["usuario"]) && ($_SESSION["usuario"] != "")) {
    $usuario = unserialize($_SESSION["usuario"]);
}
?>

<div>
<div>
    <div>
        <a href="<?php echo $caminho . "view/index.php"; ?>">home</a>
    </div>
    <div>
        <a href="<?php echo $caminho . "view/sobre.php"; ?>">sobre</a>
    </div>
    <?php if ($usuario) { ?>
        <?php if ($usuario->__get("tipo") == 1) { ?>
            <div>
                <a href="javascript:mudarMenu('menuAdmin');">Administração</a>
            </div>
            <div id="menuAdmin" style="display:none;">
                <ul>
                        <li><a href="<?php echo $caminho . "view/cadastro_usuario.php"; ?>">Usuários</a></li>
                        <li><a href="<?php echo $caminho . "view/cadastro_produto.php"; ?>">Produtos</a></li>
                        <li><a href="<?php echo $caminho . "view/cadastro_categoria.php"; ?>">Categorias</a></li>
                </ul>
            </div>
        <?php } ?>
        <div>
            <a href="<?php echo $caminho . "controller/sair.php"; ?>">sair</a>
        </div>
    <?php } ?>
</div>
</div>
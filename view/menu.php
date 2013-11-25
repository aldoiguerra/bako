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

<nav>
    <ul>
        <!--<li><a href="<?php //echo $caminho . "view/index.php"; ?>">Home</a></li>
        <li><a href="<?php //echo $caminho . "view/sobre.php"; ?>">Sobre</a></li>-->
        <?php if ($usuario) { ?>
            <li><a href="<?php echo $caminho . "view/manter_conta.php"; ?>">Mesas</a></li>
            <?php if ($usuario->__get("tipo") == 1) { ?>
                
                            <li><a href="<?php echo $caminho . "view/cadastro_usuario.php"; ?>">Usuários</a></li>
                            <li><a href="<?php echo $caminho . "view/cadastro_produto.php"; ?>">Produtos</a></li>
                            <li><a href="<?php echo $caminho . "view/cadastro_categoria.php"; ?>">Categorias</a></li>
                            <li><a href="<?php echo $caminho . "view/cadastro_adicional.php"; ?>">Adicionais</a></li>
                            <li><a href="<?php echo $caminho . "view/cadastro_formaPagamento.php"; ?>">Forma de Pagamento</a></li>
                            <li><a href="<?php echo $caminho . "view/cadastro_parametrosSistema.php"; ?>">Configurações</a></li>
            <?php } ?>
            <li><a class="icon-logout" href="<?php echo $caminho . "controller/sair.php"; ?>">Sair</a></li>
        <?php } ?>
       <li id="displayHora"></li>
    </ul>
</nav>

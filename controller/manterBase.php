<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/manterBaseV_1.0.php');

$mensagem = "";

function conectar($host,$usuario,$senha,$banco){

    $con=mysqli_connect($host,$usuario,$senha,$banco);

    // Check connection
    if (mysqli_connect_errno($con))
      {
        die("Falhar na conexão com o banco de dados.: " . mysqli_connect_error());
      }else{
        gerarMensagem("Banco de dados conectado.");
      }
    return $con;
}

function gerarMensagem($msg){
    global $mensagem;
    $mensagem = $mensagem . "<br />" . $msg;
}

function excluirTabelas($conexao){

    try{
        gerarMensagem("Abrindo transação.");
        $conexao->autocommit(FALSE);
        
        $tabelas[0] = "mesa";
        $tabelas[1] = "pagamento";
        $tabelas[2] = "parametroSistema";
        $tabelas[3] = "formaPagamento";
        $tabelas[4] = "pedidoAdicional";
        $tabelas[5] = "pedido";
        $tabelas[6] = "conta";
        $tabelas[7] = "produto";
        $tabelas[8] = "categoriaAdicional";
        $tabelas[9] = "categoria";
        $tabelas[10] = "adicional";
        $tabelas[11] = "usuario";
                
        for($i=0;$i<count($tabelas);$i++){
            $sql = "DROP TABLE IF EXISTS ".$tabelas[$i];
            gerarMensagem("Efetuando consulta: ".$sql);
            $resultado = mysqli_query($conexao,$sql);
            if(!$resultado) {
                //if($conexao->errno != 1051){
                //    throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //}else{
                    gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //}
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
        }
        
        $conexao->commit();
        gerarMensagem("Commit efetuado.");
    } catch (Exception $ex) {
        gerarMensagem("Excessão gerada: ".$ex->getMessage());
        $conexao->rollback();
        gerarMensagem("Rollback efetuado.");
    }    
    return;
}

function criarTabelas($conexao){

    try{
        gerarMensagem("Abrindo transação.");
        $conexao->autocommit(FALSE);
        
        $tabelas = retornarTabelas();
                
        for($i=0;$i<count($tabelas);$i++){
            $sql = $tabelas[$i];
            gerarMensagem("Efetuando consulta: ".$sql);
            $resultado = mysqli_query($conexao,$sql);
            if(!$resultado) {
                //if($conexao->errno != 1051){
                //    throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //}else{
                    gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //}
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
        }
        
        $conexao->commit();
        gerarMensagem("Commit efetuado.");
    } catch (Exception $ex) {
        gerarMensagem("Excessão gerada: ".$ex->getMessage());
        $conexao->rollback();
        gerarMensagem("Rollback efetuado.");
    }    
    return;
}

function popularTestes($conexao){

    try{
        //gerarMensagem("Abrindo transação.");
        //$conexao->autocommit(FALSE);
        

        for($i=1;$i<50;$i++){
           $sql= "INSERT INTO mesa (numMesa) VALUES ($i)";
           gerarMensagem("Efetuando consulta: ".$sql);
           $ret = mysqli_query($conexao,$sql);
            if(!$ret) {
                gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
       }

       //Categoria
       for($i=1;$i<10;$i++){
           $sql = "INSERT INTO categoria (id,descricao,categoriaPaiId,status) VALUES ('','Categoria $i',NULL,'1')";
           gerarMensagem("Efetuando consulta: ".$sql);
           $ret = mysqli_query($conexao,$sql);
            if(!$ret) {
                gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
       }

       //Categoria
       for($i=1;$i<100;$i++){
           $sql = "INSERT INTO produto (id,nome,descricao,categoriaId,preco,status) VALUES ('','Produto $i','Produto $i','".(($i%10)+1)."','".($i%10*1.87)."','1')";
           gerarMensagem("Efetuando consulta: ".$sql);
           $ret = mysqli_query($conexao,$sql);
            if(!$ret) {
                gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                //throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
       }

       /*for($i=1;$i<=10;$i++){
           $sql = "INSERT INTO conta (id,dataHoraAbertura,dataHoraFechamento,numMesa,qtdPessoas,descricao,taxaServico,status) VALUE (NULL,now() - interval 1 day,NOW(),$i,".($i+3).",'',1,1)";
           gerarMensagem("Efetuando consulta: ".$sql);
           $ret = mysqli_query($conexao,$sql);
            if(!$ret) {
                gerarMensagem("ERRO: (". $conexao->errno . ") " . $conexao->error);
                throw new Exception ("ERRO: (". $conexao->errno . ") " . $conexao->error);
            }else{
                gerarMensagem("Consulta efetuada com sucesso.");
            }
       }*/       
               
        //$conexao->commit();
        //gerarMensagem("Commit efetuado.");
    } catch (Exception $ex) {
        gerarMensagem("Excessão gerada: ".$ex->getMessage());
        //$conexao->rollback();
        //gerarMensagem("Rollback efetuado.");
    }    
    return;
}

if(isset($_POST["excluirTabelas"])){
    $conexao = conectar($_POST["host"],$_POST["usuario"],$_POST["senha"],$_POST["banco"]);
    excluirTabelas($conexao);
    echo $mensagem;
    return;
}else if(isset($_POST["criarTabelas"])){
    $conexao = conectar($_POST["host"],$_POST["usuario"],$_POST["senha"],$_POST["banco"]);
    criarTabelas($conexao);
    echo $mensagem;
    return;
}else if(isset($_POST["popularTestes"])){
    $conexao = conectar($_POST["host"],$_POST["usuario"],$_POST["senha"],$_POST["banco"]);
    popularTestes($conexao);
    echo $mensagem;
    return;
}

?>
<!Doctype html>
<html>
<head>
<title>Manter base de dados</title>
<?php include '../view/css_include.php';?> 
</head>
<body>
    <section id="section">
        <div class="title">
            <h1>Manter bases de dados</h1>
        </div>
        <div class="row">
            <div class="col-12" id="cadastro">
                <div class="col-3 field">
                        <label class="label">Host</label>
                        <input type="text" size="30" id="host" />
                </div>
                <div class="col-3 field">
                        <label class="label">Banco</label>
                        <input type="text" size="30" id="banco" >
                </div>
                <div class="col-3 field">
                        <label class="label">Usuário</label>
                        <input type="text" size="30" id="usuario" />
                </div>
                <div class="col-3 field">
                        <label class="label">Senha</label>
                        <input type="text" size="30" id="senha" />
                </div>
            </div>
         </div>
        <div class="row">
            <input type="button" value="Criar tabelas" id="btnNovo" class="bt-success" onclick="criarTabelas()"/>
            <input type="button" value="Excluir tabelas" id="btnLimpar" class="bt-negative" onclick="excluirTabelas();"/>
            <!--<input type="button" value="Editar" id="btnEditar" class="bt-normal"/>
            <input type="button" value="Salvar" id="btnSalvar" class="bt-success"/>-->
            <input type="button" value="Dados de teste" id="btnExcluir" class="bt-negative" onclick="popularTestes();" />
        </div>
        <div class="row">
            <div id="retorno">

            </div>
        </div>

    </section>

<?php include '../view/rodape.php';?>
<script type="text/javascript" src="../js/jquery.js"></script>

</body>
<script>

function validarCampos(){
    var host = $("#host").val();
    var banco = $("#banco").val();
    var usuario = $("#usuario").val();
    var senha = $("#senha").val();
    
    if(host == ""){
        alert("Insira o Host.");
        return 0;
    }
    if(banco == ""){
        alert("Insira o BAnco.");
        return 0;
    }
    if(usuario == ""){
        alert("Insira o Usuário.");
        return 0;
    }

    return 1;
}    

function excluirTabelas(){
    
    if(!validarCampos()){
        $("#retorno").html("");
        return;
    }
    
    if(!confirm("Confirma a exclusão das tabelas?")){
        return;
    }

    var variaveis = {"excluirTabelas": "1",
                    "host":$("#host").val(),
                    "banco":$("#banco").val(),
                    "usuario":$("#usuario").val(),
                    "senha":$("#senha").val()
                };
    $.post(window.location.href, variaveis,
    function(data) {
        $("#retorno").html(data);
    }, "text").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    
}    

function criarTabelas(){
    
    if(!validarCampos()){
        $("#retorno").html("");
        return;
    }
    
    if(!confirm("Confirma a exclusão das tabelas?")){
        return;
    }

    var variaveis = {"criarTabelas": "1",
                    "host":$("#host").val(),
                    "banco":$("#banco").val(),
                    "usuario":$("#usuario").val(),
                    "senha":$("#senha").val()
                };
    $.post(window.location.href, variaveis,
    function(data) {
        $("#retorno").html(data);
    }, "text").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    
}    

function popularTestes(){
    
    if(!validarCampos()){
        $("#retorno").html("");
        return;
    }
    
    if(!confirm("Confirma a exclusão das tabelas?")){
        return;
    }

    var variaveis = {"popularTestes": "1",
                    "host":$("#host").val(),
                    "banco":$("#banco").val(),
                    "usuario":$("#usuario").val(),
                    "senha":$("#senha").val()
                };
    $.post(window.location.href, variaveis,
    function(data) {
        $("#retorno").html(data);
    }, "text").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    
}    

</script>
</html>

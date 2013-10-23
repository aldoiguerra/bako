<?php


/*$string = "";
$connect = ConexaoSingleton::getConexao();
$result = $connect->executar("SELECT * FROM usuraio");
$ret = $connect->get_array($result);
$tamanho = sizeof($ret);
if(ConexaoSingleton::getConexao()->getNumResultados() == 0) return null;
*/

//$GLOBALS["usuario"] = "admin";
/*
function teste(){
    echo $GLOBALS["usuario"];    
}

teste();
echo $GLOBALS["usuario"];

//echo "Teste: ".sha1("S%s@dm1n")."<br />";

$con=mysqli_connect("localhost","root","","dbestabelecimento");

// Check connection
if (mysqli_connect_errno($con))
  {
    die("Falhar na conexão com o banco de dados.: " . mysqli_connect_error());
  }else{
    echo("Banco de dados conectado.<br />");
  }
/*
//QUERY
$result = mysqli_query($con,"SELECT * FROM usuario WHERE id = 2 ");

if(!$result){
  die('There was an error running the query [' . $con->error . ']');
}  

echo $result->num_rows."<br />";
echo $con->affected_rows."<br />";
 
$i =0;
while($row = mysqli_fetch_array($result))
  {
    echo "Linha: $i<br />";
        foreach ($row as $key => $value) {
            echo "--------Key: $key; Value: $value<br />";
        }
     $i++;
  }

echo "update usuario<br />";  
  
 //UPDATE DELETE INSERT 
 $result = mysqli_query($con,"UPDATE usuario SET nome = 'usuario' WHERE id = 2 ");

if(!$result){
    die('There was an error running the query [' . $con->error . ']');
}  

//echo $result->num_rows;
echo $con->affected_rows."<br />";

//Statment
echo "stmt<br />";

$id[0]['tipo'] = 'i';
$id[0]['valor'] = 2;

if (!($statement = $con->prepare("SELECT * FROM usuario WHERE id = ?"))) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}

if (!($statement->bind_param($id[0]['tipo'], $id[0]['valor']))) {
    die("Erro ao setar parametros: (" . $con->errno . ") " . $con->error);
}

if (!($statement->execute())) {
    die("Erro ao executar query: (" . $con->errno . ") " . $con->error);
}

$result = $statement->get_result();

//$row = $res->fetch_assoc();
echo $result->num_rows."<br />";

$i =0;
while($row = mysqli_fetch_array($result))
  {
    echo "Linha: $i<br />";
        foreach ($row as $key => $value) {
            echo "--------Key: $key; Value: $value<br />";
        }
     $i++;
  }

echo "stmt update<br />";

$dados[0] = "ss";
$dados[1] = sha1("S%s@dm1n");
$dados[2] = "sysadmin";

if (!($statement = $con->prepare("SELECT u.usuario AS usuario FROM usuario u WHERE u.senha = ? AND u.usuario = ?"))) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}

var_dump ($dados);

$ref    = new ReflectionClass('mysqli_stmt'); 
$method = $ref->getMethod("bind_param");  
if (!($method->invokeArgs($statement,$dados))) {
    die("Erro ao preparar os dados da query: (" . $con->errno . ") " . $con->error);
}

if (!($statement->execute())) {
    die("Erro ao executar query: (" . $con->errno . ") " . $con->error);
}

$result = $statement->get_result();

if ($result) {
    echo "resultado OK<br />";
    echo $result->num_rows."<br />";
    $i =0;
    while($row = mysqli_fetch_array($result))
      {
        echo "Linha: $i<br />";
            foreach ($row as $key => $value) {
                echo "--------Key: $key; Value: $value<br />";
            }
         $i++;
      }
} else {
    echo "resultado falha";
}

echo $statement->affected_rows."<br />";
echo $con->affected_rows."<br />";
  
//$returned_name = "";

//$statement->bind_result($returned_name);

//while($statement->fetch()){
//    echo "Retorno: ".$returned_name . '<br />';
//}

//$statement->free_result();
  
mysqli_close($con);    

echo "Iniando teste com logar<br />";

require_once ('../controller/login.php');

echo "Logar: ".logar("sysadmin", "123456");
*/

//dados de teste

require_once ('../dao/ConexaoSingleton.class.php');

//Mesa
for($i=1;$i<50;$i++){
    $sql= "INSERT INTO mesa (numMesa) VALUES ($i)";
    echo $sql."<br />";
    $ret = ConexaoSingleton::getConexao()->executar($sql);
    echo $ret."<br />";
}

//Categoria
for($i=1;$i<10;$i++){
    $sql = "INSERT INTO categoria (id,descricao,categoriaPaiId,status) VALUES ('','Categoria $i',NULL,'1')";
    echo $sql."<br />";
    $ret = ConexaoSingleton::getConexao()->executar($sql);
    echo $ret."<br />";
}

//Categoria
for($i=1;$i<100;$i++){
    $sql = "INSERT INTO produto (id,nome,descricao,categoriaId,preco,status) VALUES ('','Produto $i','Produto $i','".($i%10)."','".($i%10*1.87)."','1')";
    echo $sql."<br />";
    $ret = ConexaoSingleton::getConexao()->executar($sql);
    echo $ret."<br />";
}

?>

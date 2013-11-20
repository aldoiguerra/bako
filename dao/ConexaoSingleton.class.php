<?php
require_once ('../controller/debug.php');
require_once ('../dao/dados.php');

/**
 * @author aldoig
 * @version 1.0
 * @created 28-mai-2011 12:24:36
 */
class ConexaoSingleton
{
    private static $instancia = null;
    private static $lastId = null;
    private static $lastQuery = null;

    private $usuario = null;
    private $senha = null;
    private $host = null;
    private $banco = null;
    private $link = null;
    private $consulta = null;
    private $dados = null;
    
    private function __construct() {
        $this->usuario = $GLOBALS["usuario"];
        $this->senha = $GLOBALS["senha"];
        $this->host = $GLOBALS["host"];
        $this->banco = $GLOBALS["banco"];
        $this->consulta = "";
        $this->dados = array();
    }

    public function conectar() {
        if ($this->link){
            debug(3, "Conexão com o banco de dados já ativa.");
            return;
        }
        debug(3, "Conectando com o banco de dados.");
        $this->link = mysqli_connect($this->host, $this->usuario, $this->senha,$this->banco);
        if (mysqli_connect_errno($this->link))
        {
          debug(1, "Falhar na conexão com o banco de dados. ERRO: " . mysqli_connect_error());
          die("Falhar na conexão com o banco de dados.");
        }
    }

    public function desconectar() {
        debug(3, "Desconectando do banco de dados.");
        return mysqli_close($this->link);
    }

    public function executar($consulta) {
        $this->consulta = $consulta;
        
        debug(3, "Efetuando consulta: ".$this->consulta);
        
        $resultado = mysqli_query($this->link,$this->consulta);

        ConexaoSingleton::$lastQuery = $resultado;

        if ($resultado) {
            debug(3, "Consulta executada com sucesso.");
           return $resultado;
        } else {
            debug(1, "Falhar ao executar comando SQL. SQL: ".$this->consulta." ERRO: (" . $this->link->errno . ") " . $this->link->error);
            return null;
        }
    }
    
    public function getNumResultados() {
        return ConexaoSingleton::$lastQuery->num_rows;
    }

    public function getLinhasAfetadas() {
        return ConexaoSingleton::$link->affected_rows;
    }
    
    public function getLastId() {
        debug(3, "Recupenrando ultimo id.");
        $tmp = mysqli_insert_id($this->link);
        debug(3, "tmp: ".$tmp.".");
        if ($tmp > 0) ConexaoSingleton::$lastId = $tmp;
        debug(3, "Ultimo Id: ".ConexaoSingleton::$lastId);
        return ConexaoSingleton::$lastId;
    }
    
    public function __toString() {
        return get_class();
    }

    public static function getConexao() {
        if(!ConexaoSingleton::$instancia){
            debug(3, "Criando nova instancia para conexão.");
            ConexaoSingleton::$instancia = new ConexaoSingleton();
            ConexaoSingleton::$instancia->conectar();
        }
        return ConexaoSingleton::$instancia;
    }
    
    public function startTransaction(){
        debug(3, "Iniciando transação.");
        $this->link->autocommit(FALSE);
    }

    public function commit(){
        debug(3, "Realizando commit.");
        $this->link->commit();
    }
    
    public function rollback(){
        debug(3, "Realizando rollback.");
        $this->link->rollback();
    }
    public function get_array($result){
        $array = array();
        $cont = 0;
        while($row = mysqli_fetch_array($result)){
            $array[$cont] = $row;
            $cont++;
        }
        return $array;
    }

    public static function makeValuesReferenced($arr){
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    
    public function executarStmt($consulta,$dados) {
        $this->consulta = $consulta;
        $this->dados = $dados;

        if (!($statement = $this->link->prepare($this->consulta))) {
            debug(1, "Falha ao preparar consulta. SQL: ".$this->consulta." ERRO: (" . $this->link->errno . ") " . $this->link->error);
            die("Falha ao preparar consulta. SQL: ".$this->consulta);
        }
        
        $ref    = new ReflectionClass('mysqli_stmt'); 
        $method = $ref->getMethod("bind_param"); 
        if (!($method->invokeArgs($statement,$this->dados))) {
            debug(1, "Falha ao inserir dados. SQL: ".$this->consulta." ERRO: (" . $this->link->errno . ") " . $this->link->error);
            die("Falha ao preparar ao inserir dados. SQL: ".$this->consulta);
        }

        if (!($statement->execute())) {
            debug(1, "Falha ao executar consulta. SQL: ".$this->consulta." ERRO: (" . $this->link->errno . ") " . $this->link->error);
            die("Falha ao executar consulta. SQL: ".$this->consulta);
        }

        $resultado = $statement->get_result();
        //$resultado = iimysqli_stmt_get_result($statement);

        ConexaoSingleton::$lastQuery = $resultado;

        if ($resultado) {
           return $resultado;
        } else {
            return null;
        }
    }
}

class iimysqli_result
{
    public $stmt, $nCols;
}    

function iimysqli_stmt_get_result($stmt)
{
    /**    EXPLANATION:
     * We are creating a fake "result" structure to enable us to have
     * source-level equivalent syntax to a query executed via
     * mysqli_query().
     *
     *    $stmt = mysqli_prepare($conn, "");
     *    mysqli_bind_param($stmt, "types", ...);
     *
     *    $param1 = 0;
     *    $param2 = 'foo';
     *    $param3 = 'bar';
     *    mysqli_execute($stmt);
     *    $result _mysqli_stmt_get_result($stmt);
     *        [ $arr = _mysqli_result_fetch_array($result);
     *            || $assoc = _mysqli_result_fetch_assoc($result); ]
     *    mysqli_stmt_close($stmt);
     *    mysqli_close($conn);
     *
     * At the source level, there is no difference between this and mysqlnd.
     **/
    $metadata = mysqli_stmt_result_metadata($stmt);
    $ret = new iimysqli_result;
    if (!$ret) return NULL;

    $ret->nCols = mysqli_num_fields($metadata);
    $ret->stmt = $stmt;

    mysqli_free_result($metadata);
    return $ret;
}

function iimysqli_result_fetch_array(&$result)
{
    $ret = array();
    $code = "return mysqli_stmt_bind_result(\$result->stmt ";

    for ($i=0; $i<$result->nCols; $i++)
    {
        $ret[$i] = NULL;
        $code .= ", \$ret['" .$i ."']";
    };

    $code .= ");";
    if (!eval($code)) { return NULL; };

    // This should advance the "$stmt" cursor.
    if (!mysqli_stmt_fetch($result->stmt)) { return NULL; };

    // Return the array we built.
    return $ret;
}
?>
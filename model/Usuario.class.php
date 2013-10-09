<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class Usuario extends DaoPadrao
{

    private $nome = null;
    private $usuario = null;
    private $senha = null;
    private $tipo = null;
    
    public function __construct() {
        
    }
    
    public function __toString()
    {
        return get_class()."[usuario: $this->usuario]";
    }

    function __get($propriedade)
    {
        return $this->$propriedade;
    }

    function __set($propriedade, $valor)
    {
        $this->$propriedade = $valor;
    }

    function getIdColumnName()
    {
        return "usuario";
    }

    function getTableName()
    {
        return "usuario";
    }
    
    function getIdValue()
    {
        return $this->usuario;
    }

    function getSelectList()
    {
        return "usuario,senha,tipo,nome";
    }

    function getInsertList()
    {
        return "'$this->usuario','$this->senha','$this->tipo','$this->nome'";
    }

    function getUpdateList()
    {
        return "usuario='$this->usuario',senha='$this->senha',tipo='$this->tipo',nome='$this->nome'";
    }

    function isAutoIncrement()
    {
        return false;
    }

    function load($id)
    {
        $ret = parent::load($id);
        if ($ret){
            $r = mysqli_fetch_array($ret);
            $this->usuario = $r[0];
            $this->senha = $r[1];
            $this->tipo = $r[2];
            $this->nome = $r[3];
        }
        return $ret;
    }
    
    function add() {
        try{
            $ret = parent::add();
            return $ret;
        }catch(Exception $e){
            echo($e->getMessage());
            return 0;
        }
    }

    function remove()
    {
        $ret = parent::remove();
        return $ret;
    }

    function update()
    {
        return parent::update();
    }
    
}
?>
<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class Adicional extends DaoPadrao
{

    private $id = null;
    private $descricao = null;
    
    public function __construct() {
        
    }
    
    public function __toString()
    {
        return get_class()."[adicional: $this->id]";
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
        return "id";
    }

    function getTableName()
    {
        return "adicional";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,descricao";
    }

    function getInsertList()
    {
        return "'$this->id','$this->descricao'";
    }

    function getUpdateList()
    {
        return "id='$this->id',descricao='$this->descricao'";
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
            $this->id = $r[0];
            $this->descricao = $r[1];
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
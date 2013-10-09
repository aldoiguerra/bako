<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class Categoria extends DaoPadrao
{

    private $id = null;
    private $descricao = null;
    private $categoriaProdutoPaiId = null;
    
    public function __construct() {
        
    }
    
    public function __toString()
    {
        return get_class()."[categoria: $this->id]";
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
        return "categoriaProduto";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,descricao,categoriaProdutoPaiId";
    }

    function getInsertList()
    {
        if($this->categoriaProdutoPaiId == ""){
            return "'$this->id','$this->descricao',NULL";
        }else{
            return "'$this->id','$this->descricao','$this->categoriaProdutoPaiId'";
        }
    }

    function getUpdateList()
    {
        if($this->categoriaProdutoPaiId == ""){
            return "id='$this->id',descricao='$this->descricao',categoriaProdutoPaiId=NULL";
        }else{
            return "id='$this->id',descricao='$this->descricao',categoriaProdutoPaiId='$this->categoriaProdutoPaiId'";
        }
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
            $this->categoriaProdutoPaiId = $r[2];
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
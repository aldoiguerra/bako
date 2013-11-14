<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class Categoria extends DaoPadrao
{

    private $id = null;
    private $descricao = null;
    private $categoriaPaiId = null;
    private $status = null;
    private $adicional = null;
    
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
        return "categoria";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,descricao,categoriaPaiId,status";
    }

    function getInsertList()
    {
        if($this->categoriaPaiId == ""){
            return "'$this->id','$this->descricao',NULL,'$this->status'";
        }else{
            return "'$this->id','$this->descricao','$this->categoriaPaiId','$this->status'";
        }
    }

    function getUpdateList()
    {
        if($this->categoriaPaiId == ""){
            return "id='$this->id',descricao='$this->descricao',categoriaPaiId=NULL,status='$this->status'";
        }else{
            return "id='$this->id',descricao='$this->descricao',categoriaPaiId='$this->categoriaPaiId',status='$this->status'";
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
            $this->categoriaPaiId = $r[2];
            $this->status = $r[3];
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
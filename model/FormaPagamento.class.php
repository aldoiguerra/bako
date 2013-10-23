<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class FormaPagamento extends DaoPadrao
{

    private $id = null;
    private $descricao = null;
    private $pedeObservacao = null;
    
    public function __construct() {
        
    }
    
    public function __toString()
    {
        return get_class()."[descricao: $this->descricao]";
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
        return "formaPagamento";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,descricao,pedeObservacao";
    }

    function getInsertList()
    {
        return "'$this->id','$this->descricao','$this->pedeObservacao'";
    }

    function getUpdateList()
    {
        return "descricao='$this->descricao',pedeObservacao='$this->pedeObservacao'";
    }

    function isAutoIncrement()
    {
        return true;
    }

    function load($id)
    {
        $ret = parent::load($id);
        if ($ret){
            $r = mysqli_fetch_array($ret);
            $this->id = $r[0];
            $this->descricao = $r[1];
            $this->pedeObservacao = $r[2];
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
<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class PerfilImpressao extends DaoPadrao
{

    private $id = null;
    private $descricao = null;
    private $layout = null;
    private $tipoTexto = null;
    private $tipoLayout = null;
    
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
        return "perfilImpressao";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,descricao,layout,tipoTexto,tipoLayout";
    }

    function getInsertList()
    {
        return "'$this->id','$this->descricao','$this->layout','$this->tipoTexto','$this->tipoLayout'";
    }

    function getUpdateList()
    {
        return "id='$this->id',descricao='$this->descricao',layout='$this->layout',tipoTexto='$this->tipoTexto',tipoLayout='$this->tipoLayout'";
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
            $this->layout = $r[2];
            $this->tipoTexto = $r[3];
            $this->tipoLayout = $r[4];
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
<?php
require_once ('../dao/DaoPadrao.class.php');

class Pedido extends DaoPadrao
{

    private $id = null;
    private $quantidade = null;
    private $produtoId = null;
    private $contaId = null;
    private $observacao = null;
    private $valorUnitario = null;
    private $usuarioId = null;
    private $dataHora = null;

    public function __construct() {
    }
    
    public function __toString()
    {
        return get_class()."[pedido: $this->id - ".$this->getUpdateList()."]";
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
        return "pedido";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,quantidade,produtoId,contaId,observacao,dataHora,usuarioId,valorUnitario";
    }

    function getInsertList()
    {
        return "NULL,'$this->quantidade','".$this->produtoId."','".$this->contaId."','$this->observacao','$this->dataHora','".$this->usuarioId."','$this->valorUnitario'";
    }

    function getUpdateList()
    {
        return "quantidade='$this->quantidade',produtoId='".$this->produtoId."',contaId='".$this->contaId."',observacao='$this->observacao',dataHora='$this->dataHora',usuarioId='".$this->usuarioId."',valorUnitario='$this->valorUnitario'";
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
            $this->quantidade = $r[1];
            $this->produtoId = $r[2];
            $this->contaId = $r[3];
            $this->observacao = $r[4];
            $this->dataHora = $r[5];
            $this->usuarioId = $r[6];
            $this->valorUnitario = $r[7];
        }
        return $ret;
    }
    
    function add()
    {
        $ret = parent::add();
        return $ret;
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
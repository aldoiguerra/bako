<?php
require_once ('../dao/DaoPadrao.class.php');

class Conta extends DaoPadrao
{
    private $id = null;
    private $dataHoraAbertura = null;
    private $dataHoraFechamento = null;
    private $numMesa = null;
    private $qtdPessoas = null;
    private $descricao = null;
    private $taxaServico = null;
    private $status = null;
    private $desconto = null;
    
    public function __construct() {        
    }
    
    public function __toString()
    {
        return get_class()."[conta: $this->id]";
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
        return "conta";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,dataHoraAbertura,dataHoraFechamento,numMesa,qtdPessoas,descricao,taxaServico,status,desconto";
    }

    function getInsertList()
    {
        return "'$this->id','$this->dataHoraAbertura','$this->dataHoraFechamento','$this->numMesa','$this->qtdPessoas','$this->descricao','$this->taxaServico','$this->status','$this->desconto'";
    }

    function getUpdateList()
    {
        return "dataHoraAbertura='$this->dataHoraAbertura',dataHoraFechamento='$this->dataHoraFechamento',numMesa='$this->numMesa',qtdPessoas='$this->qtdPessoas',descricao='$this->descricao',taxaServico='$this->taxaServico',status='$this->status',desconto='$this->desconto'";
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
            $this->dataHoraAbertura = $r[1];
            $this->dataHoraFechamento = $r[2];
            $this->numMesa = $r[3];
            $this->qtdPessoas = $r[4];
            $this->descricao = $r[5];
            $this->taxaServico = $r[6];
            $this->status = $r[7];
            $this->desconto = $r[8];
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
<?php
require_once ('../dao/DaoPadrao.class.php');

/**
 * @author Aluno
 */
class Produto extends DaoPadrao
{

    private $codigo = null;
    private $nome = null;
    private $descricao = null;
    private $categoriaId = null;
    private $preco = null;
    private $status = null;
    
    public function __construct() {
        
    }
    
    public function __toString()
    {
        return get_class()."[nome: $this->nome]";
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
        return "produto";
    }
    
    function getIdValue()
    {
        return $this->id;
    }

    function getSelectList()
    {
        return "id,nome,descricao,categoriaId,preco,status";
    }

    function getInsertList()
    {
        if ($this->categoriaId == ""){
            return "'$this->id','$this->nome','$this->descricao',NULL,'$this->preco','$this->status'";
        }else{
            return "'$this->id','$this->nome','$this->descricao','$this->categoriaId','$this->preco','$this->status'";
        }
    }

    function getUpdateList()
    {
        if ($this->categoriaId == ""){
            return "nome='$this->nome',descricao='$this->descricao',categoriaId=NULL, preco='$this->preco',status='$this->status'";
        }else{
            return "nome='$this->nome',descricao='$this->descricao',categoriaId='$this->categoriaId',preco='$this->preco',status='$this->status'";
        }
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
            $this->nome = $r[1];
            $this->descricao = $r[2];
            $this->categoriaId = $r[3];
            $this->preco = $r[4];
            $this->status = $r[5];
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
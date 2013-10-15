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
        return "id,nome,descricao,categoriaId,preco";
    }

    function getInsertList()
    {
        return "'$this->id','$this->nome','$this->descricao','$this->categoriaId','$this->preco'";
    }

    function getUpdateList()
    {
        return "nome='$this->nome',descricao='$this->descricao',categoriaId='$this->categoriaId',preco='$this->preco'";
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
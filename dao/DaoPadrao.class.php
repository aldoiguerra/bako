<?php
require_once ('iDao.class.php');
require_once ('ConexaoSingleton.class.php');

/**
 * @author Aluno
 * @version 1.0
 * @created 28-mai-2011 12:24:14
 */
abstract class DaoPadrao implements iDao
{
    
    function load($id)
    {
        $sql = 
            "SELECT ".$this->getSelectList().
            " FROM ".$this->getTableName().
            " WHERE ".$this->getIdColumnName()." = '".$id."'";
        return ConexaoSingleton::getConexao()->executar($sql);
    }

    function add()
    {
        $sql = 
            "INSERT INTO ".$this->getTableName().
            " (".$this->getSelectList().") ".
            "VALUES".
            " (".$this->getInsertList().")";
        $ret = ConexaoSingleton::getConexao()->executar($sql);
        if ($ret){
            $this->load(ConexaoSingleton::getConexao()->getLastId());
        }
        return $ret;
    }

    function remove()
    {
        $sql = 
            "DELETE FROM ".$this->getTableName().
            " WHERE ".$this->getIdColumnName()." = '".$this->getIdValue()."'";
        return ConexaoSingleton::getConexao()->executar($sql);
    }

    function update()
    {
        $sql = 
            "UPDATE ".$this->getTableName().
            " SET ".$this->getUpdateList().
            " WHERE ".$this->getIdColumnName()." = '".$this->getIdValue()."'";
        return ConexaoSingleton::getConexao()->executar($sql);
    }

}
?>
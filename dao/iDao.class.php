<?php

/**
 * @author aldoig
 * @version 1.0
 * @created 28-mai-2011 12:23:58
 */
interface iDao
{

    function __get($propriedade);

    function __set($propriedade, $valor);



    function getIdColumnName();

    function getTableName();
    
    function getIdValue();

    function getSelectList();

    function getInsertList();

    function getUpdateList();

    function isAutoIncrement();



    function load($id);

    function add();

    function update();

    function remove();

}
?>
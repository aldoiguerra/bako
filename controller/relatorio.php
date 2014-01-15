<?php
require_once ('../controller/sessao.php');

function popularSelect($dtInicial,$dtFinal){
    debug(3, "Inicio da consulta do relatorio. ");
    $connect = ConexaoSingleton::getConexao();
    $sql = "SELECT produto.descricao as 'produto', valorUnitario as 'valorunitario', sum(quantidade) as 'qtde', 
            (sum(quantidade) * valorUnitario) as 'valortotal', conta.numMesa as 'mesa'
            FROM pedido
                INNER JOIN produto ON (pedido.produtoId = produto.Id)
                INNER JOIN conta ON (pedido.contaId = conta.Id)
            WHERE CAST( dataHora AS DATE )
            BETWEEN  (".$dtInicial.")
            AND  (".$dtFinal.")
            GROUP BY numMesa,produto";
    
    $result = $connect->executar($sql);
    debug(3, "Numero de dados retornados no relatorio: ".$connect->getNumResultados());
    if($connect->getNumResultados() > 0){
        $arraydados = $connect->get_array($result);
        $array = array(
            "retorno"=>true,
            "dados"=>$arraydados
        );        
    }else{
        $array = array(
            "retorno"=>false
        );
    }
    debug(3, "Retorno relatorio: ".$array["retorno"]);
    return $array;
}

/*if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar categoria: ".$_POST["consultar"]);
    $objC = new Categoria();
    $ret = $objC->load($_POST["consultar"]);
    debug(3, "Categoria consultado: ".$objC->__get("id"));
    if($objC->__get("id")){
        $sql = "SELECT adicionalId,(SELECT descricao FROM adicional a WHERE a.id = adicionalId) As descricao FROM categoriaAdicional WHERE categoriaId = '".$objC->__get("id")."'";
        $connect = ConexaoSingleton::getConexao();
        $result = $connect->executar($sql);
        $arraydados = $connect->get_array($result);
        
        $array = array(
            "retorno"=>true,
            "id"=>$objC->__get("id"),
            "descricao"=>$objC->__get("descricao"),
            "categoriaPai"=>$objC->__get("categoriaPaiId"),
            "status"=>$objC->__get("status"),
            "adicionais"=>$arraydados
            );
    }else{
        $array = array(
            "retorno"=>false);
    }
    echo json_encode($array);
}else */if(isset($_POST["popularSelect"])){
    debug(3, "Recebido pedido para select do relatorio.");
    echo json_encode(popularSelect($_POST["dtInicial"],$_POST["dtFinal"]));
}

?>

<?php
require_once ('../controller/sessao.php');
require_once ('../model/ParametrosSistema.class.php');

function retornarArray(){
    $string = "";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar("SELECT p.id, p.qtdMesas, p.valorTxServico FROM parametroSistema p");
    return $connect->get_array($result);
}

function salvar($id,$qtdMesas,$valorTxServico){
    
    try {
        ConexaoSingleton::getConexao()->startTransaction();
        
        debug(3, "Buscando se existe parametros do sistema cadastrado.");
        $obj = new ParametroSistema();
        $ret = $obj->load($id);
        $idAntigo = $obj->__get("id");
        debug(3, "Parametro do sistema localizado: ".$idAntigo);
        
        $obj->__set("id",$id);
        $obj->__set("qtdMesas",$qtdMesas);
        $obj->__set("valorTxServico",$valorTxServico);
        if($idAntigo){
            $ret = $obj->update();
        }else{
            $ret = $obj->add();            
        }
        if(!$ret) throw new Exception ("");
        
        ConexaoSingleton::getConexao()->commit();
        
        debug(3, "parametros do sistema salvo com sucesso");
    }catch(Exception $e){
        debug(1, "Erro ao salvar parametros do sistema: ".$e->getMessage());
        
        ConexaoSingleton::getConexao()->rollback();
    }
    return $ret;
}

if(isset($_POST["salvar"])){
    debug(3, "Recebido pedido para salvar parametros do sistema: ".$_POST["id"]);
    $qtdMesas = $_POST["qtdMesas"];
    $sql = "SELECT Count(*) As Total FROM mesa";
    $connect = ConexaoSingleton::getConexao();
    $result = $connect->executar($sql);
    $rs = $connect->get_array($result);
    $total = $rs[0]["Total"];
    debug(3, "Total de mesas cadastradas, parametros do sistema: ".$total);
    debug(3, "Quantidade de mesas enviadas, parametros do sistema: ".$qtdMesas);
    $msg="Erro, não foi possivel salvar os parametros do sistema!";
    if ($total > $qtdMesas){
        $sql = "SELECT numMesa FROM conta WHERE status <> 3";
        $connect = ConexaoSingleton::getConexao();
        $result = $connect->executar($sql);
        $rs = $connect->get_array($result);
        if (count($rs)>0){
            $ret = 0;
            $msg = "Não é possivel alterar a quantidade de mesas, com alguma(s) aberta(s)!";
            debug(3, "Mesas abertas, não foi possivel alterar, parametros do sistema: ");
        }else{
            $sql = "DELETE FROM mesa";
            $connect = ConexaoSingleton::getConexao();
            $result = $connect->executar($sql);
            for ($i=1;$i <= $qtdMesas;$i++){
                $sql = "INSERT INTO mesa (numMesa) VALUES ('".$i."')";
                $connect = ConexaoSingleton::getConexao();
                $result = $connect->executar($sql);
            }
            $ret = salvar($_POST["id"],$_POST["qtdMesas"],$_POST["valorTxServico"]);
            debug(3, "Parametros do sistema 1 ".$ret);
        }
    }elseif ($total < $qtdMesas){
        debug(3, "Parametros do sistema 2");
        for ($i=1;$i <= ($qtdMesas - $total);$i++){
            $sql = "INSERT INTO mesa (numMesa) VALUES ('".($total+$i)."')";
            $connect = ConexaoSingleton::getConexao();
            $result = $connect->executar($sql);
        }
        $ret = salvar($_POST["id"],$_POST["qtdMesas"],$_POST["valorTxServico"]);
    }else{
        $ret = salvar($_POST["id"],$_POST["qtdMesas"],$_POST["valorTxServico"]);
    }
    debug(3, "Situacao ao salvar parametros do sistema: ".$ret);
    if($ret){
        echo json_encode(array(
            "retorno"=>true,
            "msg"=>"Parametros do sistema salvos com sucesso!"));
        debug(3, "Parametros do sistema, Fim: ".$ret);
    }else{
        echo json_encode(array(
            "retorno"=>false,
            "msg"=>$msg));
    }
}else if(isset($_POST["consultar"])){
    debug(3, "Recebido pedido para consultar parametros do sistema: ".$_POST["consultar"]);
    $obj = new ParametroSistema();
    $ret = $obj->load($_POST["consultar"]);
    if($obj->__get("id")){
        $array = array(
            "retorno"=>true,
            "id"=>$obj->__get("id"),
            "qtdMesas"=>$obj->__get("qtdMesas"),
            "valorTxServico"=>$obj->__get("valorTxServico"),
            );
    }else{
        $array = array("retorno"=>false);
    }
    echo json_encode($array);
}
?>

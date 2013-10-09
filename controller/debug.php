<?php

function debug($nivel,$conteudo){

    $nivelDefinido = 3;
    $arquivo = "debug.txt";
    
    if($nivel <= $nivelDefinido){

        $manipula = fopen($arquivo, "a+");

        if($nivel == 1){
            $writestring = "\n".date('l j \d\e F Y h:i:s A')." - ERROR: " . $conteudo;
        }else if($nivel == 2){
            $writestring = "\n".date('l j \d\e F Y h:i:s A')." - ALERT: " . $conteudo;
        }else if($nivel == 3){
            $writestring = "\n".date('l j \d\e F Y h:i:s A')." - DEBUG: " . $conteudo;
        }
        
        fwrite($manipula, $writestring);

        fclose($manipula);   
    }
}

?>

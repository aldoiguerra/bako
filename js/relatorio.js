
function gerarRelatorio(){
    var dataInicial = $("#dtInicial").val();
    var dataFinal = $("#dtFinal").val();
    /*alert(dtInicial+'-- '+dtFinal);
    var patternData = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;  
    if(!patternData.test(dtInicial)){  
        alert("Digite a data inicial no formato Dia/Mês/Ano");
        return false;  
    } 
    if(!patternData.test(dtFinal)){  
        alert("Digite a data final no formato Dia/Mês/Ano");
        return false;  
    } 
    var dataInicial = dtInicial.substring(0,2)+'-'+dtInicial.substring(2,2)+'-'+dtInicial.substring(5,4);
    var dataFinal = dtFinal.substring(0,2)+'-'+dtFinal.substring(2,2)+'-'+dtFinal.substring(5,4);
    alert(dataInicial+'/'+dataFinal);*/
    var variaveis = {"popularSelect": 1,
                     "dtInicial": dataInicial,
                     "dtFinal": dataFinal};
    $.post(urlRelatorio, variaveis,
            function(data) {
                if(data.retorno){
                    alert(data.dados);
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){
                alert('textStatus');
                $("#retorno").html("ERRO ao editar dados: ".textStatus);});
}
$(document).ready(function(){

    $("#btnGerar").click(function() {
        $("#retorno").html("");
        //alert('teste');
        gerarRelatorio();
    });

});
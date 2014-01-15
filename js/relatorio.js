
function gerarRelatorio(){
    var dtInicial = $("#dtInicial").val();
    var dtFinal = $("#dtFinal").val();
    
    var patternData = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;  
    if(!patternData.test(dtInicial)){  
        alert("Digite a data inicial no formato Dia/Mês/Ano");
        return false;  
    } 
    if(!patternData.test(dtFinal)){  
        alert("Digite a data final no formato Dia/Mês/Ano");
        return false;  
    }
    var dataInicial = dtInicial.substring(6,10)+'-'+dtInicial.substring(3,5)+'-'+dtInicial.substring(0,2);
    var dataFinal = dtFinal.substring(6,10)+'-'+dtFinal.substring(3,5)+'-'+dtFinal.substring(0,2);
    
    var variaveis = {"popularSelect": 1,
                     "dtInicial": dataInicial,
                     "dtFinal": dataFinal};
    $.post(urlRelatorio, variaveis,
            function(data) {
                if(data.retorno){
                    var html = '<ul>';
                    html = html+'<li><div>Produto</div><div>Valor Unitário</div><div>Quantidade</div><div>Valor Total</div><div>N. Mesa</div></li>';
                    for(var i=0;i<data.dados.length;i++){
                        html = html+'<li>';
                        html = html+'<div>'+data.dados[i][0]+'</div><div>'+data.dados[i][1]+'</div><div>'+data.dados[i][2]+'</div><div>'+data.dados[i][3]+'</div><div>'+data.dados[i][4]+'</div>';
                        html = html+'</li>';
                    }
                    html = html+'</ul>';
                    $("#tblRelatorio").html(html);
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
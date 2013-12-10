
function gerarRelatorio(){
    var variaveis = {"popularSelect": 1,
                     "dtInicial": $("#dtInicial").val(),
                     "dtFinal": $("#dtFinal").val()};
    $.post(urlRelatorio, variaveis,
            function(data) {
                if(data.retorno){
                    alert(data.dados);
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
}
$(document).ready(function(){

    $("#btnGerar").click(function() {
        $("#retorno").html("");
        gerarRelatorio();
    });

});
function editar(){
    var variaveis = {"consultar": "1"};
    $.post(urlParametroSistema, variaveis,
        function(data) {
            if(data.retorno){
                $("#qtdMesas").val(data.qtdMesas);
                $("#valorTxServico").val(data.valorTxServico);
                $("#btnEditar").show();
                $("#btnSalvar").hide();
            }             
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
}

$(document).ready(function(){
    editar();

    $("#btnEditar").click(function() {
        $("#retorno").html("");
        $("#btnEditar").hide();
        $("#btnSalvar").show();
    });

    $("#btnSalvar").click(function(){
        if ($("#qtdMesas").val() == ""){
            $("#retorno").html("Obrigatório preencher quantidade de mesas.");
            $("#qtdMesas").focus();
            return;
        }else{
            var regra = /^[0-9]+$/;
            var valor = $("#qtdMesas").val()
            if (!valor.match(regra)) {
                $("#retorno").html("Permitido somente número inteiro positivo!");
                $("#qtdMesas").focus();
                return;
            }
        }
        var variaveis = {"salvar": "1",
                        "id": 1,
                        "qtdMesas": $("#qtdMesas").val(),
                        "valorTxServico": $("#valorTxServico").val()
                        };
        $.post(urlParametroSistema, variaveis,
            function(data) {
                $("#retorno").html(data.msg);
                if(data.retorno){
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    $("#btnEditar").show();
    $("#btnSalvar").hide();
});

});
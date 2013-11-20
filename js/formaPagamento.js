
function editar(){
    limparCampos();
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlFormaPagamento, variaveis,
            function(data) {
                if(data.retorno){
                    $("#codigo").val(data.id);
                    $("#descricao").val(data.descricao);
                    if (data.pedeObservacao==1){
                        document.getElementById('pObs').checked=true
                    }else{
                        document.getElementById('pObs').checked=false
                    }
                    $("#btnNovo").hide();
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();
                    $("#btnExcluir").hide();
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}
function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlFormaPagamento, variaveis,
        function(data) {
            if(data.retorno){
                var colunas = data.colunas;
                var dados = data.dados;
                var lista = "";
                var tamanhoDados = dados.length;
                for(var i=0;i<tamanhoDados;i++){
                    lista = lista + '<li>';
                    lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
                    lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
				lista = lista + '<span class="indicator">&nbsp;</span>';
				lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
				lista = lista + '<h3>'+dados[i]["descricao"]+'</h3>';
                    lista = lista + '</label>';
                    lista = lista + '</li>';
                }
                document.getElementById("lista").innerHTML = lista;
                editar();
            }else{
                document.getElementById("lista").innerHTML = "";
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao consultar dados: ".textStatus);});                
}

function limparCampos(){
    $("#codigo").val("");
    $("#descricao").val("");
    $("#btnExcluir").hide();
    document.getElementById('pObs').checked=true
}

$(document).ready(function(){
    
    listarDados();
    
    $("#btnLimpar").click(function() {
        limparCampos();
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
    });
    
    $("#btnNovo").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
    });
    
    $("#btnEditar").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").show();
    });
    
    $("#btnExcluir").click(function() {
        if(confirm("Confirma a exclusão da forma de pagamento "+$("#codigo").val()+"'")){
            var variaveis = {"excluir": $("#codigo").val()};
            $.post(urlFormaPagamento, variaveis,
                function(data) {
                    $("#retorno").html(data.msg);
                    if(data.retorno){
                        listarDados();
                        limparCampos();
                        $("#btnNovo").show();
                        $("#btnEditar").hide();
                        $("#btnSalvar").hide();
                        $("#btnExcluir").hide();
                    }
                }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao excluir forma de pagamento: "+textStatus);});
        }
    });
    
    $("#btnSalvar").click(function(){
        if ($("#descricao").val() == ""){
            $("#retorno").html("Obrigatório preencher descricao.");
            $("#descricao").focus();
            return
        }
        var pedeObs = 0;
        if ($("#pObs").prop('checked')){
            pedeObs = 1;
        }
        var variaveis = {"salvar": "1",
                        "id": $("#codigo").val(),
                        "descricao": $("#descricao").val(),
                        "pedeObservacao": pedeObs,
                        };
        $.post(urlFormaPagamento, variaveis,
            function(data) {
                $("#retorno").html(data.msg);
                if(data.retorno){
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    $("#btnNovo").show();
    $("#btnEditar").hide();
    $("#btnSalvar").hide();
    $("#btnExcluir").hide();
    listarDados();
    limparCampos();
});

});

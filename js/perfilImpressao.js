
function editar(){
    limparCampos();
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlPerfil, variaveis,
            function(data) {
                if(data.retorno){
                    $("#codigo").val(data.id);
                    $("#descricao").val(data.descricao);
                    $("input:radio[name=tipoLayout]").val([data.tipoLayout]);
                    $("input:radio[name=tipoTexto]").val([data.tipoTexto]);
                    $("#layout").val(data.layout);
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").show();
                    $("#btnExcluir").show();
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}
function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlPerfil, variaveis,
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
				//lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
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
    if(document.querySelector('#lista input:checked')){
        document.querySelector('#lista input:checked').checked = false;
    }
    $("#codigo").val("");
    $("#descricao").val("");
    //$("input:radio[name=tipoLayout]:checked").val();
    $( "input:radio[name=tipoLayout]").val([""]);
    //$("input:radio[name=tipoTexto]:checked").val();
    $( "input:radio[name=tipoTexto]").val([""]);
    $("#layout").val("");
    $("#btnExcluir").hide();
}

$(document).ready(function(){
    
    listarDados();
    
    $("#btnLimpar").click(function() {
        limparCampos();
        $("#retorno").html("");
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
    });
    
    $("#btnNovo").click(function() {
        //limparCampos();
        $("#retorno").html("");
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
        if(confirm("Confirma a exclusão do layout "+$("#codigo").val()+"'")){
            var variaveis = {"excluir": $("#codigo").val()};
            $.post(urlPerfil, variaveis,
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
        if (!$("input:radio[name=tipoTexto]:checked").val()){
            $("#retorno").html("Obrigatório preencher Tipo Texto");
            $("#descricao").focus();
            return
        }
        if (!$("input:radio[name=tipoLayout]:checked").val()){
            $("#retorno").html("Obrigatório preencher Tipo Layout.");
            $("#descricao").focus();
            return
        }
        var variaveis = {"salvar": "1",
                        "id": $("#codigo").val(),
                        "descricao": $("#descricao").val(),
                        "layout": $("#layout").val(),
                        "tipoTexto": $("input:radio[name=tipoTexto]:checked").val(),
                        "tipoLayout": $("input:radio[name=tipoLayout]:checked").val()
                        };
        $.post(urlPerfil, variaveis,
            function(data) {
                console.log(data)
                $("#retorno").html(data.msg);
                if(data.retorno){
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                    $("#btnExcluir").hide();
                    listarDados();
                    limparCampos();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
});

});

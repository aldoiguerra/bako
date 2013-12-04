
function editar(){
    limparCampos();
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlAdicional, variaveis,
            function(data) {
                if(data.retorno){
                    $("#id").val(data.id);
                    $("#descricao").val(data.descricao);
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").show();
                    $("#btnExcluir").show();
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}
function pesquisar(texto){
    texto = texto.toUpperCase();
    var tamanhoDados = dados.length;
    if(texto=="") {
        var lista = "";
        for(var i=0;i<tamanhoDados;i++){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />';
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                lista = lista + '<span class="indicator">&nbsp;</span>';
                lista = lista + '<h3>'+dados[i]["descricao"]+'</h3>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
        editar();
        return;
    }
    var lista = "";
    document.getElementById("lista").innerHTML = lista;
    for(var i=0;i<tamanhoDados;i++){
        var string = dados[i]["descricao"];
        string = string.toUpperCase();
        if(string.indexOf(texto)>=0){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />';
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                lista = lista + '<span class="indicator">&nbsp;</span>';
                lista = lista + '<h3>'+dados[i]["descricao"]+'</h3>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
    }
    editar();
}

function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlAdicional, variaveis,
        function(data) {
            if(data.retorno){
                colunas = data.colunas;
                dados = data.dados;
                var lista = "";
                var tamanhoDados = dados.length; 
                for(var i=0;i<tamanhoDados;i++){
                    lista = lista + '<li>';
                    lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />';
                    lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
				lista = lista + '<span class="indicator">&nbsp;</span>';
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
    $("#id").val("");
    $("#descricao").val("");
    $("#btnExcluir").hide();
}

$(document).ready(function(){
    
    listarDados();

    $("#pesquisar").keyup(function() {
        pesquisar($("#pesquisar").val());
    });
    
    $("#btnLimpar").click(function() {
        limparCampos();
        $("#retorno").html("");
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
        $("#btnExcluir").hide();
    });
    $("#btnNovo").click(function() {
        limparCampos();
        $("#retorno").html("");
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").show();
    });
    
    $("#btnEditar").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").show();
    });
    
    $("#btnExcluir").click(function() {
        if(confirm("Confirma a exclusão de adicional de categoria "+$("#id").val()+"'")){
            var variaveis = {"excluir": $("#id").val()};
            $.post(urlAdicional, variaveis,
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
                }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao excluir dados: "+textStatus);});
        }
        popularSelect();
    });
    
    $("#btnSalvar").click(function(){
        if ($("#descricao").val()==""){
            $("#retorno").html("Obrigatório preencher a descrição.");
            $("#descricao").focus();
            return
        }
        var status = "";
        //Executa Loop entre todas as Radio buttons com o name de valor
        $('input:radio[name=rAI]').each(function() {
            //Verifica qual está selecionado
            if ($(this).is(':checked'))
                status = parseInt($(this).val());
        })
        var variaveis = {"salvar": "1",
                        "id": $("#id").val(),
                        "descricao": $("#descricao").val(),
                        };
        $.post(urlAdicional, variaveis,
            function(data) {
                $("#retorno").html(data.msg);
                if(data.retorno){
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                    $("#btnExcluir").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
        listarDados();
        limparCampos();
        popularSelect();
    });

});

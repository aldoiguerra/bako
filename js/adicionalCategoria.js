
function editar(){
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlAdicional, variaveis,
            function(data) {
                if(data.retorno){
                    $("#id").val(data.id);
                    $("#descricao").val(data.descricao);
                    /*if (data.status == 1){
                        $("#ckInativo").attr("checked",false);
                        $("#ckAtivo").attr("checked",true);
                    }else{
                        $("#ckAtivo").attr("checked",false);
                        $("#ckInativo").attr("checked",true);
                    }*/
                    $("#btnNovo").show();
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();                    
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
                lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
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
                lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
                lista = lista + '<h3>'+dados[i]["descricao"]+'</h3>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
        editar();
        //popularSelect();
    }
}
/*
function popularSelect(){
    var variaveis = {"popularSelect": 1};
    $.post(urlAdicional, variaveis,
        function(data) {
            if(data.retorno){
                var dados = data.dados;
                var select = '<option value=""></option>';
                for(var dado in dados){
                    var select = select + '<option value="'+dado+'">'+dados[dado]+'</option>'; 
                }
                document.getElementById("slCategoria").innerHTML = select;
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao consultar dados: ".textStatus);});                
}
*/
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
    $("#id").val("");
    $("#descricao").val(""); 
}

$(document).ready(function(){
    
    listarDados();
    //popularSelect();

    $("#pesquisar").keyup(function() {
        pesquisar($("#pesquisar").val());
    });
    
    $("#btnLimpar").click(function() {
        limparCampos();
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
        $("#btnExcluir").hide();
    });
    $("#btnNovo").click(function() {
        //limparCampos();
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").hide();
    });
    
    $("#btnEditar").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").show();
    });
    
    $("#btnExcluir").click(function() {
        if(confirm("Confirma a exclusão da categoria "+$("#id").val()+"'")){
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
                }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao excluir categoria: "+textStatus);});
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
                    listarDados();
                    limparCampos();
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                    $("#btnExcluir").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
        popularSelect();
    });

});

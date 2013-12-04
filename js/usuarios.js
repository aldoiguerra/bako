
function editar(){
    //limparCampos();
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlUsuario, variaveis,
            function(data) {
                if(data.retorno){
                    $("#usuario").val(data.usuario);
                    $("#nome").val(data.nome);
                    $("#senha").val("");
                    $("#tipo").val(data.tipo);
                    if (data.status == 1){
                        document.getElementById("ckAtivo").checked=true;
                    }else{
                        document.getElementById("ckInativo").checked=true;
                    }
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").show();     
                    $("#btnExcluir").show();
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar usuário: ".textStatus);});
    });
}
function pesquisar(texto){
    texto = texto.toUpperCase()
    var tamanhoDados = dados.length;
    if(texto=="") {
        var lista = "";
        for(var i=0;i<tamanhoDados;i++){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                lista = lista + '<span class="indicator">&nbsp;</span>';
                lista = lista + '<h4>'+dados[i]["usuario"]+'<h3>';
                lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
                if (dados[i]["tipo"]=='1'){
                    lista = lista + '<p>Administrador</p>';
                }else if (dados[i]["tipo"]=='2'){
                    lista = lista + '<p>Garçom</p>';
                }
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
        editar();
        return;
    }
    var lista = ""
    document.getElementById("lista").innerHTML = lista;
    for(var i=0;i<tamanhoDados;i++){
        var string = dados[i]["nome"];
        string = string.toUpperCase()
        if(string.indexOf(texto)>=0){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                lista = lista + '<span class="indicator">&nbsp;</span>';
                lista = lista + '<h4>'+dados[i]["usuario"]+'<h3>';
                lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
                if (dados[i]["tipo"]=='1'){
                    lista = lista + '<p>Administrador</p>';
                }else if (dados[i]["tipo"]=='2'){
                    lista = lista + '<p>Garçom</p>';
                }
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
    }
    editar();
}

function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlUsuario, variaveis,
        function(data) {
            if(data.retorno){
                colunas = data.colunas;
                dados = data.dados;
                var lista = "";
                var tamanhoDados = dados.length; 
                for(var i=0;i<tamanhoDados;i++){
                    lista = lista + '<li>';
                    lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
                    lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
				lista = lista + '<span class="indicator">&nbsp;</span>';
				lista = lista + '<h4>'+dados[i]["usuario"]+'<h3>';
				lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
				if (dados[i]["tipo"]=='1'){
                                    lista = lista + '<p>Administrador</p>';
                                }else if (dados[i]["tipo"]=='2'){
                                    lista = lista + '<p>Garçom</p>';
                                }
                    lista = lista + '</label>';
                    lista = lista + '</li>';
                }
                document.getElementById("lista").innerHTML = lista;
                editar();
            }else{
                document.getElementById("lista").innerHTML = "";
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao consultar usuário: ".textStatus);});                
}

function limparCampos(){
    if(document.querySelector('#lista input:checked')){
        document.querySelector('#lista input:checked').checked = false;
    }
    $("#usuario").val("");
    $("#nome").val("");
    $("#senha").val("");
    $("#tipo").val("0");
    $("#btnExcluir").hide();
    document.getElementById("ckAtivo").checked=true;
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
        $("#btnExcluir").hide();
    });
    
    $("#btnEditar").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        $("#btnExcluir").show();
    });
    
    $("#btnExcluir").click(function() {
        if(confirm("Confirma a exclusão do usuário "+$("#usuario").val()+"'")){
            var variaveis = {"excluir": $("#usuario").val()};
            $.post(urlUsuario, variaveis,
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
                }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao excluir usuário: "+textStatus);});
        }
    });
    
    $("#btnSalvar").click(function(){
        if ($("#usuario").val() == ""){
            $("#retorno").html("Obrigatório preencher o usuário.");
            $("#usuario").focus();
            return
        }else if ($("#nome").val() == ""){
            $("#retorno").html("Obrigatório preencher nome do usuário.");
            $("#nome").focus();
            return
        }else if ($("#senha").val() == ""){
            $("#retorno").html("Obrigatório preencher senha.");
            $("#senha").focus();
            return
        }else if ($("#tipo").val() < 1){
            $("#retorno").html("Obrigatório selecionar tipo do usuário.");
            $("#tipo").focus();
            return
        }
        $('input:radio[name=rAI]').each(function() {
            //Verifica qual está selecionado
            if ($(this).is(':checked'))
                status = parseInt($(this).val());
        })
        var variaveis = {"salvar": "1",
                        "usuario": $("#usuario").val(),
                        "nome": $("#nome").val(),
                        "senha": $("#senha").val(),
                        "tipo": $("#tipo").val(),
                        "status": status
                        };
        $.post(urlUsuario, variaveis,
            function(data) {
                $("#retorno").html(data.msg);
                if(data.retorno){
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                    $("#btnExcluir").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar usuário: ".textStatus);});
        limparCampos();
        listarDados();
    });

});


function editar(){
    $("input[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).val()};
        $.post(urlProduto, variaveis,
            function(data) {
                if(data.retorno){
                    $("#codigo").val(data.codigo);
                    $("#nome").val(data.nome);
                    $("#descricao").val(data.descricao);
                    $("#slCategoria").val(data.categoriaId);
                    $("#preco").val(data.preco);
                    if (data.status == 1){
                        //$("#ckAtivo").attr("checked",true);
                        //$("#ckInativo").attr("checked",false);
                        document.getElementById("ckAtivo").setAttribute("checked",true);
                        document.getElementById("ckInativo").removeAttribute("checked");
                    }else{
                        document.getElementById("ckInativo").setAttribute("checked",true);
                        document.getElementById("ckAtivo").removeAttribute("checked");
                        //$("#ckAtivo").attr("checked",false);
                        //$("#ckInativo").attr("checked",true);
                    }
                    $("#btnNovo").show();
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}

function popularSelect(){
    var variaveis = {"popularSelect": 1};
    $.post(urlProduto, variaveis,
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
function pesquisar(texto){
    texto = texto.toUpperCase();
    if(texto=="") {
        var lista = "";
        var tamanhoDados = dados.length;            
        for(var i=0;i<tamanhoDados;i++){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                lista = lista + '<span class="indicator">&nbsp;</span>';
                lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
                lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
                lista = lista + '<p>'+dados[i]["categoria"]+'</p>';
                lista = lista + '<p>R$ '+dados[i]["preco"]+'</p>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
        editar();
        return;
    }
    var tamanhoDados = dados.length;
    var lista = "";
    document.getElementById("lista").innerHTML = lista;
    for(var i=0;i<tamanhoDados;i++){
        var string = dados[i]["nome"];
        string = string.toUpperCase();
        if(string.indexOf(texto)>=0){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                        lista = lista + '<span class="indicator">&nbsp;</span>';
                        lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
                        lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
                        lista = lista + '<p>'+dados[i]["categoria"]+'</p>';
                        lista = lista + '<p>R$ '+dados[i]["preco"]+'</p>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }else if(dados[i]["id"].indexOf(texto)>=0){
            lista = lista + '<li>';
            lista = lista + '<input type="radio" name="editar" id="ra'+dados[i][colunas[0]]+'" value="'+dados[i][colunas[0]]+'" onchange="if(this.checked) {document.getElementById(\'section\').classList.add(\'section-show\')};" />'
            lista = lista + '<label for="ra'+dados[i][colunas[0]]+'">';
                        lista = lista + '<span class="indicator">&nbsp;</span>';
                        lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
                        lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
                        lista = lista + '<p>'+dados[i]["categoria"]+'</p>';
                        lista = lista + '<p>R$ '+dados[i]["preco"]+'</p>';
            lista = lista + '</label>';
            lista = lista + '</li>';
        }
        document.getElementById("lista").innerHTML = lista;
        editar();
        popularSelect();
    }
}
function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlProduto, variaveis,
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
				lista = lista + '<h4>'+dados[i]["id"]+'<h3>';
				lista = lista + '<h3>'+dados[i]["nome"]+'</h3>';
				lista = lista + '<p>'+dados[i]["categoria"]+'</p>';
                                lista = lista + '<p>R$ '+dados[i]["preco"]+'</p>';
                    lista = lista + '</label>';
                    lista = lista + '</li>';
                }
                document.getElementById("lista").innerHTML = lista;
                editar();
                popularSelect();
            }else{
                document.getElementById("lista").innerHTML = "";
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao consultar dados: ".textStatus);});                
}

function limparCampos(){
    $("#codigo").val("");
    $("#nome").val("");
    $("#descricao").val("");
    $("#slCategoria").val("");
    $("#preco").val("");
    //$("#retorno").attr("hidden",true);
    
}

$(document).ready(function(){
    
    listarDados();
    
    $("#btnLimpar").click(function() {
        limparCampos();
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
        //$("#btnExcluir").hide();
    });
    
    $("#btnNovo").click(function() {
        limparCampos();
        popularSelect();
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        //$("#btnExcluir").hide();
    });
    
    $("#pesquisar").keyup(function() {
        pesquisar($("#pesquisar").val());
    });
    
    $("#btnEditar").click(function() {
        $("#btnNovo").hide();
        $("#btnEditar").hide();
        $("#btnSalvar").show();
        //$("#btnExcluir").show();
    });
    
    /*$("#btnExcluir").click(function() {
        if(confirm("Confirma a exclusão do produto "+$("#codigo").val()+"'")){
            var variaveis = {"excluir": $("#codigo").val()};
            $.post(urlProduto, variaveis,
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
    });*/
    
    $("#btnSalvar").click(function(){
        if ($("#nome").val() == ""){
            $("#retorno").html("Obrigatório preencher nome do produto.");
            $("#nome").focus();
            return
        }else if ($("#preco").val() == ""){
            $("#retorno").html("Obrigatório preencher preço.");
            $("#preco").focus();
            return
        }else if ($("#slCategoria").val() == ""){
            $("#retorno").html("Obrigatório selecionar a categoria.");
            $("#slCategoria").focus();
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
                        "codigo": $("#codigo").val(),
                        "nome": $("#nome").val(),
                        "descricao": $("#descricao").val(),
                        "categoriaId": $("#slCategoria").val(),
                        "preco": $("#preco").val(),
                        "status": status
                        };
        $.post(urlProduto, variaveis,
            function(data) {
                $("#retorno").html(data.msg);
                if(data.retorno){
                    listarDados();
                    limparCampos();
                    $("#btnNovo").show();
                    $("#btnEditar").hide();
                    $("#btnSalvar").hide();
                    //$("#btnExcluir").hide();
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    $("#btnNovo").show();
    $("#btnEditar").hide();
    $("#btnSalvar").hide();
    //$("#btnExcluir").hide();
    listarDados();
    limparCampos();
});

});

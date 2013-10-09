
function editar(){
    $("a[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).attr("id")};
        $.post(urlCategoria, variaveis,
            function(data) {
                if(data.retorno){
                    $("#id").val(data.id);
                    $("#descricao").val(data.descricao);
                    $("#slCategoria").val(data.categoriaPai);
                    $("#btnNovo").show();
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();                    
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}

function popularSelect(){
    var variaveis = {"popularSelect": 1};
    $.post(urlCategoria, variaveis,
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

function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlCategoria, variaveis,
        function(data) {
            if(data.retorno){
                var colunas = data.colunas;
                var dados = data.dados;
                var tabela = '<table cellspacing="0" cellpadding="0">';
                tabela = tabela + '<tr>';
                tabela = tabela + '<th>Código</th>';
                tabela = tabela + '<th>Descrição</th>';
                tabela = tabela + '<th>Categoria pai</th>';
                tabela = tabela + '</tr>';
                tabela = tabela + '<tbody id="corpoTabela">';
                var tamanhoDados = dados.length; 
                for(var i=0;i<tamanhoDados;i++){
                    tabela = tabela + '<tr>';
                    var tamanhoLinha = colunas.length;
                    for(var j=0;j<tamanhoLinha;j++){
                        tabela = tabela + '<td>'+dados[i][colunas[j]]+'</td>';
                    }
                    tabela = tabela + '<td><a name="editar" href="javascript:#;" id="'+dados[i][colunas[0]]+'">Editar</a></td>';
                    tabela = tabela + '</tr>';
                }
                tabela = tabela + '</tbody>';
                tabela = tabela + '</table>';
                document.getElementById("lista").innerHTML = tabela;
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
    popularSelect();

    $("#btnLimpar").click(function() {
        limparCampos();
        $("#btnNovo").show();
        $("#btnEditar").hide();
        $("#btnSalvar").hide();
        $("#btnExcluir").hide();
    });
    
    $("#btnNovo").click(function() {
        limparCampos();
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
            $.post(urlCategoria, variaveis,
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
    });
    
    $("#btnSalvar").click(function(){
        var variaveis = {"salvar": "1",
                        "id": $("#id").val(),
                        "descricao": $("#descricao").val(),
                        "categoriaPai": $("#slCategoria").val()
                        };
        $.post(urlCategoria, variaveis,
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
    });

});


function editar(){
    $("a[name='editar']").click(function(){
        var variaveis = {"consultar": $(this).attr("id")};
        $.post(urlUsuario, variaveis,
            function(data) {
                if(data.retorno){
                    $("#usuario").val(data.usuario);
                    $("#nome").val(data.nome);
                    $("#senha").val("");
                    $("#tipo").val(data.tipo);
                    $("#btnNovo").show();
                    $("#btnEditar").show();
                    $("#btnSalvar").hide();                    
                }             
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    });
}

function listarDados(){
    var variaveis = {"listar": 1};
    $.post(urlUsuario, variaveis,
        function(data) {
            if(data.retorno){
                var colunas = data.colunas;
                var dados = data.dados;
                var tabela = '<table cellspacing="0" cellpadding="0">';
                tabela = tabela + '<tr>';
                tabela = tabela + '<th>Usuário</th>';
                tabela = tabela + '<th>Nome</th>';
                tabela = tabela + '<th>Acesso</th>';
                tabela = tabela + '<th>Editar</th>';
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
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao consultar dados: ".textStatus);});                
}

function limparCampos(){
    $("#usuario").val("");
    $("#nome").val("");
    $("#senha").val("");
    $("#tipo").val("");    
}

$(document).ready(function(){
    
    listarDados();

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
        var variaveis = {"salvar": "1",
                        "usuario": $("#usuario").val(),
                        "nome": $("#nome").val(),
                        "senha": $("#senha").val(),
                        "tipo": $("#tipo").val()
                        };
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
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    });

});

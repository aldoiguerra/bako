
function limparCampos(){
    $("#idConta").val("");
    $("#mesa").val("");
    $("#qtdPessoas").val("");
    $("#dataHora").html("");
    $("#dataHoraFechamento").html("");
    $("#tabelaPedidos").html("");
}

function buscarContas(){
    var variaveis = {"buscarContas": 1};
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                arrayContas = data.contas;
                arrayProdutos = data.produtos;
                arrayFormaPagamento = data.formapagamentos;
                desenharContas();
                desenharSelectFP();
                desenharSelectProdutos();
            }
        },
        "json"
    ).fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
}

function buscarProdutos(){
    var variaveis = {"buscarProdutos": 1};
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                arrayProdutos = data.produtos;
            }
        },
        "json"
    ).fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
}

function desenharContas(){

    var conteudo = "";
    if(arrayContas){
        var tamanho = arrayContas.length;
        for(var i=0;i<tamanho;i++){
            var classe = 'class=""';
            var pessoas = arrayContas[i]["qtdPessoas"]+' pessoas';
            var subTotal = numeroLogicalToDisplay(arrayContas[i]["totalAtual"]);
            var dataHora = dataHoraLogicalToDisplay(arrayContas[i]["dataHoraAbertura"],1);
            var classeMesa = "number";
            var idConta = arrayContas[i]["id"];
            var numMesa = arrayContas[i]["numMesa"]+arrayContas[i]["descricao"];
            if((!arrayContas[i]["status"]) || (arrayContas[i]["status"] == 3)){
                classe = 'class="inactive"'
                pessoas = '&nbsp;';
                subTotal = 'Livre';
                dataHora = '';
                idConta = '';
            }else if(arrayContas[i]["status"] == 2){
                classeMesa = "number-close";
            }
            conteudo = conteudo +'<li>';
            conteudo = conteudo +'<input type="radio" name="radioAside" id="ra'+numMesa+'" value="'+idConta+'" mesa="'+arrayContas[i]["numMesa"]+'" />';
            conteudo = conteudo +'<label for="ra'+numMesa+'" '+classe+'>';
            conteudo = conteudo +'<span class="'+classeMesa+'">'+numMesa+'</span>';
            //conteudo = conteudo +'<h4>'+pessoas+'<h3>';
            //conteudo = conteudo +'<h2>'+subTotal+'</h2>';
            //conteudo = conteudo +'<p>'+dataHora+'</p>';
            conteudo = conteudo +'</label>';
            conteudo = conteudo +'</li>';
        }
    }
    
    document.getElementById("listaConta").innerHTML = conteudo;
    inserirEditar();
}

function inserirEditar(){
    $("input[name='radioAside']").click(function(){consultarConta($(this).val(),$(this).attr("mesa"));});
}

function consultarConta(pIdConta,pNumMesa){
    limparCampos();
    if (pIdConta != ""){
        var variaveis = {"consultar": pIdConta};
        $.post(urlConta, variaveis,
            function(data) {
                if(data.retorno){
                    //fecharPopup("popupPagamento");
                    alterarTela(data);
                }
            }, 
            "json"
        ).fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    }else{
        //$("#btnFecharConta").hide();
        $("#mesa").val(pNumMesa);
        $("#status").html(statusContaLogicalToDisplay(3));
        alterarBotoesStatus(3);
    }
}

function alterarBotoesStatus(pStatus){
    console.log("alterando botões para o status: "+pStatus);
    if(pStatus == 1){
        alterarBotoes(false,true,true,true,true,true,true,true);
    }else if(pStatus == 2){
        alterarBotoes(false,true,true,false,false,false,true,true);
    }else{
        alterarBotoes(true,false,false,false,false,false,false,false);
    }
}

function alterarBotoes(btnAbrirMesa,btnSalvar,btnCancelar,btnNovoPedido,btnFechar,btnTrocar,btnRealizarPagamento,btnDesconto){
    console.log("sequencia botoes: "+btnAbrirMesa+" - "+btnSalvar+" - "+btnCancelar+" - "+btnNovoPedido+" - "+btnFechar+" - "+btnTrocar+" - "+btnRealizarPagamento+" - "+btnDesconto);
    if(btnAbrirMesa != null){
        if(btnAbrirMesa){
            $("#btnAbrirMesa").show();
        }else{
            $("#btnAbrirMesa").hide();
        }
    }
    if(btnSalvar != null){
        if(btnSalvar){
            $("#btnSalvar").show();
        }else{
            $("#btnSalvar").hide();
        }
    }
    if(btnCancelar != null){
        if(btnCancelar){
            $("#btnExcluir").show();
        }else{
            $("#btnExcluir").hide();
        }
    }
    if(btnNovoPedido != null){
        if(btnNovoPedido){
            $("#btnNovoPedido").show();
        }else{
            $("#btnNovoPedido").hide();
        }
    }    
    if(btnTrocar != null){
        if(btnTrocar){
            $("#btnTrocarMesa").show();
        }else{
            $("#btnTrocarMesa").hide();
        }
    }    
    if(btnRealizarPagamento != null){
        if(btnRealizarPagamento){
            $("#btnRealizarPagamento").show();
        }else{
            $("#btnRealizarPagamento").hide();
        }
    }    
    if(btnDesconto != null){
        if(btnDesconto){
            $("#btnDesconto").show();
        }else{
            $("#btnDesconto").hide();
        }
    }    
}

function consultarMesa(pMesa){
    if (pMesa != ""){
        var numMesa = pMesa;
        var numConta = "";
        if (arrayContas){
            var tamanho = arrayContas.length;
            for(var i=0;i<tamanho;i++){
                if (arrayContas[i]["numMesa"] == numMesa){
                    if(arrayContas[i]["status"] != 3){
                        numConta = arrayContas[i]["id"];
                    }
                }
            }
        }
        consultarConta(numConta,numMesa);
    }
}

function desenharPedidos(pPedidos,pPagamentos,pStatus,pDesconto,pTaxaServico){
    
    var conteudo = "<table>";
    
    var taxa = 0;
    var tamanho = pPedidos.length;
    var total = 0;
    var taxaServico = 0;
    var subTotal = 0;
    for(var i=0;i<tamanho;i++){
        conteudo = conteudo + '<tr>';
        //conteudo = conteudo + '<td>'+pPedidos[i]["id"]+'</td>';
        conteudo = conteudo + '<td>'+/*arrayProdutos[pPedidos[i]["produtoId"]]["id"]+' - '+*/arrayProdutos[pPedidos[i]["produtoId"]]["nome"]+'</td>';
        conteudo = conteudo + '<td>'+pPedidos[i]["quantidade"]+' x '+numeroLogicalToDisplay(pPedidos[i]["valorUnitario"])+'</td>';
        conteudo = conteudo + '<td><span class="color-success">'+numeroLogicalToDisplay(pPedidos[i]["valor"])+'</span></td>';
        //conteudo = conteudo + '<td><a name="editarPedido" class="icon-edit" value="'+pPedidos[i]["id"]+'">&nbsp;</a>';
        conteudo = conteudo + '<td><a name="excluirPedido" class="icon-cancel" value="'+pPedidos[i]["id"]+'" detalhes="'+pPedidos[i]["quantidade"]+' x '+arrayProdutos[pPedidos[i]["produtoId"]]["nome"]+'">&nbsp;</a></td>';
        conteudo = conteudo + '</tr>';
        
        if($("#taxaServico").prop("checked")){
            taxa = pPedidos[i]["valor"]*txServico;
            taxaServico = taxaServico+taxa;
        }
        subTotal = subTotal + parseFloat(pPedidos[i]["valor"]) + taxa;
        total = total + parseFloat(pPedidos[i]["valor"]) + taxa;
    }
        
    //if($("#taxaServico").prop("checked")){
        conteudo = conteudo + '</tr>'; 
        conteudo = conteudo + '<td>Taxa serviço</td>';
        conteudo = conteudo + '<td><input type="checkbox" id="taxaServico" /></td>';
        conteudo = conteudo + '<td><span class="color-success">'+numeroLogicalToDisplay(taxaServico.toFixed(2))+'</span></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '</tr>'; 
    //}

    conteudo = conteudo + '<tr class="tr-marker">';
    conteudo = conteudo + '<td>Sub Total</td>';
    conteudo = conteudo + '<td></td>';
    conteudo = conteudo + '<td><strong>'+numeroLogicalToDisplay(subTotal.toFixed(2))+'</strong></td>';
    conteudo = conteudo + '<td></td>';
    conteudo = conteudo + '</tr>'; 

    //if((pDesconto) &&(parseFloat(pDesconto) != 0)){
        conteudo = conteudo + '<tr>';
        conteudo = conteudo + '<td>Desconto</td>';
        conteudo = conteudo + '<td><input type="text" size="5" id="valorDesconto" /><input type="button" id="btnSalvarDesconto" class="icon-ok-1 bt-normal" /></td>';
        conteudo = conteudo + '<td><span class="color-negative">'+numeroLogicalToDisplay(pDesconto)+'</span></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '</tr>';     
        total = total - parseFloat(pDesconto);
    //}
    
    var tamanho = pPagamentos.length;
    for(var i=0;i<tamanho;i++){
        conteudo = conteudo + '<tr>';
        //conteudo = conteudo + '<td>'+pPagamentos[i]["id"]+'</td>';
        conteudo = conteudo + '<td>'+pPagamentos[i]["formaPagamento"]+'</td>';
        conteudo = conteudo + '<td>'+pPagamentos[i]["observacao"]+'</td>';
        conteudo = conteudo + '<td><span class="color-negative">'+numeroLogicalToDisplay(pPagamentos[i]["valor"])+'</span></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '</tr>';
        
        total = total - parseFloat(pPagamentos[i]["valor"]);
    }    
    
    conteudo = conteudo + '</table>';
    conteudo = conteudo + '<div id="divTotal">Total';
    conteudo = conteudo + '<strong>'+numeroLogicalToDisplay(total.toFixed(2))+'</strong>';
    conteudo = conteudo + '<input type="button" value="Liberar mesa" style="display: none;" id="btnExcluir" style="display: none;" class="bt-negative" />';
    conteudo = conteudo + '</div>';
    totalConta = total.toFixed(2);
    /*
    if (pStatus == 1){
        conteudo = conteudo + '<tr>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '<td><input type="text" size="5" id="qtdProduto" /></td>';
        conteudo = conteudo + '<td><input type="text" size="5" id="idProduto" /></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '<td></td>';
        conteudo = conteudo + '</tr>';    
    }*/
    
    document.getElementById("tabelaPedidos").innerHTML = conteudo;
    $("#btnExcluir").click(function(){liberarMesa();});
    //$("a[name='editarPedido']").click(function(){consultarPedido($(this).attr("value"));});
    $("a[name='excluirPedido']").click(function(){excluirPedido($(this).attr("value"),$(this).attr("detalhes"));});
    $("#btnSalvarDesconto").click(function(){aplicarDesconto();});
    $("#taxaServico").attr("checked",(pTaxaServico)?true:false);
    //$("#qtdProduto").keyup(function(event){if(event.keyCode == 13){inserirPedido();}});
    //$("#idProduto").keyup(function(event){if(event.keyCode == 13){inserirPedido();}});
}

function excluirPedido(pPedido,pDetalhes){

    if(!confirm("Confirma a exclusão do pedido: "+pDetalhes)){
        return;
    }

    var variaveis = {"excluirPedido": "1",
                "id": pPedido,
                "idConta": $("#idConta").val()
                };
    $.post(urlConta, variaveis,
    function(data) {
        if(data.retorno){
            alterarTela(data);
        }
    }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    
}

function inserirPedido(){
    var qtdProduto = $("#qtdProduto").val();
    var idProduto = $("#idProduto").val();
    
    if(qtdProduto == ""){
        exibirRetorno("Insira a quantidade de produto.");
        return;
    }
    if(idProduto == ""){
        exibirRetorno("Insira o código do produto.");
        return;
    }
    var variaveis = {"inserirPedido": "1",
                "id": "",
                "qtdProduto": qtdProduto,
                "idProduto": idProduto,
                "idConta": $("#idConta").val(),
                "dataHora": dataHoraDisplayToLogical("",1)
                };
    $.post(urlConta, variaveis,
    function(data) {
        if(data.retorno){
            alterarTela(data);
        }
    }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    
}

function desenharSelectProdutos(){
    var retorno = "";
    var produto = -1;
    retorno = retorno + '<option value="" ></option>';
    for(produto in arrayProdutos){
        retorno = retorno + '<option value="'+arrayProdutos[produto]["id"]+'" >'+arrayProdutos[produto]["nome"]+'</option>';
    }  
    $("#selectProduto").html(retorno);
    $("#selectProduto").select2({placeholder:'Produto'});
    $("#selectProduto").change(function(){
                                 $("#codigoProduto").val($("#selectProduto").val());  
                                 $("#valorProduto").val(numeroLogicalToDisplay(arrayProdutos[$("#selectProduto").val()]["preco"]));
                                 desenharSelectAdicionais($("#selectProduto").val());
                                 calcularPrecoPedido();
                              });
    $("#selectAdicionais").select2({placeholder:'Adicionais'});
}

function desenharSelectAdicionais(pProdutoSelecionado){
    var retorno = "";
    var produto = -1;
    var arrayAdicional = arrayProdutos[pProdutoSelecionado]["adicionais"].toString().split(",");
    for(var i=0;i<arrayAdicional.length;i++){
        var adicional = arrayAdicional[i].split(";");
        retorno = retorno + '<option value="'+adicional[0]+'" >'+adicional[1]+'</option>';
    }  
    $("#selectAdicionais").html(retorno);
    $("#selectAdicionais").select2({placeholder:'Adicionais'});
    $("#selectAdicionais").change(function(){$("#codigoProduto").val($("#selectProduto").val())});
}

function calcularPrecoPedido(){
    var quantidade = $("#quantidadePedido").val();
    var preco = numeroDisplayToLogical($("#valorProduto").val());
    var valor = 0;
    if((quantidade != "") && (preco != "")){
        valor = parseFloat(quantidade) * parseFloat(preco);
    }
    $("#totalPedido").val(numeroLogicalToDisplay(valor.toFixed(2)));
}

function limparPedido(){
    $("#codigoPedido").val("");
    $("#quantidadePedido").val("");
    $("#codigoProduto").val("");
    $("#selectProduto").select2("val", "");
    $("#valorProduto").val("");
    $("#totalPedido").val("");
    $("#selectAdicionais").select2("val", "");
    $("#observacaoPedido").val("");
}

function selecionarProduto(){
    console.log("codigoProduto: "+$("#codigoProduto").val());
    $("#selectProduto").select2("val", $("#codigoProduto").val());
    $("#valorProduto").val(numeroLogicalToDisplay(arrayProdutos[$("#selectProduto").val()]["preco"]));
    desenharSelectAdicionais($("#selectProduto").val());
    calcularPrecoPedido();  
}

function salvarPedido(){
    //Verifica se o numero da mesa digitado já não esta aberto
    if ($("#quantidadePedido").val() == ""){
        exibirRetorno("Insira a quantidade de produtos");
        return;
    }
    if ($("#codigoProduto").val() == ""){
        exibirRetorno("Insira o código do produto.");
        return;
    }
    
    var variaveis = {"salvarPedido": "1",
                    "id": $("#codigoPedido").val(),
                    "quantidadePedido": $("#quantidadePedido").val(),
                    "codigoProduto": $("#codigoProduto").val(),
                    "valorProduto": numeroDisplayToLogical($("#valorProduto").val()),
                    "adicionais": (($("#selectAdicionais").val() == null)?"":$("#selectAdicionais").val().join(",")),
                    "observacaoPedido": $("#observacaoPedido").val(),
                    "idConta": $("#idConta").val(),
                    "dataHora": dataHoraDisplayToLogical("",1)
                    };
    console.log(variaveis);
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                alterarTela(data);
                limparPedido();
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
}

function consultarPedido(pPedido){
    limparPedido();
    if (pPedido != ""){
        var variaveis = {"consultarPedido": pPedido};
        $.post(urlConta, variaveis,
            function(data) {
                console.log(data);
                if(data.retorno){
                    $("#codigoPedido").val(data.id);
                    $("#quantidadePedido").val(data.quantidade);
                    $("#codigoProduto").val(data.produtoId);
                    $("#selectProduto").select2("val", data.produtoId);
                    $("#valorProduto").val(data.valorUnitario);
                    var total = numeroLogicalToDisplay((parseFloat(data.quantidade)*parseFloat(data.valorUnitario)).toFixed(2));
                    $("#totalPedido").val(total);
                    if(data.adicionais){
                        $("#selectAdicionais").select2("val", data.adicionais.split(","));
                    }
                    $("#observacaoPedido").val(data.observacao);
                }
            }, 
            "json"
        ).fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao editar dados: ".textStatus);});
    }
}

function exibirRetorno(pMsg){
    $("#retorno").html(pMsg);
    setTimeout(function(){$("#retorno").html("");},5000);
}

function salvarConta(){
    //Verifica se o numero da mesa digitado já não esta aberto
    if ($("#mesa").val() == ""){
        exibirRetorno("Insira o numero da mesa.");
        return;
    }
    if ($("#qtdPessoas").val() == ""){
        exibirRetorno("Insira a quantidade de pessoas.");
        return;
    }
    var numMesa = $("#mesa").val();
    var numConta = "";
    var posicao = "";
    if (arrayContas){
        var tamanho = arrayContas.length;
        for(var i=0;i<tamanho;i++){
            if (arrayContas[i]["numMesa"] == numMesa){
                if ((arrayContas[i]["status"] == 1) || (arrayContas[i]["status"] == 2)){
                    numConta = arrayContas[i]["id"];
                    posicao = i;
                }
            }
        }
    }
    
    if ($("#idConta").val() != numConta){
        alert("A mesa "+numMesa+" já está aberta.");
        consultarConta(numConta);
    }else{
        var variaveis = {"salvar": "1",
                        "id": $("#idConta").val(),
                        "numMesa": $("#mesa").val(),
                        "qtdPessoas": $("#qtdPessoas").val(),
                        "descricao": $("#descricao").val(),
                        "taxaServico": ($("#taxaServico").prop("checked")?1:0),
                        "dataHora": dataHoraDisplayToLogical($("#dataHora").html(),1),
                        "status": arrayContas[posicao]["status"],
                        "dataHoraFechamento": dataHoraDisplayToLogical($("#dataHoraFechamento").html(),1)
                        };
        $.post(urlConta, variaveis,
            function(data) {
                if(data.retorno){
                    alterarTela(data);
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    }
}

function abrirMesa(){
    //Verifica se o numero da mesa digitado já não esta aberto
    if ($("#mesa").val() == ""){
        exibirRetorno("Insira o numero da mesa.");
        return;
    }
    if ($("#qtdPessoas").val() == ""){
        exibirRetorno("Insira a quantidade de pessoas.");
        return;
    }
    var numMesa = $("#mesa").val();
    var numConta = "";
    if (arrayContas){
        var tamanho = arrayContas.length;
        for(var i=0;i<tamanho;i++){
            if (arrayContas[i]["numMesa"] == numMesa){
                if ((arrayContas[i]["status"] == 1) || (arrayContas[i]["status"] == 2)){
                    numConta = arrayContas[i]["id"];
                }
            }
        }
    }
    
    if ($("#idConta").val() != numConta){
        alert("A mesa "+numMesa+" já está aberta.");
        consultarConta(numConta);
    }else{
        var variaveis = {"abrirMesa": "1",
                        "id": $("#idConta").val(),
                        "numMesa": $("#mesa").val(),
                        "qtdPessoas": $("#qtdPessoas").val(),
                        "descricao": $("#descricao").val(),
                        "taxaServico": ($("#taxaServico").prop("checked")?1:0),
                        "dataHora": dataHoraDisplayToLogical($("#dataHora").html(),1)
                        };
        $.post(urlConta, variaveis,
            function(data) {
                if(data.retorno){
                    alterarTela(data);
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    }
}

function statusContaLogicalToDisplay(valor){
    var retorno = "";
    if (valor == 1){
        retorno = "Aberta";
    }else if (valor == 2){
        retorno = "Fechada";
    }else if (valor == 3){
        retorno = "Livre";
    }
    return retorno;
}

function fecharConta(){
    //Verifica se o numero da mesa digitado já não esta aberto
    var variaveis = {"fecharConta": "1",
                    "id": $("#idConta").val(),
                    "numMesa": $("#mesa").val(),
                    "qtdPessoas": $("#qtdPessoas").val(),
                    "descricao": $("#descricao").val(),
                    "taxaServico": ($("#taxaServico").prop("checked")?1:0),
                    "dataHoraAbertura": dataHoraDisplayToLogical($("#dataHora").html(),1),
                    "dataHoraFechamento": dataHoraDisplayToLogical("",1)
                    };
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                alterarTela(data);
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
}

function liberarMesa(){
    //Verifica se o numero da mesa digitado já não esta aberto
    var variaveis = {"liberarMesa": "1",
                    "id": $("#idConta").val(),
                    "numMesa": $("#mesa").val(),
                    "qtdPessoas": $("#qtdPessoas").val(),
                    "descricao": $("#descricao").val(),
                    "taxaServico": ($("#taxaServico").prop("checked")?1:0),
                    "dataHoraAbertura": dataHoraDisplayToLogical($("#dataHora").html(),1),
                    "dataHoraFechamento": dataHoraDisplayToLogical($("#dataHoraFechamento").html(),1)
                    };
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                alterarTela(data);
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
}

function desenharSelectFP(){
    var retorno = "<option>Forma pagamento</option>";
    var tamanho = arrayFormaPagamento.length;
    for(var i=0;i<tamanho;i++){
        retorno = retorno + '<option value="'+arrayFormaPagamento[i]["id"]+'" >'+arrayFormaPagamento[i]["descricao"]+'</option>';
    }
    $("#selectFP").html(retorno);
}

function mostraPopup(pPopup){
    var windowHeight = $(window).height();
    var windowWidth = $(window).width();
    var popupWidth = $("#"+pPopup).width();
    var popupHeight = $("#"+pPopup).height();
    $("#"+pPopup).offset({ top: ((windowHeight-popupHeight)/2), left: ((windowWidth-popupWidth)/2) });
    $("#"+pPopup).show();
}

function fecharPopup(pPopup){
    $("#"+pPopup).hide();
}

function registrarPagamento(pPopup){
    $("#"+pPopup).show();
}

function salvarPagamento(){
    //Verifica se o numero da mesa digitado já não esta aberto
    var variaveis = {"salvarPagamento": "1",
                    "id": $("#idConta").val(),
                    "formaPagamento": $("#selectFP").val(),
                    "valor": numeroDisplayToLogical($("#valorPagamento").val()),
                    "observacao": $("#observacao").val(),
                    "dataHora": dataHoraDisplayToLogical("",1)
                    };
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                //fecharPopup("popupPagamento");
                alterarTela(data);
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
}

function aplicarDesconto(){
    //Verifica se o numero da mesa digitado já não esta aberto
    var valorTotal = parseFloat(totalConta);
    var valorDesconto = parseFloat(numeroDisplayToLogical($("#valorDesconto").val()));


    if(valorTotal < valorDesconto){
        exibirRetorno("Desconto maior que o total.");
        return;
    }
    if(valorDesconto <= 0){
        exibirRetorno("Insira um desconto válido.");
        return;        
    }
    
    var variaveis = {"aplicarDesconto": "1",
                    "id": $("#idConta").val(),
                    "desconto": valorDesconto
                    };
    $.post(urlConta, variaveis,
        function(data) {
            if(data.retorno){
                alterarTela(data);
            }
        }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
}

function alterarTela(pDados){
    console.log("Mostrando dados na tela.");
    console.log(pDados);
    $("#idConta").val(pDados.id);
    $("#mesa").val(pDados.numMesa);
    $("#qtdPessoas").val(pDados.qtdPessoas);
    $("#descricao").val(pDados.descricao);
    $("#status").html(statusContaLogicalToDisplay(pDados.status));
    $("#dataHora").html(dataHoraLogicalToDisplay(pDados.dataHoraAbertura,1));
    $("#dataHoraFechamento").html(dataHoraLogicalToDisplay(pDados.dataHoraFechamento,1));
    desenharPedidos(pDados.pedidos,pDados.pagamentos,pDados.status,pDados.desconto,pDados.taxaServico);
    if(pDados.contas){
        arrayContas = pDados.contas;
        desenharContas();
    }
    $("#ra"+pDados.numMesa+pDados.descricao).prop("checked",true);    
    alterarBotoesStatus(pDados.status);    
}

function trocarMesa(){
    //Verifica se o numero da mesa digitado já não esta aberto
    var novaMesa = $("#novaMesa").val();
    var numConta = "";
    if (arrayContas){
        var tamanho = arrayContas.length;
        for(var i=0;i<tamanho;i++){
            if (arrayContas[i]["numMesa"] == novaMesa){
                if(arrayContas[i]["status"] != 3){
                    numConta = arrayContas[i]["id"];
                }
            }
        }
    }

    if(numConta == ""){
        var variaveis = {"trocarMesa": "1",
                        "id": $("#idConta").val(),
                        "novaMesa": novaMesa
                        };
        $.post(urlConta, variaveis,
            function(data) {
                console.log(data);
                if(data.retorno){
                    fecharPopup("popupTrocarMesa");
                    alterarTela(data);
                }
            }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao salvar dados: ".textStatus);});
    }else{
        alert("A mesa '"+novaMesa+"' não está livre.");
    }
}
function buscarTaxaServico(){
    txServico = 0;
    var variaveis = {"buscaTaxaServico": "1"};
    $.post(urlConta, variaveis,
    function(data) {
        if(data.retorno){
            txServico = data.taxa/100;
        }
    }, "json").fail(function(jqXHR, textStatus, errorThrown){$("#retorno").html("ERRO ao buscar taxa de serviço. ".textStatus);});
}

$(document).ready(function(){

    arrayContas = null;
    arrayProdutos = null;
    arrayFormaPagamento = null;
    totalConta = 0;
    buscarContas();
    buscarTaxaServico();

    $("#btnAbrirMesa").click(function(){abrirMesa();});
    $("#btnSalvar").click(function(){salvarConta();});
    //$("#btnRealizarPagamento").click(function(){mostraPopup("popupPagamento");});
    $("#btnSalvarPagamento").click(function(){salvarPagamento();});
    $("#btnCancelarDesconto").click(function(){fecharPopup("popupDesconto");});
    $("#btnTrocarMesa").click(function(){mostraPopup("popupTrocarMesa");});
    $("#btnCancelarTroca").click(function(){fecharPopup("popupTrocarMesa");});
    $("#btnSalvarTroca").click(function(){trocarMesa();});
    $("#btnSalvarPedido").click(function(){salvarPedido();});
    $("#valorProduto").change(function(){calcularPrecoPedido();});
    $("#quantidadePedido").change(function(){calcularPrecoPedido();});
    $("#codigoProduto").change(function(){selecionarProduto();});
    $("#taxaServico").change(function (){
        inserirEditar();
        //alert('changed');
    });
    
    $("#mesa").keyup(function(event){
        if(event.keyCode == 13){
            consultarMesa($(this).val())
        }
    });
});





//montar URL de controller
function retornarURL(){
    var url = window.location;
    var urlString = url.toString();
    var urlArray = urlString.split("/");
    var url = "";
    for (var i = 0; i < (urlArray.length-2); i++) {
        url = url + urlArray[i] + "/";
    }
    return url;
}

urlControle = retornarURL()+"controller/contaMobile.php";

function usuarioLogado(){
    var parms = "verificarLogin=1";
    var retorno = requestSync(urlControle,parms,"text");
    var usuarioLogado = (retorno == "1")?true:false;
    return usuarioLogado;
}

function efetuarLogin(){
   var usuario = document.getElementById("usuarioLogin").value; 
   var senha = document.getElementById("senhaLogin").value; 
   if(usuario == ""){
       alert("Digite o usuário");
       return;
   }
   if(senha == ""){
       alert("Digite a senha");
       return;
   }
   var parms = "efetuarLogin=1&usuario="+usuario+"&senha="+senha;
   var retorno = requestSync(urlControle,parms,"text");
   var usuarioLogado = (retorno=="1")?true:false;
   if(usuarioLogado){
        acaoLogado();
   }else{
        showSection('login');
   }
}

function sairSistema(){
    var parms = "sairSistema=1";
    var retorno = requestSync(urlControle,parms,"text");
    retorno = (retorno == "1")?true:false;
    if(retorno){
        hideSection('inicio');
        showSection('login');
    }
}

function listarItensCardapio(pId,pThis) {
	
	pThis.parentNode.parentNode.style.display = 'none';
	var newUlCategoria = ""
	newUlCategoria = document.createElement('ul');
	document.getElementById('listaCategoria').parentNode.appendChild(newUlCategoria);
	
	for(var c in categoria) {
		if(categoria[c].categoriaPaiId == pId) {
			var liCat = document.createElement('li');
			newUlCategoria.appendChild(liCat);
			liCat.innerHTML = '<a href="javascript:;" onclick="listarItensCardapio('+categoria[c].id+',this)">'+categoria[c].descricao+'</a>';
		}
	}
	
	for(var p in produto) {
		if(categoria[produto[p].categoriaId].id == pId) {
			var liProd = document.createElement('li')
			liProd.innerHTML = '<label><span><h2>'+produto[p].nome+'</h2><small>'+produto[p].descricao+'</small><a href="javascript:;" onclick="adicionarProdutoCardapio('+p+');">+</a></label>'
			newUlCategoria.appendChild(liProd)
		}
	}
}

//buscarAssync('listarContas=1',listarMesas)
function listarMesas() {
    var parms = "buscarMesas=1";
    requestAssync(urlControle,parms,function(data){
                                    conta = data.contas;
                                    desenharMesas();
                                    });
}

function desenharMesas() {
		
	showAside('mesas');
	var ulMesas = document.getElementById('listaMesas') 
	ulMesas.innerHTML = ""
	for(var j in conta) {
		var backColor = ""
                var total = 'R$ '+conta[j].totalAtual
		if(conta[j].status == 1) {
			backColor = "green";
		} else if(conta[j].status == 2) {
			backColor = "red";
		} else {
			backColor = "gray"
                        total = "Livre"
		}
		var liMesa = document.createElement('li')
		ulMesas.appendChild(liMesa)
		liMesa.innerHTML =	'<a href="javascript:;" data-idconta="'+conta[j].id+'" data-qtdpessoas="'+conta[j].qtdPessoas+'" data-mesa="'+conta[j].numMesa+'" data-status="'+conta[j].status+'">' +
								'<h2 class="li-thumb" style="background-color:'+backColor+'">'+conta[j].numMesa+'</h2>'+
								'<h3>'+total+'</h3>' +
							'</a>'
		
		liMesa.children[0].addEventListener('click',function() {
			hideAside('mesas');
			document.getElementById('listaMesas') .innerHTML = "";
			
			document.getElementById('btnConta').dataset.idconta = this.dataset.idconta;
                        document.getElementById('btnConta').dataset.numMesa = this.dataset.mesa;
                        document.getElementById('btnNumeroMesa').innerText  = 'MESA ' + this.dataset.mesa;
                        
			var articlePedido = document.querySelector('#inicio article')
			articlePedido.innerHTML = '<h6>MESA</h6><h5 class="box flex content-center align-center">' + this.dataset.mesa + '</h5><div class="box content-center item-center"><div><button class="button-inverse icon-down-dir"></button></div><div class="box padding1"><input type="number" readonly="true" id="qtdPessoas" value="'+this.dataset.qtdpessoas+'" />Pessoas</div><div><button class="button-inverse icon-up-dir"></button></div></div>'
			
                        document.querySelector('#navInicio').innerHTML = "";
			if(this.dataset.status == "1") {
				var btn = document.createElement('button');
				btn.className = 'icon-doc-inv';
				btn.style.cssText = 'width:100%;font-size:18px;font-weight:400;'
				btn.innerText = 'Novo pedido';
				document.querySelector('#navInicio').appendChild(btn);
				btn.addEventListener('click',function() {showSection('pedido')},false)
			} else if(this.dataset.status == "2") {
                            //Se a conta está fechada não adiciona pedido
                        } else{
				var btn = document.createElement('button');
				btn.className = 'icon-doc-inv';
				btn.style.cssText = 'width:100%;font-size:18px;font-weight:400;'
				btn.innerText = 'Abrir mesa';
				document.querySelector('#navInicio').appendChild(btn);
				btn.addEventListener('click',function() {abrirConta();},false)
			}
                        
                        document.getElementById('btnConta').style.display = "";
		},false);
	}
}

function abrirConta() {
    
    var numMesa = document.getElementById('btnConta').dataset.numMesa;
    var qtdPessoas = document.getElementById('qtdPessoas').value;

    if((qtdPessoas == "") || (parseInt(qtdPessoas,10) <= 0)) {
        alert('Entre como número de pessoas');
        return false;
    }

    var parms = "abrirConta=1&numMesa="+numMesa+"&qtdPessoas="+qtdPessoas+"&dataHora="+dataHoraDisplayToLogical("",1);
    var retorno = requestSync(urlControle,parms);

    console.log(retorno);

    if(retorno.retorno){
        alert("Conta Aberta");
        document.querySelector('#navInicio').innerHTML = "";
        var btn = document.createElement('button');
        btn.className = 'icon-doc-inv';
        btn.style.cssText = 'width:100%;font-size:18px;font-weight:400;'
        btn.innerText = 'Novo pedido';
        document.querySelector('#navInicio').appendChild(btn);
        btn.addEventListener('click',function() {showSection('pedido')},false)
    }else{
        alert("ERRO: ");
    }

}

function adicionarProdutoPedido() {

    var codProduto = parseInt(document.getElementById('iptCodProduto').value,10);
    var qtdProduto = parseInt(document.getElementById('iptQtdProduto').value,10);

    if(produto[codProduto] == 'undefined') {
            alert('Produto não existe');
            return false;
    }

    console.log("produto: "+codProduto);
    console.log("quantidade: "+qtdProduto);

    var achouProduto = false;
    for(var c in novoPedido) {
        if(novoPedido[c].codProd == codProduto){
            novoPedido[c].quantProd = parseInt(novoPedido[c].quantProd,10) + qtdProduto;
            achouProduto = true;
        }
    }

    if(!achouProduto){
        novoPedido.push({
            codProd: codProduto,
            quantProd: qtdProduto,
            adicionais: ""
        });
    }

    document.getElementById('iptCodProduto').value = '';
    document.getElementById('iptQtdProduto').value = '';

    desenharListaPedidos();
}

function adicionarProdutoCardapio(pProduto) {

    if(produto[pProduto] == 'undefined') {
            alert('Produto não existe');
            return false;
    }

    var achouProduto = false;
    for(var c in novoPedido) {
        if(novoPedido[c].codProd == pProduto){
            novoPedido[c].quantProd = parseInt(novoPedido[c].quantProd,10) + 1;
            achouProduto = true;
        }
    }

    if(!achouProduto){
        novoPedido.push({
                codProd: pProduto,
                quantProd: 1,
                adicionais: ""
        });
    }
    
    desenharListaPedidos();
}

function removerProdutoCardapio(pProduto) {

    if(produto[pProduto] == 'undefined') {
            alert('Produto não existe');
            return false;
    }

    var achouProduto = false;
    var removerProduto = false;
    var posicao = -1;
    for(var c in novoPedido) {
        if(novoPedido[c].codProd == pProduto){
            var quantidade = parseInt(novoPedido[c].quantProd,10) - 1;
            if(quantidade == 0){
                removerProduto = true;
                posicao = c;
            }else{
                novoPedido[c].quantProd = quantidade;
            }
            achouProduto = true;
        }
    }

    if(!achouProduto){
        novoPedido.push({
                codProd: pProduto,
                quantProd: 1,
                adicionais: ""
        });
    }
    if(removerProduto){
        novoPedido.splice(posicao,1);
    }
    
    desenharListaPedidos();
}

function desenharListaPedidos(){

    var ulItensPedido = document.getElementById('ulItensPedido');
    ulItensPedido.innerHTML = "";
    
    console.log(novoPedido);

    for(var c in novoPedido) {
        var codProduto = novoPedido[c].codProd;
        var qtdProduto = novoPedido[c].quantProd;

        var liItem = document.createElement('li');
        liItem.id = "pedidoProduto" + codProduto;
        liItem.innerHTML =  '<span class="input-thumb" id="qtd'+codProduto+'"><p>'+qtdProduto+'</p></span><a href="javascript:;" onclick="adicionarProdutoCardapio('+codProduto+');">+</a>'+ //<a href="javascript:;" onclick="removerProdutoCardapio('+codProduto+');">-</a>
                                                '<a href="javascript:return false;" id="listLink'+codProduto+'" onclick="this.nextElementSibling.nextElementSibling.classList.toggle(\"show-action\")">'+
                                                        '<h2>'+ produto[codProduto].nome +'</h2>'+
                                                '</a>'+
                                                '<p class="count" id="valorPedido'+codProduto+'">'+(produto[codProduto].preco*qtdProduto)+'</p>'+
                                                '<div class="action" id="action'+codProduto+'">'+
                                                        '<button class="icon-quote-left" onclick="desenharAdicionais(\''+codProduto+'\');"></button>'+
                                                        '<button class="icon-trash" onclick="removerProdutoCardapio('+codProduto+')"></button>'+
                                                '</div>';
        ulItensPedido.appendChild(liItem)

        Hammer(document.getElementById('listLink'+codProduto)).on('tap',function() {document.getElementById('action'+codProduto).classList.add('show-action')});
        Hammer(document.getElementById('listLink'+codProduto)).on('dragright',function() {document.getElementById('action'+codProduto).classList.remove('show-action')});

        Hammer(document.getElementById('qtd'+codProduto)).on('dragright',function() {
                if(parseInt(this.innerText) > 1) {
                        this.children[0].style.cssText = '-webkit-transform: translate3d(100%,0,0);';
                        var pThis = this
                        var stime = setTimeout(function() {
                                pThis.innerHTML = '<p>'+(parseInt(pThis.innerText)-1) + '</p>';
                                document.getElementById('valorPedido'+codProduto).innerText = produto[codProduto].preco*(parseInt(pThis.innerText)-1)
                        },150);
                        for(var c in novoPedido) {
                                if(novoPedido[c].codProd == codProduto) {
                                        novoPedido[c].quantProd = parseInt(pThis.innerText)-1
                                }
                        }
                }
                desenharListaPedidos();
        });
        Hammer(document.getElementById('qtd'+codProduto)).on('dragleft',function() {
                this.children[0].style.cssText = '-webkit-transform:translate3d(-100%,0,0);';
                var pThis = this
                var stime = setTimeout(function() {
                        pThis.innerHTML = '<p>'+(parseInt(pThis.innerText) + 1) + '</p>';
                        document.getElementById('valorPedido'+codProduto).innerText = produto[codProduto].preco*(parseInt(pThis.innerText)+1)
                },150);
                for(var c in novoPedido) {
                        if(novoPedido[c].codProd == codProduto) {
                                novoPedido[c].quantProd = parseInt(pThis.innerText)+1
                        }
                }
                desenharListaPedidos();
        });
    }
}

function listarPedidos() {
	showAside('conta');
	var idConta = document.getElementById('btnConta').dataset.idconta;

        var parms = "consultar="+idConta;
        var objConta = requestSync(urlControle,parms);
        
        conta = objConta.contas;
	
	var ulPedidos = document.getElementById('listaPedidos');
	ulPedidos.innerHTML = ""
	for(var i=0; i<objConta.pedidos.length;i++) {
		var pedido = objConta.pedidos[i];
		var liPedido = document.createElement('li');
		liPedido.className = 'box'
		liPedido.innerHTML = '<p class="box flex item-center">'+pedido.quantidade+' - '+produto[pedido.produtoId].descricao+'</p>';
		ulPedidos.appendChild(liPedido)
	}
        var total = 0;
        for(var i=0; i<conta.length;i++) {
            if(conta[i].id == idConta){
                total = conta[i].totalAtual;
                break;
            }
        }
	document.getElementById('totalAtual').innerHTML = total;
        document.getElementById('mesaConta').innerHTML = 'MESA '+objConta.numMesa+' ('+objConta.qtdPessoas+' pessoas)';
}

function confirmarPedido() {
	//SalvarPedido(JSON.stringify(novoPedido))
        var idConta = document.getElementById('btnConta').dataset.idconta;
        var dados = {"pedidos":novoPedido,"dataHora":dataHoraDisplayToLogical("",1),"idConta":idConta};
        
        console.log(JSON.stringify(dados));
        
        var parms = "salvarPedido=1&dados="+JSON.stringify(dados);
        var retorno = requestSync(urlControle,parms);
        
        console.log(retorno);
	
        if(retorno.retorno == "1"){
            novoPedido = [];
            document.getElementById('ulItensPedido').innerHTML = "";
            hideSection('pedido');
        }else{
            alert("ERRO: ");
        }
}

function buscarDados() {
    var parms = "buscarDados=1";
    requestAssync(urlControle,parms,function(data){
                                    categoria = data.categorias;
                                    produto = data.produtos;
                                    desenharCardapio();
                                    });
}

function desenharCardapio() {
    var ulCategoria = document.getElementById('listaCategoria')

    for(var i in categoria) {
            if(categoria[i].categoriaPaiId == null) {
                    var liCat = document.createElement('li');
                    liCat.innerHTML = '<a href="javascript:;"  onclick="listarItensCardapio('+categoria[i].id+',this)">'+categoria[i].descricao+'</a>';
                    ulCategoria.appendChild(liCat);
            }
    }
}

function desenharAdicionais(pProduto) {
    var slAdicionais = document.getElementById('slAdicionais');

    var options = ""
    var listaAdiconais = produto[pProduto].adicionais.split(",");
    for(var i=0;i<listaAdiconais.length;i++) {
        var dados = listaAdiconais[i].split(";");
        options += "<option value='"+dados[0]+"' >"+dados[1]+"</option>"
    }
    slAdicionais.innerHTML = options;
    slAdicionais.produtoId = pProduto;
    
    showDialog("dialogAdicionais");
}

function adicionarAdionais() {
    
    var slAdicionais = document.getElementById('slAdicionais');
    var adicionais = slAdicionais.value;
    var produtoId = slAdicionais.produtoId;
    var quantidade = document.getElementById('qtdAdicinal').value;

    for(var c in novoPedido) {
        var listaAdicionais = adicionais.split(",");
        for(var i=0;i<listaAdicionais.length;i++){
            var adicional = listaAdicionais[i];
            if(novoPedido[c].codProd == produtoId){
                if(novoPedido[c].adicionais == ""){
                    novoPedido[c].adicionais = adicional+";"+quantidade;
                }else{
                    novoPedido[c].adicionais += ","+adicional+";"+quantidade;
                }
            }
        }
    }
    
    console.log(novoPedido);
    
    hideDialog("dialogAdicionais");
}

function acaoLogado(){
    showSection('inicio');
    buscarDados();
    listarMesas();    
}

function fecharConta() {

    var idConta = document.getElementById('btnConta').dataset.idconta;

    var parms = "fecharConta="+idConta+"&dataHoraFechamento="+dataHoraDisplayToLogical("",1);
    var retorno = requestSync(urlControle,parms);

    if(retorno.retorno){
        alert("Conta fechada");
        hideAside('conta');
        hideAside('mesa');
    }else{
        alert("Erro ao fechar conta: "+retorno.msg);
    }

}

categoria = new Array();
produto = new Array();
conta = new Array();
novoPedido = new Array();

if(!usuarioLogado()){
    hideSection('inicio');
    showSection('login');
}else{
    acaoLogado();
}

var inicio = document.getElementById('inicio')
Hammer(inicio).on('dragright',listarMesas);
Hammer(inicio).on('dragleft',listarPedidos);

var mesas = document.getElementById('mesas')
Hammer(mesas).on('dragleft',function() {hideAside('mesas')});

var eleConta = document.getElementById('conta')
Hammer(eleConta).on('dragright',function() {hideAside('conta')});


function verificarLogin(){
    
   
}

function efetuarLogin() {
	showSection('inicio');
	listarMesas();
}

function listarItensCardapio(pId,pThis) {
	
	pThis.parentNode.parentNode.style.display = 'none';
	var newUlCategoria = ""
	newUlCategoria = document.createElement('ul');
	document.getElementById('listaCategoria').parentNode.appendChild(newUlCategoria);
	
	for(var c in categoria) {
		if(categoria[c].pai == pId) {
			var liCat = document.createElement('li');
			newUlCategoria.appendChild(liCat);
			liCat.innerHTML = '<a href="javascript:;" onclick="listarItensCardapio('+categoria[c].codigo+',this)">'+categoria[c].descricao+'</a>';
		}
	}
	
	for(var p in produto) {
		if(categoria[produto[p].categoria].codigo == pId) {
			var liProd = document.createElement('li')
			liProd.innerHTML = '<label><span><h2>'+produto[p].nome+'</h2><small>'+produto[p].descricao+'</small></span><input type="checkbox" class="icon-check-empty" value="'+p+'" /></label>'
			newUlCategoria.appendChild(liProd)
		}
	}
}

//buscarAssync('listarContas=1',listarMesas)
function listarMesas() {   
//function listarMesas(conta) {
	
	getContas(); // exemplo
	
	showAside('mesas');
	var ulMesas = document.getElementById('listaMesas') 
	ulMesas.innerHTML = ""
	for(var j in conta) {
		var backColor = ""
		if(conta[j].status == 1) {
			backColor = "green";
		} else if(conta[j].status == 2) {
			backColor = "red";
		} else if(conta[j].status == 3) {
			backColor = "gray"
		}
		var liMesa = document.createElement('li')
		ulMesas.appendChild(liMesa)
		liMesa.innerHTML =	'<a href="javascript:;" data-idconta="'+conta[j].idConta+'" data-qtdpessoas="'+conta[j].qtdPessoas+'" data-mesa="'+conta[j].numeroMesa+'" data-status="'+conta[j].status+'">' +
								'<h2 class="li-thumb" style="background-color:'+backColor+'">'+conta[j].numeroMesa+'</h2>'+
								'<h3>R$ '+conta[j].totalAtual+'</h3>' +
							'</a>'
		
		liMesa.children[0].addEventListener('click',function() {
			hideAside('mesas');
			document.getElementById('listaMesas') .innerHTML = "";
			
			document.getElementById('btnConta').dataset.idconta = this.dataset.idconta;
			document.getElementById('btnNumeroMesa').innerText  = 'MESA ' + this.dataset.mesa;
			
			var articlePedido = document.querySelector('#inicio article')
			articlePedido.innerHTML = '<h6>MESA</h6><h5 class="box flex content-center align-center">' + this.dataset.mesa + '</h5><div class="box content-center item-center"><div><button class="button-inverse icon-down-dir"></button></div><div class="box padding1"><input type="number" readonly="true" value="'+this.dataset.qtdpessoas+'" />Pessoas</div><div><button class="button-inverse icon-up-dir"></button></div></div>'
			
			if(this.dataset.status == "1") {
				var navFooter = document.createElement('nav')
				var btn = document.createElement('button');
				btn.className = 'icon-doc-inv';
				btn.style.cssText = 'width:100%;font-size:18px;font-weight:400;'
				btn.innerText = 'Novo pedido';
				navFooter.appendChild(btn);
				document.querySelector('#inicio').appendChild(navFooter);
				btn.addEventListener('click',function() {showSection('pedido')},false)
			} else if(this.dataset.status == "2") {
				document.getElementById('encMesaAberta').style.display = 'none';
				document.getElementById('encMesaFechada').style.display = '';
				document.getElementById('encMesaLivre').style.display = 'none';
			} else if(this.dataset.status == "3") {
				document.getElementById('encMesaAberta').style.display = 'none';
				document.getElementById('encMesaFechada').style.display = 'none';
				document.getElementById('encMesaLivre').style.display = '';
			}
		},false);
	}
}

function adicionarProdutoPedido() {

	var codProduto = parseInt(document.getElementById('iptCodProduto').value);
	var qtdProduto = parseInt(document.getElementById('iptQtdProduto').value);
	
	if(produto[codProduto] == 'undefined') {
		alert('Produto n�o existe');
		return false;
	}
	if(document.getElementById('pedidoProduto'+codProduto)) {
		alert('Produto j� adicionado');
		return false;
	}
	var ulItensPedido = document.getElementById('ulItensPedido');
	var liItem = document.createElement('li');
	liItem.id = "pedidoProduto" + codProduto 
	liItem.innerHTML =  '<span class="input-thumb" id="qtd'+codProduto+'"><p>'+qtdProduto+'</p></span>'+
						'<a href="javascript:return false;" id="listLink'+codProduto+'" onclick="this.nextElementSibling.nextElementSibling.classList.toggle(\"show-action\")">'+
							'<h2>'+ produto[codProduto].nome +'</h2>'+
						'</a>'+
						'<p class="count" id="valorPedido'+codProduto+'">'+(produto[codProduto].preco*qtdProduto)+'</p>'+
						'<div class="action" id="action'+codProduto+'">'+
							'<button class="icon-quote-left" onclick="showDialog(\'dialogAdicionais\')"></button>'+
							'<button class="icon-trash" onclick="excluirItemPedido('+codProduto+')"></button>'+
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
				if(novoPedido[c].codigoProduto == codProduto) {
					novoPedido[c].quantidadeProduto = parseInt(pThis.innerText)-1
				}
			}
		}
	});
	Hammer(document.getElementById('qtd'+codProduto)).on('dragleft',function() {
		this.children[0].style.cssText = '-webkit-transform:translate3d(-100%,0,0);';
		var pThis = this
		var stime = setTimeout(function() {
			pThis.innerHTML = '<p>'+(parseInt(pThis.innerText) + 1) + '</p>';
			document.getElementById('valorPedido'+codProduto).innerText = produto[codProduto].preco*(parseInt(pThis.innerText)+1)
		},150);
		for(var c in novoPedido) {
			if(novoPedido[c].codigoProduto == codProduto) {
				novoPedido[c].quantidadeProduto = parseInt(pThis.innerText)+1
			}
		}
	});
	
	
	novoPedido.push({
		codigoProduto: codProduto.value,
		quantidadeProduto: qtdProduto.value,
		adicionais: [{
			codigoAdicional: 10,
			qtdAdicional: 2
		},
		{
			codigoAdicional: 8,
			qtdAdicional: 5
		}]
	});
	
	
	document.getElementById('iptCodProduto').value = '';
	document.getElementById('iptQtdProduto').value = '';
	
	
}

function excluirItemPedido(pCodProduto) {
	for(var i in novoPedido) {
		if(novoPedido[i].codigoProduto == pCodProduto) {
			novoPedido.splice(i,1);
			var produtoExcluido = document.getElementById('pedidoProduto'+pCodProduto);
			produtoExcluido.parentNode.removeChild(produtoExcluido)
		}
	}
}

function listarPedidos() {
	showAside('conta');
	var idConta = document.getElementById('btnConta').dataset.idconta;
	
	//var objConta = GetPedidos(idConta);
	
	/*Exemplo*/
	var objConta = {
		totalAtual: '120,50',
		pedido: [
			{
				qtd: '4',
				descricao: 'Coca-Cola - Lata 350ml',
			},
			{
				qtd: '2',
				descricao: 'Brama 600ml',
			},
			{
				qtd: '1',
				descricao: 'Por��o - Isca de frango',
			},
			]
	}
	/*Fim exemplo*/
	var ulPedidos = document.getElementById('listaPedidos');
	ulPedidos.innerHTML = ""
	for(var i=0; i<objConta.pedido.length;i++) {
		var pedido = objConta.pedido[i];
		var liPedido = document.createElement('li');
		liPedido.className = 'box'
		liPedido.innerHTML = '<p class="box flex item-center">'+pedido.qtd+' '+pedido.descricao+'</p>';
		ulPedidos.appendChild(liPedido)
	}
	document.getElementById('totalAtual').innerHTML = objConta.totalAtual;
}

function confirmarPedido() {
	//SalvarPedido(JSON.stringify(novoPedido))
	alert(novoPedido[0])
	novoPedido = []
	document.getElementById('ulItensPedido').innerHTML = "";
	hideSection('pedido');
}

// Metodo de exemplo
function getContas()  {
	conta = new Array();
	conta[0] = {
		idConta: 1,
		numeroMesa: 7,
		descricaoMesa: 'A',
		qtdPessoas: 4,
		totalAtual: 120.20,
		status: 1,
	}

	conta[1] = {
		idConta: 2,
		numeroMesa: 10,
		descricaoMesa: '',
		qtdPessoas: 3,
		totalAtual: 80.32,
		status: 2,
	}

	conta[2] = {
		idConta: 3,
		numeroMesa: 11,
		descricaoMesa: '',
		qtdPessoas: 8,
		totalAtual: 0,
		status: 3,
	}
	return conta;
}

var novoPedido = new Array();

var adicional = ['Copo com gelo','Copo com lim�o','Sem a�ucar','Bem passado','Mal passado','Cortado no meio']

var categoria = new Array()

categoria[0] = {
	codigo: 0,
	descricao: 'Importadas',
	adicionais: [0,1,2],
	pai:2
}
categoria[1] = {
	codigo: 1,
	descricao: 'Nacionais',
	adicionais: [3,4,5],
	pai:null
}

categoria[2] = {
	codigo: 2,
	descricao: 'Cervejas',
	adicionais: null,
	pai:null
}

categoria[0];
categoria[1].pai = 2;
categoria[2].pai = null;

var produto = new Array()


produto[0] = {
	codigo: 0,
	categoria: 0,
	nome: 'Budy',
	descricao: 'Lorem ipsum necque porro dolor sit amet',
	preco: 5.50
}
produto[1] = {
	codigo: 1,
	categoria: 0,
	nome: 'Stella',
	descricao: 'Lorem ipsum necque porro dolor sit amet',
	preco: 5.50
}
produto[2] = {
	codigo: 2,
	categoria: 1,
	nome: 'Skol',
	descricao: 'Lorem ipsum necque porro dolor sit amet',
	preco: 5.50
}
produto[3] = {
	codigo: 3,
	categoria: 1,
	nome: 'Brahma',
	descricao: 'Lorem ipsum necque porro dolor sit amet',
	preco: 5.50
}


var ulCategoria = document.getElementById('listaCategoria')

for(var i in categoria) {
	if(categoria[i].pai == null) {
		var liCat = document.createElement('li');
		liCat.innerHTML = '<a href="javascript:;"  onclick="listarItensCardapio('+categoria[i].codigo+',this)">'+categoria[i].descricao+'</a>';
		ulCategoria.appendChild(liCat);
	}
}

var inicio = document.getElementById('inicio')
Hammer(inicio).on('dragright',listarMesas);
Hammer(inicio).on('dragleft',listarPedidos);

var mesas = document.getElementById('mesas')
Hammer(mesas).on('dragleft',function() {hideAside('mesas')});

var conta = document.getElementById('conta')
Hammer(conta).on('dragright',function() {hideAside('conta')});


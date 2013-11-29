<?php
header("Content-Type: text/html; charset=UTF-8",true);
require_once ('../controller/conta.php');
?>
<!Doctype html>
<html>
<head>
<title>Mesas</title>
<?php include 'css_include.php';?> 
<script>
    document.createElement("main");
    urlConta = "<?php echo retornaUrl()."controller/conta.php"; ?>";
</script>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>
    
<aside>
    <div class="search-page">
        <input type="text" placeholder="Pesquise pelo mesa" size="34" />
    </div>
    <ul id="listaConta">
    </ul>
</aside>
    
<section id="section">
    <div class="title">
        <h1>Mesas</h1>
    </div>
    <div class="row">
        <div class="col-12" id="retorno">

        </div>
    </div>
    <div class="row">
        <input type="text" id="idConta" style="display:none;" />
        <div class="col-4">
            <div class="field">
                <label class="label">Mesa</label>
                <input type="text" size="15" id="mesa" />
            </div>
        </div>
        <div class="col-4">
            <div class="field">
                <label class="label">Descrição adicional</label>
                <input type="text" size="15" maxlength="1" id="descricao" />
            </div>
        </div>
        <div class="col-4">
            <div class="field">
                <label class="label">Pessoas</label>
                <input type="text" size="15" id="qtdPessoas" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="field">
                <label class="label">Situação</label>
                <h3 id="status"></h3>
            </div>
        </div>
        <div class="col-4">
            <div class="field">
                <label class="label">Aberta em</label>
                <h3 id="dataHora"></h3>
            </div>
        </div>
        <div class="col-4">
            <div class="field">
                <label class="label">Fechada em</label>
                <h3 id="dataHoraFechamento"></h3>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="check-set">
                <label>
                    <input type="checkbox" id="taxaServico" /> Taxa de serviço
                </label>
            </div>
        </div>
    </div>
    
    <div class="row">
        <input type="hidden" id="codigoPedido" />
        <input type="hidden" id="valorProduto" />
        <input type="hidden" size="10" id="totalPedido" />
        <div class="field col-2">
            <label class="label">Código Produto</label>
            <input type="text" size="10" id="codigoProduto" />
        </div>

        <div class="field col-2">
            <label class="label">Produto</label>
            <select id="selectProduto" width="100px"></select>
        </div>

        <div class="field col-2">
            <label class="label">Quantidade</label>
            <input type="number" size="2" value="1" style="width:50px;" id="quantidadePedido" />
        </div>
        
        <div class="field col-2">
            <label class="label">Adicionais</label>
            <select id="selectAdicionais" multiple="multiple" width="100px"></select>
        </div>

        <div class="field  col-2">
            <label class="label">Observação</label>
            <input type="text" size="10" id="observacaoPedido" />
        </div>
        <div class="col-2">
            <input type="button" value="+" id="btnSalvarPedido" class="bt-success"/>
        </div>   
    </div>

    
    <div class="row">
        <table id="tabelaPedidos">

        </table>
    </div>
            
    <div class="row">    
        <div class="button-bar">
            <input type="button" value="Abrir mesa" id="btnAbrirMesa" style='display: none;' class="bt-success"/>
            <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
            <input type="button" value="Liberar mesa" style='display: none;' id="btnExcluir" style='display: none;' class="bt-negative" />
            <input type="button" value="Fechar conta" style='display: none;' id="btnFecharConta" class="bt-success"/>
            <input type="button" value="Trocar mesa" style='display: none;' id="btnTrocarMesa" class="bt-success"/>
            <input type="button" value="Desconto..." style='display: none;' id="btnDesconto" class="bt-success"/>
            <input type="button" value="Pagamento..." style='display: none;' id="btnRealizarPagamento" class="bt-success"/>
        </div>
    </div>
    
    
        
    </div>


   
     
</section>

    
    
    
    <div id="popupTrocarMesa" class="modal" style="display:none;">
    <div>
        <div>
            <div class="field">
                <label class="label">Nova mesa</label>
                <input type="text" size="10" id="novaMesa" />
            </div>
            <input type="button" value="Salvar" id="btnSalvarTroca" class="bt-success"/>
            <input type="button" value="Cancelar" id="btnCancelarTroca" class="bt-negative"/>
        </div>
    </div>        
</div>
  
    
<div id="popupDesconto" class="modal" style="display:none;">
    <div>
        <div>
            <div class="modal-title">Desconto</div>
            <div class="field">
                <input type="text" size="10" id="valorDesconto" />
            </div>

            <div>
                <input type="button" value="Salvar" id="btnSalvarDesconto" class="bt-success"/>
                <input type="button" value="Cancelar" id="btnCancelarDesconto" class="bt-negative"/>
            </div>
        </div>
    </div> 
</div>

    
    
    
<div id="popupPagamento" class="modal" style="display:none;">
        <div>
            <div>
                <div class="field">
                    <label class="label">Forma pagamento</label>
                    <select id="selectFP"></select>
                </div>
           
                <div class="field">
                    <label class="label">Valor</label>
                    <input type="text" size="10" id="valorPagamento" />
                </div>
          
                <div class="field">
                    <label class="label">Observação</label>
                    <input type="text" size="30" id="observacao" />
                </div>
       
                <input type="button" value="Salvar" id="btnSalvarPagamento" class="bt-success"/>
                <input type="button" value="Cancelar" id="btnCancelarPagamento" class="bt-negative"/>
            </div>
        </div>
</div>   
    

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/conta.js"></script>

</body>
</html>
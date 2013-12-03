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

<style>
    #listaConta {margin:5px;}
    #listaConta li {width:25%;height:80px;float:left;background:#FFF;}
    #listaConta li label {text-align:center;padding:15px;}
    #listaConta li label span {margin-right:0;float:none;border-radius:6px;display:block;margin:0 auto;width:48px;height:48px;font-size:32px;line-height:48px;}
    .numero-mesa-selecionada {float:left;color:#777;background:#CCC;border-radius:6px;box-shadow:0 0 10px 0 rgba(0,0,0,.3);text-align:center;margin:0 15px 0 0;}
    .numero-mesa-selecionada input {background:transparent;font-size:40px !important;text-align:center;width:80px;height:60px;line-height:50px;}
</style>
</head>
<body>

<?php include 'cabecalho.php';?>
<?php include 'menu.php';?>
    
<aside>
    <div class="search-page">
       
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

            
        <div>
            <input type="hidden" id="idConta" />
            <input type="hidden" size="15" maxlength="1" id="descricao" />
            <div class="field">
                <div class="numero-mesa-selecionada">
                    <input type="text" readonly="readonly" size="3" id="mesa" />
                     <h5 id="status"></h5>
                </div>
                <div>
                    <input type="number" style="width:100px" placeholder="Qtd Pessoas" id="qtdPessoas" />
                    <input type="button" value="Abrir mesa" id="btnAbrirMesa" style='display: none;' class="bt-normal"/>
                    <input type="button" value="Salvar" id="btnSalvar" style='display: none;' class="bt-success"/>
                </div>
                <div>
                    <h3 class="icon-calendar" id="dataHora"></h3>
                    <h3 class="icon-calendar"  id="dataHoraFechamento"></h3>
                </div>
            </div>
        
            <div>
                <table id="tabelaPedidos">

                </table>
            </div>
        </div>

           
    </div>
    <div class="row">    
        <div class="button-bar">
            
            <input type="button" value="Liberar mesa" style='display: none;' id="btnExcluir" style='display: none;' class="bt-negative" />
            <input type="button" value="Fechar conta" style='display: none;' id="btnFecharConta" class="bt-success"/>
            <input type="button" value="Trocar mesa" style='display: none;' id="btnTrocarMesa" class="bt-success"/>
            <input type="button" value="Desconto..." style='display: none;' id="btnDesconto" class="bt-success"/>
            <!--<input type="button" value="Pagamento..." style='display: none;' id="btnRealizarPagamento" class="bt-success"/>-->
        </div>
    </div>        
</div>

</section>

    
    
    <div class="group-overlay before-pedido" style="top:75px;z-index: 9;">
                <input type="hidden" id="codigoPedido" />
                <input type="hidden" id="valorProduto" />
                <input type="hidden" size="10" id="totalPedido" />
                <input type="hidden" size="10" id="codigoProduto" />

                <div class="field">
                    <select id="selectProduto" style="width:100%;"></select>
                </div>

                <div class="field">
                    <input type="number" placeholder="Quantidade" size="2" style="width:100%;" id="quantidadePedido" />
                </div>

                <div class="field">
                    <select id="selectAdicionais" multiple="multiple" style="width:100%"></select>
                </div>

                <div class="field">
                    <textarea rows="3" placeholder="Observaçâo" id="observacaoPedido"></textarea>
                </div>
                <div class="field text-right">
                    <button id="btnSalvarPedido" class="icon-plus bt-success">Incluir pedido</button>
                </div>   
            </div>
            
            
            <div id="grpPagamento" class="group-overlay before-pagamento" style="top:400px;z-index: 9;">
                <div class="field">
                    <select accesskey="2" id="selectFP" onfocus="fixGroupOverlay('grpPagamento');"></select>
                    <input type="text" size="10" placeholder="Valor" id="valorPagamento" onfocus="fixGroupOverlay('grpPagamento');" />
                </div>
          
                <div class="field">
                    <input type="text" size="30" id="observacao" placeholder="Observação" onfocus="fixGroupOverlay('grpPagamento');" />
                </div>
                
                <div class="field text-right">
                    <button id="btnSalvarPagamento" class="icon-plus bt-success">Incluir pagamento</button>
                </div>
            </div>
    
    
    
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
    

<?php include 'rodape.php';?>
<?php include 'js_include.php';?> 
<script type="text/javascript" src="../js/conta.js"></script>

</body>
</html>

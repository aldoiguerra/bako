
function showAside(pId) {
	var elAside = document.getElementById(pId)
	elAside.classList.add('show-aside');
}

function hideAside(pId) {
	var elAside = document.getElementById(pId)
	elAside.classList.remove('show-aside');
}

function showSection(pId) {
	var elSection = document.getElementById(pId)
	elSection.classList.add('show-section')
}

function hideSection(pId) {
	var elSection = document.getElementById(pId)
	elSection.classList.remove('show-section')
}


function showDialog(pId) {
	var elSection = document.getElementById(pId)
	elSection.classList.add('show-dialog')
}

function hideDialog(pId) {
	var elSection = document.getElementById(pId)
	elSection.classList.remove('show-dialog')
}

function requestAssync(url,parms,funcaoOk,tipoRetorno){
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
    }
    if(!tipoRetorno){
        tipoRetorno = "json";
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            console.log("retorno: "+xmlhttp.responseText);
            if(tipoRetorno == "json"){
                var retorno = eval('(' + xmlhttp.responseText + ')');
            }else{
                var retorno = xmlhttp.responseText;
            }
            funcaoOk(retorno);
        }
    }        
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(parms);
}

function requestSync(url,parms,tipoRetorno){
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    if(!tipoRetorno){
        tipoRetorno = "json";
    }
    xmlhttp.open("POST",url,false);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(parms);
    if((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
        console.log("retorno: "+xmlhttp.responseText);
        if(tipoRetorno == "json"){
            return eval('(' + xmlhttp.responseText + ')');
        }else{
            return xmlhttp.responseText;
        }
    }else{
        return null;
    }
}

function dataHoraDisplayToLogical(dataHora,formato){
    var retorno = "";
    var data = "";
    var hora = ""
    if((dataHora) && (dataHora  != "")){
        var lista = dataHora.toString().split(" ");
        data = lista[0];
        hora = lista[1];
        var ldata = data.split("/");
        data = ldata[2]+"-"+ldata[1]+"-"+ldata[0];
    }else{
        var today=new Date();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
        var dia=today.getDate();
        var mes=today.getMonth();
        var ano=today.getFullYear();
        data = ano+"-"+mes+"-"+dia;
        hora = h+":"+m+":"+s;
    }
    if (formato == 1){
        retorno = data+" "+hora;
    }else if (formato == 2){
        retorno = data;
    }else if (formato == 3){
        retorno = hora;
    }
    return retorno;
}

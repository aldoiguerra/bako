
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

function resquestSync(url,parms,tipoRetorno){
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
        if(tipoRetorno == "json"){
            return eval('(' + xmlhttp.responseText + ')');
        }else{
            return xmlhttp.responseText;
        }
    }else{
        return null;
    }
}


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

function buscarAssync(url,parms,funcaoOk){
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            funcaoOk(eval('(' + xmlhttp.responseText + ')'));
        }
    }        
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(parms);
}

function buscarSync(url,parms){
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.open("POST",url,false);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(parms);
    if((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
        return eval('(' + xmlhttp.responseText + ')');
    }else{
        return null;
    }
}

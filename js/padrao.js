function mudarMenu(pMenu){
    var menu = document.getElementById(pMenu);
    if(menu){
        if(menu.style.display === "none"){
            menu.style.display = "";
        }else{
            menu.style.display = "none";
        }
    }
}

function displayDataHora()
{
    var today=new Date();
    var h=today.getHours();
    var m=today.getMinutes();
    var s=today.getSeconds();
    // add a zero in front of numbers<10
    m=checkTime(m);
    s=checkTime(s);
    document.getElementById('displayHora').innerHTML=h+":"+m+":"+s;
    t=setTimeout(function(){displayDataHora()},500);
}

function checkTime(i)
{
    if (i<10){
      i="0" + i;
    }
    return i;
}

displayDataHora();

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

function dataHoraLogicalToDisplay(dataHora,formato){
    var retorno = "";
    if((dataHora) && (dataHora != "")){
        var lista = dataHora.toString().split(" ");
        var data = lista[0];
        var hora = lista[1];
        var ldata = data.split("-");
        var data = ldata[2]+"/"+ldata[1]+"/"+ldata[0]
        if(formato == 1){
            retorno = data+" "+hora;
        }else if(formato == 2){
            retorno = data;
        }else if(formato == 2){
            retorno = hora;
        }
    }
    return retorno;
}

function numeroLogicalToDisplay(valor){
    var retorno = "0,00";
    if (valor){
        retorno = valor.replace(/\./,",");
    }
    return retorno;
}

function numeroDisplayToLogical(valor){
    var retorno = "0.00";
    if (valor){
        retorno = valor.replace(/,/,".");
    }
    return truncate(parseFloat(retorno));
}

function truncate(valor){
    var retorno = Math.floor(valor * 100) / 100;
    return retorno;
}

function formatarCampoNumerico(valor,decimais){
    if(valor != ""){
        valor = valor.replace(/,/,"");
        valor = valor.replace(/\./,"");
        valor = parseInt(valor,10);
        if(decimais > 0){
            valor = valor/Math.pow(10,decimais);
        }
        valor = valor.toFixed(2);
        valor = valor.toString().replace(/\./,",");
    }
    return valor;
}

function apenasNumeros(valor,tecla){
    if((tecla>=48 && tecla<=57)){
        return true;
    }else if((tecla>=96 && tecla<=105)){
        return true;
    }else if (tecla==8 || tecla==0){
        return true;
    }
    return false;
}

function acoesCampoNumerico(){
    $("input[tipo=numerico]").css("text-align","right");
    $("input[tipo=numerico]").keydown(function(event){return apenasNumeros($(this).val(),event.keyCode)});
    $("input[tipo=numerico]").keyup(function(event){$(this).val(formatarCampoNumerico($(this).val(),$(this).attr("decimais")))});
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
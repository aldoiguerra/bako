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


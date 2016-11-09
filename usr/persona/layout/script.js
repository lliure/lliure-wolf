function ll_addDesk(){
    var	nome = prompt("Qual será a identificão deste local em seu desktop?");

    if (nome != null && nome != "")
        ll_load('opt/desktop/onserver.php?ac=addDesktop', {nome: nome});
}

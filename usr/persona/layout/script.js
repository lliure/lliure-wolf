function ll_addDesk(){
    var	nome = prompt("Qual ser� a identific�o deste local em seu desktop?");

    if (nome != null && nome != "")
        ll_load('opt/desktop/onserver.php?ac=addDesktop', {nome: nome});
}

function mudarOpacidade() {
    document.getElementById("mercado_barracas_comprar").style.opacity = "100%";
}

function vender() {

}

function showMercado() {
    document.getElementById("mercado_ver_mercado").onclick = function () {
        console.log("1");
        mudarOpacidade();
    }

}
function showOpcaoVenda() {
    document.getElementById("mercado_opcoes_venda").style.display = "block";

    document.getElementById("mercado_opcoes_venda_vender").onclick = function () {

    }

    document.getElementById("mercado_opcoes_venda_cancelar").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.display = "block";
        document.getElementById("mercado_ver_mercado").style.display = "block";
        document.getElementById("mercado_opcoes_venda").style.display = "none";
    }
}


function mercadoEventos() {
    document.getElementById("mercado_vender").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.display = "none";
        document.getElementById("mercado_ver_mercado").style.display = "none";
        showOpcaoVenda();
    }

    document.getElementById("mercado_ver_mercado").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.opacity = "100%";
    }

}

window.onload = function () {
    mercadoEventos();
}
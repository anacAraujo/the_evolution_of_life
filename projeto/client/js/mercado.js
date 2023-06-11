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


async function getAllOffers() {
    const response = await fetch("../server/market/get_all_offers.php");
    const jsonData = await response.json();
    return jsonData;
}

function mercadoEventos() {
    document.body.style.backgroundImage = 'url("img/fundo_mercado.png")';

    document.getElementById("mercado_opcoes_venda").style.display = "none";

    document.getElementById("mercado_vender").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.display = "none";
        document.getElementById("mercado_ver_mercado").style.display = "none";
        showOpcaoVenda();
    }

    document.getElementById("mercado_ver_mercado").onclick = async function () {
        document.getElementById("mercado_barracas_comprar").style.opacity = "100%";
        const allOffers = await getAllOffers();
        console.log(allOffers);
    }

}

window.onload = function () {
    mercadoEventos();
}
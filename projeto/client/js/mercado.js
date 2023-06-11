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

async function insertOffer(data) {

    // TODO just an example
    try {
        const response = await fetch("../server/market/insert_offer.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        console.log("Success:", result);
        return result;
    } catch (error) {
        console.error("Error:", error);
    }
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

        for (const offer of allOffers) {
            console.log(offer);
        }
    }

}

window.onload = function () {
    mercadoEventos();
}

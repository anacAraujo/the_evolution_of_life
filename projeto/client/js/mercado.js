let allIems = {};
let inventory = {};
let allOffers = [];

function showOpcaoVenda() {
    document.getElementById("mercado_opcoes_venda").style.display = "block";

    document.getElementById("mercado_opcoes_venda_vender").onclick = function () {

    }

    document.getElementById("mercado_opcoes_venda_cancelar").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.display = "block";
        document.getElementById("mercado_ver_mercado").style.display = "block";
        document.getElementById("mercado_opcoes_venda").style.display = "none";
    }

    // TODO test
    insertOffer(1, 20, 3, 10);
}


async function getAllOffers() {
    const response = await fetch("../server/market/get_all_offers.php");
    const jsonData = await response.json();
    return jsonData;
}

async function getAllItems() {
    const response = await fetch("../server/items/get_all_items.php");
    const jsonData = await response.json();

    for (const item of jsonData) {
        allIems[item.id] = item;
    }
}

async function insertOffer(myItemId, myItemQty, otherItemId, otherItemQty) {

    const data = {
        my_item_id: myItemId,
        my_item_qty: myItemQty,
        other_item_id: otherItemId,
        other_item_qty: otherItemQty
    };

    try {
        const response = await fetch("../server/market/create_offer.php", {
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
        allOffers = await getAllOffers();

        for (const offer of allOffers) {
            console.log(offer);
        }
    }

}

window.onload = async function () {

    await getAllItems();
    console.log(allIems);

    // TODO inventory

    mercadoEventos();
}

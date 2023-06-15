let allIems = {};
let inventory = {};
let planetOffers = {}; // Map <ID do planeta, Array de offers>

async function getInventory() {
    const response = await fetch("../server/users/get_inventory.php");
    const jsonData = await response.json();

    if (jsonData.code === 'NO_LOGIN') {
        document.location.href = './login.html';
        return;
    }

    for (const inv of jsonData) {
        inventory[inv.item_id] = inv;
    }
}

async function getPlanetOffers() {
    const response = await fetch("../server/market/get_all_offers.php");
    const jsonData = await response.json();
    console.log(jsonData);

    for (const offer of jsonData) {
        if (!planetOffers[offer.planets_user_id]) {
            planetOffers[offer.planets_user_id] = [];
        }
        planetOffers[offer.planets_user_id].push(offer);
    }
}

async function getAllItems() {
    const response = await fetch("../server/items/get_all_items.php");
    const jsonData = await response.json();

    for (const item of jsonData) {
        allIems[item.id] = item;
    }
}

async function createOffer(myItemId, myItemQty, otherItemId, otherItemQty) {

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

function showOpcaoVenda() {
    document.getElementById("mercado_opcoes_venda").style.display = "block";

    document.getElementById("mercado_opcoes_venda_vender").onclick = function () {

    }

    document.getElementById("mercado_opcoes_venda_cancelar").onclick = function () {
        document.getElementById("mercado_barracas_comprar").style.display = "block";
        document.getElementById("mercado_ver_mercado").style.display = "block";
        document.getElementById("mercado_opcoes_venda").style.display = "none";
    }

    // TODO
    // createOffer(1, 20, 3, 10);

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
    }

}

window.onload = async function () {

    await getAllItems();
    console.log(allIems);

    await getInventory();
    console.log(inventory);

    await getPlanetOffers();

    console.log(planetOffers);

    const planets = Object.keys(planetOffers);

    for (const planet of planets) {
        const offers = planetOffers[planet];
        console.log(offers);
    }
    mercadoEventos();
}

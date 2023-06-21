let allIems = {};
let inventory = {};
let userInfo = {};
let planetOffers = {}; // Map <ID do planeta, Array de offers>
let current_user_item_id = 1;
let current_item_id = 1;

async function getUserInfo() {
    const response = await fetch("../server/users/get_user_info.php");
    const jsonData = await response.json();

    if (jsonData.code === 'NO_LOGIN') {
        document.location.href = './login.html';
        return;
    }
    userInfo = jsonData;
}

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

async function createOffer(myItem, myItemQty, otherItem, otherItemQty) {
    const data = {
        my_item: myItem,
        my_item_qty: myItemQty,
        other_item: otherItem,
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

async function showOpcaoVenda() {
    document.querySelector(".mercado_barracas_comprar").style.display = "none";
    document.getElementById("mercado_ver_mercado").style.display = "none";
    document.getElementById("mercado_vender").style.display = "none";
    document.getElementById("mercado_opcoes_venda").style.display = "block";
    document.getElementById("mercado_opcoes_venda_cancelar").style.display = "block";
    document.getElementById("mercado_opcoes_venda_vender").style.display = "block";

    // PERCORRER INVENTORY
    await getInventory();
    let user_item_symbol = inventory[current_user_item_id].symbol;
    document.getElementById("mercado_user_items").src = "assets/iconsfrascos/" + user_item_symbol + ".svg";
    document.querySelector(".sdireita").onclick = function () {
        current_user_item_id++;
        if (current_user_item_id > 11) {
            current_user_item_id = 1;
        }
        user_item_symbol = inventory[current_user_item_id].symbol;
        document.getElementById("mercado_user_items").src = "assets/iconsfrascos/" + user_item_symbol + ".svg";
    }
    document.querySelector(".sesquerda").onclick = function () {
        current_user_item_id--;
        if (current_user_item_id < 1) {
            current_user_item_id = 11;
        }
        user_item_symbol = inventory[current_user_item_id].symbol;
        document.getElementById("mercado_user_items").src = "assets/iconsfrascos/" + user_item_symbol + ".svg";
    }

    // PERCORRER ALL ITEMS
    getAllItems();
    let item_symbol = allIems[current_item_id].symbol;
    document.getElementById("mercado_all_items").src = "assets/iconsfrascos/" + item_symbol + ".svg";
    document.querySelector(".sdireita_troca").onclick = function () {
        current_item_id++;
        if (current_item_id > 11) {
            current_item_id = 1;
        }
        item_symbol = allIems[current_item_id].symbol;
        document.getElementById("mercado_all_items").src = "assets/iconsfrascos/" + item_symbol + ".svg";
    }
    document.querySelector(".sesquerda_troca").onclick = function () {
        current_item_id--;
        if (current_item_id < 1) {
            current_item_id = 11;
        }
        item_symbol = allIems[current_item_id].symbol;
        document.getElementById("mercado_all_items").src = "assets/iconsfrascos/" + item_symbol + ".svg";
    }

    // Quantidade item user
    let num_item_user = 1;
    document.querySelector(".quadrado_venda_item_base_venda_quantidade").innerHTML = num_item_user;

    document.querySelector(".nadicionar").onclick = function () {
        num_item_user++;
        document.querySelector(".quadrado_venda_item_base_venda_quantidade").innerHTML = "<p>" + num_item_user + "</p>";
    };

    document.querySelector(".nremover").onclick = function () {
        if (num_item_user === 1) {
            return;
        }
        num_item_user--;
        document.querySelector(".quadrado_venda_item_base_venda_quantidade").innerHTML = "<p>" + num_item_user + "</p>";
    };

    // Quantidade item troca
    let num_item = 1;
    document.querySelector(".quadrado_troca_item_base_venda_quantidade").innerHTML = num_item;

    document.querySelector(".nadicionar_troca").onclick = function () {
        num_item++;
        document.querySelector(".quadrado_troca_item_base_venda_quantidade").innerHTML = "<p>" + num_item + "</p>";
    };

    document.querySelector(".nremover_troca").onclick = function () {
        if (num_item === 1) {
            return;
        }
        num_item--;
        document.querySelector(".quadrado_troca_item_base_venda_quantidade").innerHTML = "<p>" + num_item + "</p>";
    };

    document.getElementById("mercado_opcoes_venda_vender").onclick = async function () {
        await createOffer(user_item_symbol, num_item_user, item_symbol, num_item);
        document.getElementById("mercado_opcoes_venda").style.display = "none";
        document.getElementById("mercado_opcoes_venda_cancelar").style.display = "none";
        document.getElementById("mercado_opcoes_venda_vender").style.display = "none";
        document.querySelector(".mercado_barracas_comprar").style.display = "block";
        document.getElementById("mercado_ver_mercado").style.display = "block";
        document.getElementById("mercado_vender").style.display = "block";
    }

    document.getElementById("mercado_opcoes_venda_cancelar").onclick = function () {
        document.querySelector(".mercado_barracas_comprar").style.display = "block";
        document.getElementById("mercado_ver_mercado").style.display = "block";
        document.getElementById("mercado_opcoes_venda").style.display = "none";
        document.getElementById("mercado_opcoes_venda_cancelar").style.display = "none";
        document.getElementById("mercado_opcoes_venda_vender").style.display = "none";
        document.getElementById("mercado_vender").style.display = "block";
    }

}

async function showMarketOffers() {
    document.getElementById("mercado_ver_mercado").style.display = "none";
    document.querySelector(".mercado_barracas_comprar").style.opacity = "100%";

    await getPlanetOffers();
    console.log(planetOffers);

    let offers = [];
    offers = planetOffers[1];
    console.log(offers);


}

async function updateVisualElements() {
    await getUserInfo();
    document.getElementById("avatar").src = "assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg";
}

function logout() {
    document.getElementById("modal_trigger").onclick = function () {
        document.getElementById("modal_logout").style.display = "block";
    };

    document.getElementById("close_modal").onclick = function () {
        document.getElementById("modal_logout").style.display = "none";
    };

    document.getElementById("cancel_modal").onclick = function () {
        document.getElementById("modal_logout").style.display = "none";
    };
}

function mercadoEventos() {
    logout();
    document.body.style.backgroundImage = 'url("img/fundo_mercado.png")';

    document.getElementById("mercado_opcoes_venda").style.display = "none";

    document.getElementById("mercado_vender").onclick = async function () {
        await showOpcaoVenda();
    }

    document.getElementById("mercado_ver_mercado").onclick = async function () {
        await showMarketOffers();
    }
}

window.onload = async function () {
    await updateVisualElements();
    await getAllItems();
    const planets = Object.keys(planetOffers);

    for (const planet of planets) {
        const offers = planetOffers[planet];
        console.log(offers);
    }
    mercadoEventos();
}

let userInfo = {};
let inventory = {};
let allItems = {};

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

async function getAllItems() {
    const response = await fetch("../server/items/get_all_items.php");
    const jsonData = await response.json();

    for (const item of jsonData) {
        allItems[item.id] = item;
    }
}

async function getUserInfo() {
    const response = await fetch("../server/users/get_user_info.php");
    const jsonData = await response.json();

    if (jsonData.code === 'NO_LOGIN') {
        document.location.href = './login.html';
        return;
    }
    userInfo = jsonData;
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

function mainEvents() {
    console.log(userInfo.id_settings);
    document.getElementById("avatar").src = "assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg";
    document.getElementById("frasco").src = "assets/icons_gerais/progresso" + userInfo.id_settings + "/Frasco.svg";
    document.getElementById("planeta").src = "assets/icons_gerais/progresso" + userInfo.id_settings + "/Planeta.svg";

    // Logout click event
    document.getElementById("modal_trigger").onclick = function () {
        document.getElementById("modal_logout").style.display = "block";
    };

    document.getElementById("close_modal").onclick = function () {
        document.getElementById("modal_logout").style.display = "none";
    };

    document.getElementById("cancel_modal").onclick = function () {
        document.getElementById("modal_logout").style.display = "none";
    };

    // List of the quantity of elements

    document.getElementById("icon_atmosfera").onclick = function () {
        document.getElementById("quantidades_elementos").style.display = "block";

        document.getElementById("go_back_to_index").onclick = function () {
            window.location.href = "index.html";
        };

        document.getElementById("icon_atmosfera").style.display = "none";
        document.getElementById("icon_lab").style.display = "none";
        document.getElementById("icon_mercado").style.display = "none";
        document.getElementById("icon_avatar").style.display = "none";
        document.getElementById("modal_trigger").style.display = "none";
    };

    document.getElementById("planeta").onclick = function () {
        document.getElementById("particles-js").style.display = "none";
        document.getElementById("planeta_interior").style.display = "block";
        document.getElementById("icons_gerais").style.display = "block";

    }
}

function moverElemento() {
    var elemento = document.getElementById("");

    var larguraPagina = window.innerWidth;
    var novaPosicao = larguraPagina / 2 - elemento.offsetWidth / 2;

    elemento.style.transition = "all 1s ease";
    elemento.style.left = novaPosicao + "px";
}

window.onload = async function () {
    await getUserInfo();
    console.log(userInfo);

    mainEvents();
};

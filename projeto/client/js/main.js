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

async function createRewardOfferAlien() {
    await fetch("../server/alien/create_reward.php");
}

async function updateVisualElements() {
    await getUserInfo();
    document.getElementById("avatar").src = "assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg";
    document.getElementById("frasco").src = "assets/icons_gerais/progresso" + userInfo.id_settings + "/Frasco.svg";
    document.getElementById("planeta").src = "assets/icons_gerais/progresso" + userInfo.id_settings + "/Planeta.svg";
    document.getElementById("progress_bar_in").ariaValueNow = userInfo.progress;
    document.getElementById("progress_bar_in").style.width = userInfo.progress + "%";
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

function showAlienRandomly() {
    const indexAlienDiv = document.querySelector(".alien_animado");

    const hideIndexAlien = () => {
        indexAlienDiv.style.display = 'none';
    };

    const randomNumber = Math.random();
    const randomDelay = Math.floor(randomNumber * 3000) + 1000;

    setTimeout(() => {
        indexAlienDiv.style.display = 'block';

        setTimeout(hideIndexAlien, 5000);
    }, randomDelay);
}


function mainEvents() {
    logout();

    document.getElementById("planeta").onclick = async function () {
        document.body.style.backgroundImage = "url('img/fundo_planeta_interior.png')";
        document.getElementById("particles-js").style.display = "none";
        document.getElementById("planeta_interior").style.display = "block";
        document.getElementById("icons_gerais").style.display = "flex";
        await fillLandMap();
        setEvents();
    }

    document.getElementById("index_alien").onclick = function () {

    }
}

window.onload = async function () {
    await updateVisualElements();
    showAlienRandomly();
    mainEvents();
};

let userInfo = {};
let inventory = {};
let allIems = {};

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
        allIems[item.id] = item;
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

function mainEvents() {
    document.getElementById("avatar").src += "<img src='assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg' alt=''>";
<<<<<<< HEAD
    document.getElementById("frasco").src += '<img src="assets/icons_gerais/pregresso' + userInfo.id_settings + '/Frasco.svg" alt="">';
    document.getElementById("planeta").src += '<img class="centered-planeta" src="assets/icons_gerais/pregresso' + userInfo.id_settings + '/Planeta.svg">';
=======
    document.getElementById("frasco").src += '<img src="assets/icons_gerais/progresso' + userInfo.id_settings + '/Frasco.svg" alt="">';
    document.getElementById("planeta").src += '<img class="centered-planeta" src="assets/icons_gerais/progresso' + userInfo.id_settings + '/Planeta.svg">';
}

>>>>>>> 166ca08eaa56c0e7b91b19fc5a9289863b835c5e

    // Logout click event
    document.getElementById("modal_trigger").onclick = function () {
        document.getElementById("modal_logout").style.display = "block";
    }
}

window.onload = async function () {
    await getUserInfo();
    console.log(userInfo);

    mainEvents();
}

let userInfo = {};
//TODO MENU ITEMS (get inventory) 
async function getUserInfo() {
    const response = await fetch("../server/users/get_user_info.php");
    const jsonData = await response.json();

    if (jsonData.code === 'NO_LOGIN') {
        document.location.href = './login.html';
        return;
    }
    userInfo = jsonData;
}

function updatePlanet() {
    document.getElementById("avatar").src += "<img src='assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg' alt=''>";
    //TODO FRASCO
    document.getElementById("avatar").src += "<img src='assets/avatar_perfil/Avatar" + userInfo.avatar_id + ".svg' alt=''>";
    document.getElementById("planeta").src += '<img class="centered-planeta" src="assets/icons_gerais/pregresso' + userInfo.id_settings + '/Planeta.svg">';
}

window.onload = async function () {
    await getUserInfo();
    console.log(userInfo);

    //TODO verify session -> updatePlanet()
}
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

window.onload = async function () {
    await getUserInfo();
    console.log(userInfo);
}
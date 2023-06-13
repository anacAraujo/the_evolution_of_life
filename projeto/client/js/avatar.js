let allAvatars = {};

async function getAllAvatars() {
    const response = await fetch("../server/avatar/get_all_avatars.php");
    const jsonData = await response.json();

    for (const avatar of jsonData) {
       allAvatars[avatar.id] = avatar; 
    }
     
}

async function editAvatar(avatarId) {

    const data = {
        avatar_id: avatarId
    };

    try {
        const response = await fetch("../server/avatar/sc_edit_avatar.php", {
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

function avatarEventos() {

    document.getElementById("Avatar_fullbody_2").style.display = "none";
    document.getElementById("Avatar_fullbody_3").style.display = "none";

    document.getElementById("Avatar_2").onclick = function () {
        document.getElementById("Avatar_img_selected").style.display = "none";
        document.getElementById("Avatar_fullbody_2").style.display = "block";
    }

    
}

window.onload = async function () {

    await getAllAvatars();
    console.log(allAvatars);
    
    avatarEventos();
}
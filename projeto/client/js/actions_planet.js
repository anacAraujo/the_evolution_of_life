
async function getLandItems() {
    const data = {
        land_id: id
    };
    try {
        const response = await fetch("../server/users/get_land_items.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.code === 'NO_LOGIN') {
            document.location.href = './login.html';
            return;
        }

        console.log("Success:", result);
        return result;
    } catch (error) {
        console.error("Error:", error);
    }
}

function landAction() {
    document.getElementById("land1").onclick = function () {
        let landItems = getLandItems();

    }
}





window.onload = async function () {
    landAction();
}
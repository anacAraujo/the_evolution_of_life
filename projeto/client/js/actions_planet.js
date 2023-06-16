let landItems = {};

async function getLandItems() {
    const response = await fetch("../server/users/get_land_items.php");
    const jsonData = await response.json();

    if (jsonData.code === 'NO_LOGIN') {
        document.location.href = './login.html';
        return;
    }

    for (const item of jsonData) {
        landItems[item.land_id] = item;
    }
}

async function putItemOnLand(landID, itemSymbol) {
    const data = {
        land_id: landID,
        item_symbol: itemSymbol
    };
    try {
        const response = await fetch("../server/land/put_item_on_land.php", {
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


function setEvents() {
    // Set land on click events
    const lands = document.querySelectorAll(".land");
    for (let i = 0; i < lands.length; i++) {
        const land = lands[i];
        land.onclick = addItem;
    }
}

function addItem() {
    const landId = this.id;
    console.log("itemID: ", itemID);

    const landItem = landItems[landId];

    // Land is empty, put water
    if (!landItem) {
        putItemOnLand(landId, 'H2O');
    }

}

async function fillLandMap() {
    await getLandItems();
    console.log(landItems);

    for (const land of Object.values(landItems)) {
        if (land.symbol === "H2O") {
            document.getElementById(land.land_id).style.background = "radial-gradient(circle, #9ED4F4, #86A7DA)";
        }

        // TODO if has Micro, display it
    }
}

window.onload = async function () {

    await fillLandMap();

    setEvents();
}

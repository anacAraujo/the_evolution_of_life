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

async function doFormulaOrganism(landID, formula) {
    const data = {
        land_id: landID,
        formula_name: formula
    };

    try {
        const response = await fetch("../server/land/microorganism_action.php", {
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
        land.onclick = onLandClick;
    }
}

async function onLandClick() {
    const landId = this.id;
    console.log("landId: ", landId);

    const landItem = landItems[landId];
    console.log("landItem: ", landItem);

    // Land is empty, put water
    if (!landItem) {
        document.getElementById("planeta_interior_balde").style.display = "block";
        document.getElementById("planeta_interior_balde").onclick = async function () {
            await putItemOnLand(landId, 'H2O');
            await fillLandMap();
            document.getElementById("planeta_interior_balde").style.display = "none";
        }
    }

    if (landItem.item_id === 3) {
        document.getElementById("planeta_interior_orgnism").style.display = "block";
        document.getElementById("planeta_interior_orgnism").onclick = async function () {
            await putItemOnLand(landId, 'Organism');
            await fillLandMap();
            document.getElementById("planeta_interior_orgnism").style.display = "none";
        }
    }

    if (landItem.item_id === 11) {
        document.getElementById("planeta_interior_reproducao").style.display = "block";
        document.getElementById("planeta_interior_fotossintese").style.display = "block";

        // reprodução
        document.getElementById("planeta_interior_reproducao").onclick = async function () {
            await doFormulaOrganism(landId, "reproducao");
            await fillLandMap();
            document.getElementById("planeta_interior_reproducao").style.display = "none";
            document.getElementById("planeta_interior_fotossintese").style.display = "none";
        }

        //fotossintese
        document.getElementById("planeta_interior_fotossintese").onclick = async function () {
            await doFormulaOrganism(landId, "fotossintese");
            await fillLandMap();
            document.getElementById("planeta_interior_fotossintese").style.display = "none";
            document.getElementById("planeta_interior_reproducao").style.display = "none";
        }
    }
}

async function fillLandMap() {
    await getLandItems();
    console.log(landItems);

    for (const land of Object.values(landItems)) {
        if (land.symbol === "H2O") {
            document.getElementById(land.land_id).style.background = "radial-gradient(circle, #9ED4F4, #86A7DA)";
        }

        if (land.symbol === "Organism") {
            document.getElementById(land.land_id).style.background = "radial-gradient(circle, #9ED4F4, #86A7DA)";
            const divLand = document.getElementById(land.land_id);
            if (divLand) {
                const imageUrl = 'assets/planeta_interior/MicroOrganismo.svg';
                const imgElement = divLand.querySelector("img");
                imgElement.src = imageUrl;
            }
        }
    }
}

window.onload = async function () {

    await fillLandMap();

    setEvents();
}

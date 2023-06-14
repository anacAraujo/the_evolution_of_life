let allSimpleItems = {};
let allComplexItems = {};

async function getAllSimpleItems() {
    const response = await fetch("../server/lab/get_all_simple_items.php");
    const jsonData = await response.json();
    
    for (const simpleItem of jsonData) {
        allSimpleItems[simpleItem.id] = simpleItem;
    }
}

async function getAllComplexItems() {
    const response = await fetch("../server/lab/get_all_complex_items.php");
    const jsonData = await response.json();
    
    for (const complexItem of jsonData) {
        allComplexItems[complexItem.id] = complexItem;
    }
}





function labEventos() {
    
    document.body.style.backgroundImage = 'url("img/fundo_lab.png")';
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundSize = "cover";
    
    
}





window.onload = async function () {

    

    labEventos();
}
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

    document.getElementById("lab_combinar").style.display = "none";
    document.getElementById("lab_sinal_mais").style.display = "none";
    document.getElementById("lab_elemento1").style.display = "none";
    document.getElementById("lab_elemento2").style.display = "none";
    document.getElementById("lab_decompoem").style.display = "none";
    document.getElementById("lab_elemento3").style.display = "none";

    
    document.getElementById("lab_criar").onclick = function () {
        document.getElementById("lab_alien").style.display = "none";
        document.getElementById("lab_texto").style.display = "none";
        document.getElementById("lab_criar").style.display = "none";
        document.getElementById("lab_decompor").style.display = "none";
        document.getElementById("lab_combinar").style.display = "block";
        document.getElementById("lab_sinal_mais").style.display = "block";
        document.getElementById("lab_elemento1").style.display = "block";
        document.getElementById("lab_elemento2").style.display = "block";
    }
    
    document.getElementById("lab_decompor").onclick = function () {
        document.getElementById("lab_alien").style.display = "none";
        document.getElementById("lab_texto").style.display = "none";
        document.getElementById("lab_criar").style.display = "none";
        document.getElementById("lab_decompor").style.display = "none";
        document.getElementById("lab_decompoem").style.display = "block";
        document.getElementById("lab_elemento3").style.display = "block";
    }
    
}





window.onload = async function () {

    labEventos();
}
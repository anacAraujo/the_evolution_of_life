<?php
session_start();

if(isset($_SESSION['created_element1']) && $_SESSION['created_element1']!="") {

    $element1=$_SESSION['created_element1'];
}

if(isset($_SESSION['created_element2']) && $_SESSION['created_element2']!="") {

    $element2=$_SESSION['created_element2'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="keywords" content="atmosphere, chemical elements, planet earth">
    <meta name="description" content="make Earth's early atmosphere habitable">
    <meta name="author" content="Ana Araújo, João Oliveira, Leonardo Bastos, Tomás Sousa">
    <title>The Evolution of Life</title>
    <link rel="icon" type="image/x-icon" href="assets/icons_gerais/progresso3/Planeta.svg">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body class="container lab_container">
<a href="index.html" class="ps-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
        class="bi bi-arrow-left mt-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
    </svg>
    </a>
    <!-- lab -->
    <div class="lab">
        <div class="row">
            <div class="lab_decompor_final text-center">
                <p class="text-dark text-center fs-6 lab_nome_elemento pt-2"><?=$element1?></p>
                <img class="pb-3" src="assets/lab//icons_elementos/<?=$element1?>.svg">
            </div> 
            <div class="lab_decompor_final lab_decompor_final2 text-center">
                <p class="text-dark text-center fs-6 lab_nome_elemento pt-2"><?=$element2?></p>
                <img class="pb-3" src="assets/lab//icons_elementos/<?=$element2?>.svg">
            </div> 
        </div>
    </div>
</body>
</html>
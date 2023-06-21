<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="keywords" content="atmosphere, chemical elements, planet earth">
    <meta name="description" content="make Earth's early atmosphere habitable">
    <meta name="author" content="Ana Araújo, João Oliveira, Leonardo Bastos, Tomás Sousa">
    <title>The Evolution of Life</title>
    <link rel="icon" type="image/x-icon" href="assets/icons_gerais/progresso3/Planeta.svg">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <script src="js/lab.js"></script>
    
</head>

<body class="container ">

<a href="index.html" class="ps-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left mt-2 planeta_quantidade_elementos_seta" id="go_back_to_index" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
        </a>
    <!-- lab -->
    <div class="lab">
        <div class="row">
            <div>
                <img id="lab_alien" src="assets/lab/alien.svg" alt="">
            </div>
            <div>
                <img id="lab_texto" src="assets/lab/texto.svg" alt="">
            </div>

            <!--criar elementos-->
            <div id="lab_criar" class="lab_button_criar lab_button_div_out w-0">
                <a href="cp_add_elements.php?lab_action=0" class="lab_button_div_in"><span class="lab_button_span">Criar</span></a>
            </div>

            <!--decompor elementos-->
            <div id="lab_decompor" class="lab_button_decompor lab_button_div_out">
            <a href="cp_decompose_elements.php?lab_action=1" class="lab_button_div_in"><span class="lab_button_span">Decompor</span></a>
            </div>
        </div>
    </div>

</body>

</html>
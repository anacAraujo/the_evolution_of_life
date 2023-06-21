<?php
session_start();

//VAI BUSCAR O ID DO UTILIZADOR
if (isset($_SESSION['id']) && $_SESSION['id'] != "") {

    $user_id = $_SESSION['id'];
}
//FAZ LIGAÇÃO À BASE DE DADOS
include_once "../server/connections/connection.php";

//CRIA A LIGAÇÃO
$local_link = new_db_connection();

//CRIA O ARRAY QUE VAI BUSCAR OS ELEMENTOS ATUAIS
$current_elements = array();

//INICIA O STATEMENT
$stmt_get_current = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query_get_current = "SELECT items.name,qty FROM planets_items_inventory INNER JOIN items ON items.id=item_id WHERE planets_user_id=$user_id AND items.name <> 'Microorganismo' AND items.name <> 'Semente'";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_get_current, $query_get_current)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt_get_current, $chave, $valor);

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_get_current)) {

        //FAZ FETCH DOS DADOS
        while (mysqli_stmt_fetch($stmt_get_current)) {


            //MANDA PARA O ARRAY
            $current_elements[$chave] = $valor;
        }
    } else {
        echo "Error" . mysqli_error($local_link);
    }
} else {
    echo "Error" . mysqli_error($local_link);
}

mysqli_stmt_close($stmt_get_current);



//CRIA O ARRAY QUE VAI BUSCAR OS ELEMENTOS ATUAIS
$goal_elements = array();

//INICIA O STATEMENT
$stmt_get_goal = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query_get_goal = "SELECT items.name,goal FROM items WHERE items.name <> 'Microorganismo' AND items.name <> 'Semente'";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_get_goal, $query_get_goal)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt_get_goal, $chave, $valor);

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_get_goal)) {

        //FAZ FETCH DOS DADOS
        while (mysqli_stmt_fetch($stmt_get_goal)) {


            //MANDA PARA O ARRAY
            $goal_elements[$chave] = $valor;
        }
    } else {
        echo "Error" . mysqli_error($local_link);
    }
} else {
    echo "Error" . mysqli_error($local_link);
}

mysqli_stmt_close($stmt_get_goal);

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

    <script src="js/particles/particles.js"></script>
    <script src="js/particles/app.js"></script>

    <script src="js/main.js"></script>
    <script src="js/actions_planet.js"></script>
</head>

<body class="container">
    <!--Div que mostra as quantidades dos elementos -->
    <div>
        <a href="index.html">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left mt-2 planeta_quantidade_elementos_seta" id="go_back_to_index" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
        </a>
        <h1 class="planeta_quantidade_elementos_titulo mt-1">Atmosfera Atual</h1>
        <h1 class=" planeta_quantidade_elementos_titulo mt-1" id="atual_atmosfera"> Atual</h1>
        <h1 class="planeta_quantidade_elementos_titulo mt-1" id="divisor_atmosfera"> |</h1>
        <h1 class="planeta_quantidade_elementos_titulo mt-1" id="objetivo_atmosfera"> Objetivo</h1>
        <ul class="planeta_quantidade_elementos_ul mt-1">

            <?php
            //PERCORRER OS ARRAY
            foreach ($current_elements as $key => $value) {

                echo "<li class='m-1'>$key</li>";
            }


            ?>


        </ul>
        <ul class="planeta_quantidade_elementos_ul mt-1 lista_elementos1">

            <?php
            //PERCORRER OS ARRAY
            foreach ($goal_elements as $key => $value) {

                echo "<li class='mt-1 ms-5 ps-5'>$value</li>";
            }
            ?>
        </ul>

        <ul class="planeta_quantidade_elementos_ul mt-1 lista_elementos_divisoria">
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>
            <li class="mt-1 ms-5 ps-5"> | </li>

        </ul>
        <ul class="planeta_quantidade_elementos_ul mt-1 lista_elementos2">

            <?php
            //PERCORRER OS ARRAY
            foreach ($goal_elements as $key2 => $value2) {

                echo "<li class='mt-1 ms-5 ps-5'>$value2</li>";
            }

            ?>
        </ul>

        <div>
            <img id="planeta" class="right-planeta" src="">
            <div id="progress_bar_out" class="progress my-5 barraprogRight" style="width: 25%">
                <div id="progress_bar_in" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                </div>
            </div>
        </div>

    </div>

</body>

</html>
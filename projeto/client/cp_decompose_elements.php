<?php

session_start();

//FAZ LIGAÇÃO À BASE DE DADOS
include_once "../server/connections/connection.php";

//CRIA A LIGAÇÃO
$local_link = new_db_connection();


//VAI BUSCAR O ID DO UTILIZADOR
if (isset($_SESSION['id']) && $_SESSION['id'] != "") {

    $user_id = $_SESSION['id'];
}

//SE VIER DEFINIDA A AÇÕA DO SIDE
if (isset($_GET['lab_action']) && $_GET['lab_action'] != "") {

    $action = htmlspecialchars($_GET['lab_action']);

    //INICIA O STATEMENT QUE VAI BUSCAR OS ELEMENTOS PARA MUDAR
    $stmt = mysqli_stmt_init($local_link);

    //DEFINE A QUERY
    $query = "SELECT items.id, items.name, symbol, goal, qnt_elements_default, side
    FROM items
    INNER JOIN formula_itens
    ON items_id=items.id 
    INNER JOIN planets_items_inventory ON item_id=items.id
    INNER JOIN formulas ON formulas.id = formula_id
    WHERE planets_user_id=$user_id AND side = $action AND planets_items_inventory.qty > 0 AND formula_location_id=1 LIMIT 10";

    //CRIA O ARRAY QUE OS VAI GUARDAR
    $elements = array();

    //PREPARA O STATEMENT PARA COMPOR ELEMENTOS
    if (mysqli_stmt_prepare($stmt, $query)) {

        //DÁ BIND DOS RESULTADOS
        mysqli_stmt_bind_result($stmt, $element_id, $element_name, $element_symbol, $element_goal, $element_qnt_default, $element_side);

        //EXECUTA O STATEMENT
        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_store_result($stmt);

            $rows = mysqli_stmt_num_rows($stmt);

            if ($rows > 0) {

                //VAI BUSCAR OS DADOS
                while (mysqli_stmt_fetch($stmt)) {

                    $elementos_compor[$element_id] = array(
                        "id" => $element_id,
                        "name" => $element_name,
                        "symbol" => $element_symbol,
                        "goal" => $element_goal,
                        "qnt_items_default" => $element_qnt_default,
                        "side" => $element_side
                    );
                }

                //FECHA O STATEMENT
                mysqli_stmt_close($stmt);
            }
            //SE TIVER 0 DADOS
            else {

                //MENSAGEM DE NÃO TER ITEMS PARA AÇÃO
                echo "<div class='row w-100'>
            <div class='col-12'>
            <p class='text-center fw-bold pt-2 w-100 ps-5'>Os items que tentou selecionar não se encontram disponíveis.</p>
            </div></div>";
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
    }
    //SE DER ERRO NA PREPARAÇÃO DE UM STATEMENT
    else {
        echo "Error" . mysqli_error($local_link);
    }
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
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left mt-2 planeta_quantidade_elementos_seta" id="go_back_to_index" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
        </a>
    <!-- lab -->
    <div class="lab">
        <div class="row">
            <form method='post' action='../server/lab/sc_separate_elements.php' class='row'>
                <div>
                    <button class="btn" type="submit">
                        <img id="lab_decompoem" class="lab_mensagem" src="assets/lab/decompoem_elemento.svg" alt="">
                    </button>
                </div>

                <select class="element_drop lab_button_elementos lab_button_elemento3 fs-6" name="Elemento3">
                    <option value="" disabled selected></option>

                    <?php

                    //PERCORRE O ARRAY DOS ELEMENTOS
                    foreach ($elementos_compor as $key => $value) {

                        echo '<option class="text-center" value=' . $elementos_compor[$key]["symbol"] . '>' . $elementos_compor[$key]["symbol"] . '</option>';
                    }

                    echo "</select>
                </div>
            </form>";

                    if (isset($_GET['error']) && $_GET['error'] != "") {

                        if ($_GET['error'] == "empty") {

                            echo "<p class='text-center fw-bold'>Escolha o símbolo antes de os decompôr!</p>";
                        } else if ($_GET['error'] == "qty") {

                            echo "<p class='text-center fw-bold'>Não possui itens suficientes!</p>";
                        } else if ($_GET['error'] == "formula") {
                            echo "<p class='text-center fw-bold'>Não é possível decompôr esse elemento!</p>";
                        }
                    }
                    ?>

        </div>
    </div>

</body>
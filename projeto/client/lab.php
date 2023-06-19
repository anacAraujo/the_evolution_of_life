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

<body class="container">
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
            <div id="lab_criar" class="lab_button_criar lab_button_div_out">
                <div class="lab_button_div_in"></div><span class="lab_button_span">Criar</span>
            </div>
            <div>
                <img id="lab_combinar" class="lab_mensagem" src="assets/lab/combina_elementos.svg" alt="">
            </div>
            <div>
                <img id="lab_sinal_mais" src="assets/lab/sinal_mais.svg" alt="">
            </div>
            <div id="lab_elemento1" class="lab_button_elementos"></div>
            <div id="lab_elemento2" class="lab_button_elementos lab_button_elemento2"></div>

            <!--decompor elementos-->
            <div id="lab_decompor" class="lab_button_decompor lab_button_div_out">
                <div class="lab_button_div_in"></div><span  class="lab_button_span">Decompor</span>
            </div>
            <div>
                <img id="lab_decompoem" class="lab_mensagem" src="assets/lab/decompoem_elemento.svg" alt="">
            </div>
            <div id="lab_elemento3" class="lab_button_elementos lab_button_elemento3"></div>

            <!--div que tem os elementos-->
            <div>
                <div id="lab_escolha" class="lab_escolha_elementos"></div>
            </div>

        </div>
    </div>

<?php
//FAZ LIGAÇÃO À BASE DE DADOS
include_once "../server/connections/connection.php";

//CRIA A LIGAÇÃO
$local_link= new_db_connection(); 

//INICIA O STATEMENT QUE VAI BUSCAR OS ELEMENTOS SIMPLES
$stmt_get_simple_elements=mysqli_stmt_init($local_link);

//CRIA A QUERY
$query_get_simple_elements="SELECT *
                            FROM items
                            INNER JOIN formula_itens
                            WHERE side = 0";

//CRIA O ARRAY QUE OS VAI GUARDAR
$simple_elements=array();

//PREPARA O STATEMENT
if(mysqli_stmt_prepare($stmt_get_simple_elements,$query_get_simple_elements)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt_get_simple_elements,$simple_element_id, $simple_element_name, $simple_element_symbol, $simple_element_goal, $simple_element_qnt_default, $simple_element_side);

    //EXECUTA O STATEMENT
    if(mysqli_stmt_execute($stmt_get_simple_elements)) {


        //VAI BUSCAR OS DADOS
        while(mysqli_stmt_fetch($stmt_get_simple_elements)) {

            //MANDA PARA O ARRAY
            $simple_elements[$simple_element_id]++;

            //MOSTRA 
            echo "";

        }
    }
    else {
        echo "Error" . mysqli_error($local_link);
    }
}
else {
    echo "Error" . mysqli_error($local_link);
}

//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt_get_simple_elements);


//INICIA O STATEMENT QUE VAI BUSCAR OS ELEMENTOS COMPLEXOS
$stmt_get_complex_elements=mysqli_stmt_init($local_link);

//CRIA A QUERY
$query_get_complex_elements="SELECT *
                            FROM items
                            INNER JOIN formula_itens
                            WHERE side = 0";

//CRIA O ARRAY QUE OS VAI GUARDAR
$complex_elements=array();

//PREPARA O STATEMENT
if(mysqli_stmt_prepare($stmt_get_complex_elements,$query_get_complex_elements)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt_get_complex_elements,$complex_element_id, $complex_element_name, $complex_element_symbol, $complex_element_goal, $complex_element_qnt_default, $complex_element_side);

    //EXECUTA O STATEMENT
    if(mysqli_stmt_execute($stmt_get_complex_elements)) {


        //VAI BUSCAR OS DADOS
        while(mysqli_stmt_fetch($stmt_get_complex_elements)) {

            //MANDA PARA O ARRAY
            $complex_elements[$complex_element_id]++;
        }
        // MOSTRA
        echo "
        <div class='col-3 pt-3 mt-2 text-center'>
            <button class='Avatar_form_button' id='Avatar_current'>
                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
            </button>
        </div>";
    }
    else {
        echo "Error" . mysqli_error($local_link);
    }
}
else {
    echo "Error" . mysqli_error($local_link);
}

//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt_get_simple_elements);














?>

</body>

</html>
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
            <div id="lab_criar" class="lab_button_criar lab_button_div_out w-0">
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
session_start();

if (isset($_GET['tipo_formula'])) {
  $botaoClicado = $_GET['tipo_formula'];

  // Armazene a informação do botão clicado na sessão
  $_SESSION['tipo_formula'] = $botaoClicado;

}

//FAZ LIGAÇÃO À BASE DE DADOS
include_once "../server/connections/connection.php";

//CRIA A LIGAÇÃO
$local_link= new_db_connection(); 

//VAI AO SESSION BUSCAR A AÇÃO QUE PRECISAS DE FAZER
$_SESSION['id']=1;

//VAI BUSCAR O ID DO UTILIZADOR
if(isset($_SESSION['id']) && $_SESSION['id']!="") {

    $user_id=$_SESSION['id'];

 
}

$_SESSION['lab_action']=0;

//SE VIER DEFINIDA A AÇÕA DO SIDE
if(isset($_SESSION['lab_action']) && $_SESSION['lab_action']!="") {

    $action=$_SESSION['lab_action'];

    

    //INICIA O STATEMENT QUE VAI BUSCAR OS ELEMENTOS PARA MUDAR
    $stmt=mysqli_stmt_init($local_link);

    //DEFINE A QUERY
    $query="SELECT items.id, items.name, symbol, goal, qnt_elements_default, side
    FROM items
    INNER JOIN formula_itens
    ON items_id=items.id 
    INNER JOIN planets_items_inventory ON item_id=items.id
    WHERE planets_user_id=$user_id AND side = $action";

    //CRIA O ARRAY QUE OS VAI GUARDAR
    $elements=array();

    //PREPARA O STATEMENT PARA COMPOR ELEMENTOS
    if(mysqli_stmt_prepare($stmt,$query)) {

         //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt,$element_id, $element_name, $element_symbol, $element_goal, $element_qnt_default, $element_side);

     //EXECUTA O STATEMENT
     if(mysqli_stmt_execute($stmt)) {

        mysqli_stmt_store_result($stmt);

        $rows=mysqli_stmt_num_rows($stmt);


        if($rows>0) {

            //SE ELE FOR COMPOR ELEMENTOS
            if($action==0) {

            echo "
            <form method='post' action='../server/lab/sc_join_elements.php' class='row'>";
            }
            //SE FOR DECOMPOR
            else if($action==1) {
                echo "
                <form method='post' action='../server/lab/sc_separate_elements.php' class='row'>";
            }

        //VAI BUSCAR OS DADOS
        while(mysqli_stmt_fetch($stmt)) {

            $elementos_compor[$element_id] = array(
                "id" => $element_id, 
                "name" => $element_name, 
                "symbol" => $element_symbol, 
                "goal" => $element_goal, 
                "qnt_items_default" => $element_qnt_default, 
                "side" => $element_side
            );

            //MOSTRA 
            //echo "<pre>" . print_r($elementos_compor[$element_id],true) . "ID DO ARRAY NUMÉRICO: $element_id". "</pre>";

                echo "<div class='lab_elementos col-3'>
                <input class='lab_elementos_personalizacao pt-3' type='image' name='$element_id' src='assets/lab/icons_elementos/$element_symbol.svg'>
                <p class='lab_nome_elemento pt-2'>$element_name</p>
                </div>";
                
        }
        
        echo "</form>";

        var_dump($_SESSION);

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
    }
    else {
        echo "Error" . mysqli_error($local_link);
    }

    }
    //SE DER ERRO NA PREPARAÇÃO DE UM STATEMENT
    else {
        echo "Error" . mysqli_error($local_link);
    }

    }

?>

</body>

</html>
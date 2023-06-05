<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "components/cp_head.php";


//ANTES DE TUDO VAI BUSCAR A AÇÃO QUE VAIS FAZER
if (isset($_GET['action']) && $_GET['action'] != "") {

    //GUARDA NUMA VÁRIÁVEL
    $action = $_GET['action'];

//VAI BUSCAR A TABELA PARA SABER QUE DADOS VAIS MOSTRAR
    if (isset($_GET['table']) && $_GET['table'] != "") {

        //SE ID VIER DEFINIDO
        if (isset($_GET['id']) && $_GET['id'] != "") {

            //GUARDA NA VARIÁVEL
            $id = $_GET['id'];
            $_SESSION['id'] = $id;

        }

        //GUARDA NA VARIÁVEL
        $tabela_query = $_GET['table'];
        //PASSA NO SESSION
        $_SESSION['table'] = $tabela_query;

    } //SE NÃO ESTIVEREM
    else {
        //header("Location:./errors.php?error=noData");

    }

    ?>
    <?php

//TABELAS POSSÍVEIS
    $tabelas = array("avatars", "formulas", "formula_itens", "formula_location", "items", "land", "market_offers", "microorganism_settings", "microorganism_usage", "planets", "planets_items_inventory", "planets_land_items", "profiles", "used_formulas_planet", "users");


//FAZ TANTAS COLUNAS QUANTO NECESSÁRIO
//PREPARA NOVO STATEMENT
    $stmt_columns = mysqli_stmt_init($local_link);

//QUERY
    $query_describe = "SELECT * FROM " . $tabela_query . " LIMIT 0";

//PREPARA O STATEMENT
    if (mysqli_stmt_prepare($stmt_columns, $query_describe)) {


        //EXECUTA O STATEMENT
        if (mysqli_stmt_execute($stmt_columns)) {

            //VAI BUSCAR OS METADADOS PARA CONSEGUIRES ESCREVER OS NOMES DE CAMPOS
            $metadados = mysqli_stmt_result_metadata($stmt_columns);

            //FAZ UM ARRAY PARA GUARDAR OS NOMES
            $nomes_colunas = array();

            //USA OS METADADOS QUE GUARDASTER E VAI BUSCAR OS NOMES DOS CAMPOS
            while ($campo = mysqli_fetch_field($metadados)) {

                //MANDA PRO ARRAY
                $nomes_colunas[] = $campo;
            }

            //VÊ O TAMANHO DO ARRAY
            $num_nomes = count($nomes_colunas);

            //CHAVE PRIMÁRIA DINÂMICA
            $first_col = $nomes_colunas[0]->name;

            //PASSA NO SESSION
            $_SESSION['first_col'] = $first_col;


        } //SE DER ERRO NO EXECUTE
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=execute");

        }
    } //SE DER ERRO NO PREPARE
    else {
        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=prepare");
    }

//FECHA O STATEMENT EM CIMA
    mysqli_stmt_close($stmt_columns);

//INICIA O STATEMENT
    $stmt_data = mysqli_stmt_init($local_link);

//DEFINE A QUERY
    $query_data = "SELECT * FROM $tabela_query WHERE $first_col=$id";

//DEFINE A QUERY
//VÊ SE ESTÁ NO ARRAY DAS TABELAS
    if (in_array($tabela_query, $tabelas, true)) {

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_data, $query_data)) {

            //EXECUTA O STATEMENT
            if (mysqli_stmt_execute($stmt_data)) {
                //GUARDA O NÚMERO DE LINHAS
                $linhas_resultado = mysqli_stmt_store_result($stmt_data);

                //FUNÇÃO QUE POSSIBILITA SABER O NUMERO DE COLUNAS
                $colunas = mysqli_stmt_result_metadata($stmt_data);

                //PROCURA O NÚMERO DE COLUNAS
                $colunas_valor = mysqli_num_fields($colunas);

                //DECLARA O NÚMERO DE VARIÁVEIS QUE VAI PRECISAR PARA OS RESULTADOS
                $num_vars_ = $colunas_valor;

                //CRIA O ARRAY QUE VAI GUARDAR ESSES DADOS
                $results = array();

                //CRIA ESSAS VARIÁVEIS E DEIXA AS VAZIAS
                for ($i = 0; $i < $num_vars_; $i++) {

                    //CRIA VARIÁVEIS DINÂMICAS
                    ${"var" . $i} = "";

                    //INSERE PARA O ARRAY A VARIÁVEL ATUAL
                    $results[$i] = ${"var" . $i};
                }

                //DÁ BIND DOS RESULTADOS DA QUERY, MAS VAI DANDO UNPACK
                mysqli_stmt_bind_result($stmt_data, ...$results);

                //VARIÁVEL QUE CONTROLA A ESCRITA DE DADOS
                $max = max(array_keys($results));

                //VAI BUSCAR CADA RESULTADO
                if (!mysqli_stmt_fetch($stmt_data)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:errors.php?error=fetch");

                }

            } //SE DER ERRO NA EXECUÇÃO
            else {

                //VAI PARA A PÁGINA DE ERROS
                header("Location:errors.php?error=execute");
            }

        } //SE HOUVER ERRO NA PREPARAÇÃO DO STATEMENT
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=prepare");
        }


    } else {
        //VAI PARA ERRO
        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=noTable");


    }
//FECHA O STATEMENT
    mysqli_stmt_close($stmt_data);


//DEFINE A QUERY

} //SE ISSO NÃO VIER DEFINIDO
else {
    //VAI PARA ERRO
    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=noData");
}

?>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!--CHAMA O NAVBAR -->
    <?php
    include_once "components/cp_sidebar.php";
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!--CHAMA O NAVBAR -->
            <?php
            include_once "components/cp_navbar.php";

            //COM BASE NA ACTION
            if ($action == "edit") {

                //ESCREVE QUE VAU EDITAR
                echo "<!-- Begin Page Content -->
            <div class='container-fluid border border-info rounded shadow mt-5 mb-5' style='width: 80%;'>
                <div class='form-header mt-3'>
                    <h2 class='text-center text-dark'>Formulário de Edição de Dados</h2>
                    <p class='text-center'>Preencha o formulário abaixo para editar os dados de
                        da tabela
                        <n class='font-weight-bolder'>$tabela_query</n>
                        .
                    </p>
                </div>
                <form method='post' action='./scripts/sc_edit_data.php'>
                    <div class='form-row mb-5'>
                        <div class='col-12 mb-4 mt-4'>";

                //TABLEAS CUJA CHAVE PRIMÁRIA É ID
                $id_pk = array("avatars", "items", "microorganism_settings", "profiles");

                //PERCORRE O ARRAY
                for ($i = 0; $i < $num_nomes; $i++) {

                    //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                    $nome = $nomes_colunas[$i]->name;


                    //PARTE QUE NÃO PERMITE EDIÇÃO DAS CHAVES PRIMÁRIAS
                    //SE FOR A TABELA AVATARS E A COLUNA FOR ID
                    if (in_array($tabela_query, $id_pk) && $nome == "id") {

                        //ESCREVE
                        //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                        echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";


                        //CONTINUA A PERCORRER O ARRAY
                        for ($fields = 1; $fields < $num_nomes; $fields++) {
                            $i++;

                            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                            $nome = $nomes_colunas[$fields]->name;

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$i] name='$nome'>
                                           </div>";
                        }
                    } //SENÃO SE FOR A TABLE FORMULA_ITENS
                    else if (($tabela_query == "formula_itens")) {

                        if ($nome == "formula_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        }

                        //CONTINUA A PERCORRER O ARRAY
                        for ($fields = 1; $fields < $num_nomes; $fields++) {

                            $i++;
                            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                            $nome = $nomes_colunas[$fields]->name;

                            //ESCONDE OS CAMPOS INALTRÁVEIS
                            if ($nome == "items_id") {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                            } else {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }
                        }
                    } else if ($tabela_query == "formula_location") {

                        if ($nome == "id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } else {
                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 1; $fields < $num_nomes; $fields++) {

                                $i++;
                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;


                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";

                            }
                        }


                    } //SENÃO SE FOR A TABLE formulas
                    else if (($tabela_query == "formulas")) {

                        //CONTINUA A PERCORRER O ARRAY
                        for ($fields = 1; $fields < $num_nomes; $fields++) {

                            $i++;
                            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                            $nome = $nomes_colunas[$fields]->name;
                            //ESCONDE OS CAMPOS INALTRÁVEIS
                            if ($nome == "id") {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";

                            } else if ($nome == "formula_location_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_valores_location = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_valores_location = "SELECT id,name FROM formula_location";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_valores_location, $query_valores_location)) {

                                    $results_location = "";
                                    $id_location = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_valores_location, $id_location, $results_location);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_valores_location)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Localização Fórmula</label><br>";
                                        while (mysqli_stmt_fetch($stmt_valores_location)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <input type='radio' class='form-check-input ml-1' name='$nome' value='$id_location' style='transform: scale(130%)'> <n class='ml-2 pl-3'>$results_location</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</div>";
                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_valores_location);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            } else {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }
                        }
                    } //SENÃO SE FOR A TABLE MICROORGANISM_USAGE
                    else if ($tabela_query == "microorganism_usage") {

                        if ($nome == "planets_land_items_item_id" || $nome == "planets_land_items_user_id" || $nome == "planets_land_items_land_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } //SE FOR EDITÁVEL
                        else {

                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 1; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }
                        }

                    } //SENÃO SE FOR A TABLE PLANETS
                    else if (($tabela_query == "planets_items_inventory")) {

                        //NÃO PERMITAS EDITAR
                        if ($nome == "planets_user_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";

                        } else if ($nome == "item_id") {

                            //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                            $stmt_nome_item_atmosfera = mysqli_stmt_init($local_link);

                            //DEFINE A QUERY QUE PRECISAS
                            $query_nome_item_atmosfera = "SELECT id,name FROM items";

                            //PREPARA O STATEMENT
                            if (mysqli_stmt_prepare($stmt_nome_item_atmosfera, $query_nome_item_atmosfera)) {

                                $results_nome_item_atmosfera = "";
                                $id_nome_item_atmosfera = "";

                                //DÁ BIND DOS RESULTADOS AO ARRAY
                                mysqli_stmt_bind_result($stmt_nome_item_atmosfera, $id_nome_item_atmosfera, $results_nome_item_atmosfera);

                                //EXECUTA O STATEMENT
                                if (mysqli_stmt_execute($stmt_nome_item_atmosfera)) {

                                    //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                    echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Nome do Elemento</label><br>";

                                    //ABRE O MENU DE SELEÇÃO
                                    echo "<select name='$nome' class='form-control form-control-sm'>";
                                    while (mysqli_stmt_fetch($stmt_nome_item_atmosfera)) {

                                        //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                        echo "
                                            <option class='ml-1'  value='$id_nome_item_atmosfera' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_nome_item_atmosfera</n><br>";
                                    }
                                    //FECHA A SECÇÃO
                                    echo "</select>
                                            </div>";

                                    //FECHA O STATEMENT
                                    mysqli_stmt_close($stmt_nome_item_atmosfera);

                                } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=execute");
                                }

                            } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                            else {
                                //VAI PARA A PÁGINA DE ERROS
                                header("Location:errors.php?error=prepare");
                            }

                        } else {
                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 2; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }

                        }


                    } //SENÃO SE FOR A TABLE PLANETS_LAND ITEMS
                    else if (($tabela_query == "planets_land_items")) {

                        if ($nome == "item_id") {
                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } else if ($nome == "user_id") {
                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } else if ($nome == "land_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } else {
                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 3; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }
                        }
                    } //SENÃO SE FOR A TABLE USERS
                    else if (($tabela_query == "users")) {

                        if ($nome != "pwd_hash") {

                            //NÃO PERMITAS EDITAR
                            if ($nome == "id") {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";

                            } else if ($nome == "username") {
                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                            } else if ($nome == "avatar_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_avatars = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_avatars = "SELECT id,path FROM avatars";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_avatars, $query_avatars)) {

                                    $results_avatars = "";
                                    $id_avatars = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_avatars, $id_avatars, $results_avatars);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_avatars)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Ficheiro Avatar</label><br>";

                                        //ABRE O MENU DE SELEÇÃO
                                        echo "<select name='$nome' class='form-control form-control-sm'>";
                                        while (mysqli_stmt_fetch($stmt_avatars)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <option class='ml-1'  value='$id_avatars' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_avatars</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</select>
                                            </div>";

                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_avatars);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            } else if ($nome == "profiles_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_perfil = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_perfil = "SELECT id,type FROM profiles";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_perfil, $query_perfil)) {

                                    $results_perfil = "";
                                    $id_perfil = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_perfil, $id_perfil, $results_perfil);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_perfil)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Cargo</label><br>";

                                        //ABRE O MENU DE SELEÇÃO
                                        echo "<select name='$nome' class='form-control form-control-sm'>";

                                        while (mysqli_stmt_fetch($stmt_perfil)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <option class='ml-1'  value='$id_perfil' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_perfil</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</select>
                                            </div>";

                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_perfil);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            } else if ($nome == "date") {

                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                            }


                            else if($nome=="active"){

                                //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Estado do Utilizador</label><br>";
                                //ABRE O MENU DE SELEÇÃO
                                echo "<select name='$nome' class='form-control form-control-sm'>";

                                //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                echo "<option class='ml-1'  value='0' style='transform: scale(130%); '> <n class='ml-2 pl-3'>Inativo</n><br>
                                            <option class='ml-1'  value='1' style='transform: scale(130%); '> <n class='ml-2 pl-3'>Ativo</n><br>";
                                //FECHA A SECÇÃO
                                echo "</select>
                                            </div>";
                            }

                        }
                    }
                }

                //COLOCA O BOTÃO DE SUBMETER OS DADOS
                echo '<div class="col-12 mb-4 text-center ">
                            <button type="submit" class="btn btn-primary shadow font-weight-bolder">Alterar dados</button>
                        </div>
                         </div>
                    </div>
                </form>
            </div>';
            } //SENÃO SE FOR INSERIR
            else if ($action == "insert") {

            //ESCREVE QUE VAU ADICIONAR
            echo "<!-- Begin Page Content -->
            <div class='container-fluid border border-info rounded shadow mt-5 mb-5' style='width: 80%;'>
                <div class='form-header mt-3'>
                    <h2 class='text-center text-dark'>Formulário de Adição de Registos</h2>
                    <p class='text-center'>Preencha o formulário abaixo para adicionar registo à tabela
                        <n class='font-weight-bolder'>$tabela_query</n>.
                    </p>
                </div>
                <form method='post' action='./scripts/sc_add_data.php'>
                    <div class='form-row mb-5'>
                        <div class='col-12 mb-4 mt-4'>";


            //PERCORRE O ARRAY
            for ($i = 0;
            $i < $num_nomes;
            $i++) {

            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
            $nome = $nomes_colunas[$i]->name;
            if ($tabela_query == "avatars") {
                if ($nome == "id") {

                    //ESCREVE
                    //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                    echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400'  name='$nome' readonly>
                                           </div>";
                } else if ($nome == "path") {

                    //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                    echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow'  name='$nome' placeholder='avatarNUMBER.svg'>
                                           </div>";
                }
            } //SENÃO SE FOR A TABLE FORMULA_ITENS
            else if (($tabela_query == "formula_itens")) {

                if ($nome == "formula_id") {

                    //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                    $stmt_formula_id = mysqli_stmt_init($local_link);

                    //DEFINE A QUERY QUE PRECISAS
                    $query_formula_id = "SELECT formulas.name,formula_id FROM formula_itens INNER JOIN formulas ON formula_id= formulas.id ";

                    //PREPARA O STATEMENT
                    if (mysqli_stmt_prepare($stmt_formula_id, $query_formula_id)) {

                        $nome_formula = "";
                        $formula_id = "";

                        //DÁ BIND DOS RESULTADOS AO ARRAY
                        mysqli_stmt_bind_result($stmt_formula_id, $nome_formula, $formula_id);

                        //EXECUTA O STATEMENT
                        if (mysqli_stmt_execute($stmt_formula_id)) {

                            //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                            echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Fórmula</label><br>";

                            //ABRE O MENU DE SELEÇÃO
                            echo "<select name='$nome' class='form-control form-control-sm'>";

                            while (mysqli_stmt_fetch($stmt_formula_id)) {

                                //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                echo "
                                            <option class='ml-1'  value='$formula_id' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$nome_formula</n><br>";
                            }
                            //FECHA A SECÇÃO
                            echo "</select>
                                            </div>";

                            //FECHA O STATEMENT
                            mysqli_stmt_close($stmt_formula_id);
                        }

                        //CONTINUA A PERCORRER O ARRAY
                        for ($fields = 1; $fields < $num_nomes; $fields++) {

                            $i++;
                            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                            $nome = $nomes_colunas[$fields]->name;

                            //ESCONDE OS CAMPOS INALTRÁVEIS
                            if ($nome == "items_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_items_id = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_items_id = "SELECT items.name,items.id FROM items";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_items_id, $query_items_id)) {

                                    $nome_item = "";
                                    $id_item = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_items_id, $nome_item, $id_item);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_items_id)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Item</label><br>";

                                        //ABRE O MENU DE SELEÇÃO
                                        echo "<select name='$nome' class='form-control form-control-sm'>";

                                        while (mysqli_stmt_fetch($stmt_items_id)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <option class='ml-1'  value='$id_item' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$nome_item</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</select>
                                            </div>";

                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_items_id);
                                    }
                                }

                            } else {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' name='$nome'>
                                           </div>";
                            }
                        }
                    }
                }
                    } else if ($tabela_query == "formula_location") {

                        if ($nome == "id") {

                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' name='$nome' readonly>
                                           </div>";
                        } //SE NÃO FOR ID
                        elseif ($nome != "id") {
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' name='$nome' placeholder='Nome da Localização'>
                                           </div>";

                        }

                    } //SENÃO SE FOR A TABLE formulas
                    else if (($tabela_query == "formulas")) {

                        //CONTINUA A PERCORRER O ARRAY
                        for ($fields = 1; $fields < $num_nomes; $fields++) {

                            $i++;
                            //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                            $nome = $nomes_colunas[$fields]->name;
                            //ESCONDE OS CAMPOS INALTRÁVEIS
                            if ($nome == "id") {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' name='$nome' readonly>
                                           </div>";

                            } else if ($nome == "formula_location_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_valores_location = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_valores_location = "SELECT id,name FROM formula_location";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_valores_location, $query_valores_location)) {

                                    $results_location = "";
                                    $id_location = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_valores_location, $id_location, $results_location);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_valores_location)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Localização Fórmula</label><br>";
                                        while (mysqli_stmt_fetch($stmt_valores_location)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <input type='radio' class='form-check-input ml-1' name='$nome' value='$id_location' style='transform: scale(130%)'> <n class='ml-2 pl-3'>$results_location</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</div>";
                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_valores_location);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            } else {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' placeholder='Nome da Fórmula'  name='$nome'>
                                           </div>";
                            }
                        }
                    } //SE FOR A TABLE ITEMS
                    else if ($tabela_query == "items") {

                        if ($nome == "id") {
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' readonly name='$nome'>
                                           </div>";
                        } else {

                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow' name='$nome'>
                                           </div>";
                        }

                    } //SE FOR A TABLE micrororganism_settings
                    else if ($tabela_query == "microorganism_settings") {

                        if ($nome == "id") {
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' readonly name='$nome'>
                                           </div>";
                        } else {

                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow' name='$nome'>
                                           </div>";
                        }

                    } //SENÃO SE FOR A TABLE MICROORGANISM_USAGE
                    else if ($tabela_query == "microorganism_usage") {

                        if ($nome == "planets_land_items_item_id" || $nome == "planets_land_items_user_id" || $nome == "planets_land_items_land_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' value=$results[$i] name='$nome' readonly>
                                           </div>";
                        } //SE FOR EDITÁVEL
                        else {

                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 1; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$fields] name='$nome'>
                                           </div>";
                            }
                        }

                    } //SENÃO SE FOR A TABLE PLANETS
                    else if (($tabela_query == "planets_items_inventory")) {

                        //NÃO PERMITAS EDITAR
                        if ($nome == "planets_user_id") {

                            //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                            $stmt_planets_id = mysqli_stmt_init($local_link);

                            //DEFINE A QUERY QUE PRECISAS
                            $query_planets_id = "SELECT planets.name,user_id FROM planets";

                            //PREPARA O STATEMENT
                            if (mysqli_stmt_prepare($stmt_planets_id, $query_planets_id)) {

                                $nome_planeta = "";
                                $id_planeta = "";

                                //DÁ BIND DOS RESULTADOS AO ARRAY
                                mysqli_stmt_bind_result($stmt_planets_id, $nome_planeta, $id_planeta);

                                //EXECUTA O STATEMENT
                                if (mysqli_stmt_execute($stmt_planets_id)) {

                                    //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                    echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Planeta</label><br>";

                                    //ABRE O MENU DE SELEÇÃO
                                    echo "<select name='$nome' class='form-control form-control-sm'>";

                                    while (mysqli_stmt_fetch($stmt_planets_id)) {

                                        //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                        echo "
                                            <option class='ml-1'  value='$id_planeta' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$nome_planeta</n><br>";
                                    }
                                    //FECHA A SECÇÃO
                                    echo "</select>
                                            </div>";

                                    //FECHA O STATEMENT
                                    mysqli_stmt_close($stmt_planets_id);
                                }
                            }

                        } else if ($nome == "item_id") {

                            //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                            $stmt_nome_item_atmosfera = mysqli_stmt_init($local_link);

                            //DEFINE A QUERY QUE PRECISAS
                            $query_nome_item_atmosfera = "SELECT id,name FROM items";

                            //PREPARA O STATEMENT
                            if (mysqli_stmt_prepare($stmt_nome_item_atmosfera, $query_nome_item_atmosfera)) {

                                $results_nome_item_atmosfera = "";
                                $id_nome_item_atmosfera = "";

                                //DÁ BIND DOS RESULTADOS AO ARRAY
                                mysqli_stmt_bind_result($stmt_nome_item_atmosfera, $id_nome_item_atmosfera, $results_nome_item_atmosfera);

                                //EXECUTA O STATEMENT
                                if (mysqli_stmt_execute($stmt_nome_item_atmosfera)) {

                                    //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                    echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Nome do Elemento</label><br>";

                                    //ABRE O MENU DE SELEÇÃO
                                    echo "<select name='$nome' class='form-control form-control-sm'>";
                                    while (mysqli_stmt_fetch($stmt_nome_item_atmosfera)) {

                                        //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                        echo "
                                            <option class='ml-1'  value='$id_nome_item_atmosfera' style='transform: scale(130%); '> <n class='ml-2 pl-3' >$results_nome_item_atmosfera</n><br>";
                                    }
                                    //FECHA A SECÇÃO
                                    echo "</select>
                                            </div>";

                                    //FECHA O STATEMENT
                                    mysqli_stmt_close($stmt_nome_item_atmosfera);

                                } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=execute");
                                }

                            } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                            else {
                                //VAI PARA A PÁGINA DE ERROS
                                header("Location:errors.php?error=prepare");
                            }

                        } else {
                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 2; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' placeholder='Valor Numérico' name='$nome'>
                                           </div>";
                            }

                        }


                    } //SENÃO SE FOR A TABLE PLANETS_LAND ITEMS
                    else if (($tabela_query == "planets_land_items")) {

                        if ($nome == "item_id") {
                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' ] name='$nome' readonly>
                                           </div>";
                        } else if ($nome == "user_id") {
                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400'  name='$nome' readonly>
                                           </div>";
                        } else if ($nome == "land_id") {

                            //ESCREVE
                            //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400'  name='$nome' readonly>
                                           </div>";
                        } else {
                            //CONTINUA A PERCORRER O ARRAY
                            for ($fields = 3; $fields < $num_nomes; $fields++) {
                                $i++;

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$fields]->name;

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' placeholder='Quantidade' name='$nome'>
                                           </div>";
                            }
                        }
                    } //SE FOR A TABLE ITEMS
                    else if ($tabela_query == "profiles") {

                        if ($nome == "id") {
                            echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' readonly name='$nome'>
                                           </div>";
                        } else if ($nome == "type") {


//PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                            $stmt_perfil = mysqli_stmt_init($local_link);

                            //DEFINE A QUERY QUE PRECISAS
                            $query_perfil = "SELECT id,type FROM profiles";

                            //PREPARA O STATEMENT
                            if (mysqli_stmt_prepare($stmt_perfil, $query_perfil)) {

                                $results_perfil = "";
                                $id_perfil = "";

                                //DÁ BIND DOS RESULTADOS AO ARRAY
                                mysqli_stmt_bind_result($stmt_perfil, $id_perfil, $results_perfil);

                                //EXECUTA O STATEMENT
                                if (mysqli_stmt_execute($stmt_perfil)) {

                                    //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                    echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Cargo</label><br>";

                                    //ABRE O MENU DE SELEÇÃO
                                    echo "<select name='$nome' class='form-control form-control-sm'>";

                                    while (mysqli_stmt_fetch($stmt_perfil)) {

                                        //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                        echo "
                                            <option class='ml-1'  value='$id_perfil' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_perfil</n><br>";
                                    }
                                    //FECHA A SECÇÃO
                                    echo "</select>
                                            </div>";

                                    //FECHA O STATEMENT
                                    mysqli_stmt_close($stmt_perfil);

                                } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=execute");
                                }

                            } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                            else {
                                //VAI PARA A PÁGINA DE ERROS
                                header("Location:errors.php?error=prepare");
                            }
                        }

                    } //SENÃO SE FOR A TABLE USERS
                    else if (($tabela_query == "users")) {

                        if ($nome != "pwd_hash") {

                            //NÃO PERMITAS EDITAR
                            if ($nome == "id") {

                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400'  name='$nome' readonly>
                                           </div>";

                            } else if ($nome == "username") {
                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' placeholder='Username' name='$nome' readonly>
                                           </div>";
                            } else if ($nome == "avatar_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_avatars = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_avatars = "SELECT id,path FROM avatars";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_avatars, $query_avatars)) {

                                    $results_avatars = "";
                                    $id_avatars = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_avatars, $id_avatars, $results_avatars);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_avatars)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Ficheiro Avatar</label><br>";

                                        //ABRE O MENU DE SELEÇÃO
                                        echo "<select name='$nome' class='form-control form-control-sm'>";
                                        while (mysqli_stmt_fetch($stmt_avatars)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <option class='ml-1'  value='$id_avatars' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_avatars</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</select>
                                            </div>";

                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_avatars);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            } else if ($nome == "profiles_id") {

                                //PREPARA UM STATEMENT PARA IR BUSCAR OS VALORES
                                $stmt_perfil = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY QUE PRECISAS
                                $query_perfil = "SELECT id,type FROM profiles";

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_perfil, $query_perfil)) {

                                    $results_perfil = "";
                                    $id_perfil = "";

                                    //DÁ BIND DOS RESULTADOS AO ARRAY
                                    mysqli_stmt_bind_result($stmt_perfil, $id_perfil, $results_perfil);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_perfil)) {

                                        //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                        echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Cargo</label><br>";

                                        //ABRE O MENU DE SELEÇÃO
                                        echo "<select name='$nome' class='form-control form-control-sm'>";

                                        while (mysqli_stmt_fetch($stmt_perfil)) {

                                            //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                            echo "
                                            <option class='ml-1'  value='$id_perfil' style='transform: scale(130%); '> <n class='ml-2 pl-3'>$results_perfil</n><br>";
                                        }
                                        //FECHA A SECÇÃO
                                        echo "</select>
                                            </div>";

                                        //FECHA O STATEMENT
                                        mysqli_stmt_close($stmt_perfil);

                                    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=execute");
                                    }

                                } //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:errors.php?error=prepare");
                                }

                            }

                            else if ($nome == "date") {

                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow bg-gray-400' name='$nome' readonly>
                                           </div>";
                            }
                            else if($nome=="active"){

                                //ESCREVE A PRIMEIRA PARTE DA SECÇÃO DO FORMULÁRIO
                                echo "<div class='col-12 mb-4 mt-4'>
                                               <label class='text-uppercase font-weight-bolder'>Estado do Utilizador</label><br>";
                                //ABRE O MENU DE SELEÇÃO
                                echo "<select name='$nome' class='form-control form-control-sm'>";

                                //ESCREVE A SECÇÃO DO FORMULÁRIO COM OS VALORES
                                echo "<option class='ml-1'  value='0' style='transform: scale(130%); '> <n class='ml-2 pl-3'>Inativo</n><br>
                                            <option class='ml-1'  value='1' style='transform: scale(130%); '> <n class='ml-2 pl-3'>Ativo</n><br>";
                                //FECHA A SECÇÃO
                                echo "</select>
                                            </div>";
                            }

                        }
                    }
                }

                //COLOCA O BOTÃO DE SUBMETER OS DADOS
                echo '<div class="col-12 mb-4 text-center ">
                            <button type="submit" class="btn btn-primary shadow font-weight-bolder">Inserir dados</button>
                        </div>
                         </div>
                    </div>
                </form>
            </div>';
            }


            //CHAMA O FOOTER
            include_once "components/cp_footer.php";
            ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!--CHAMA O MODAL -->
    <?php
    include_once "components/cp_modal_logout.php";
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>


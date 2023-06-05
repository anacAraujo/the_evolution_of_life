<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "components/cp_head.php";


//ANTES DE TUDO VAI BUSCAR A AÇÃO QUE VAIS FAZER
if(isset($_GET['action']) && $_GET['action']!="") {

    //GUARDA NUMA VÁRIÁVEL
    $action=$_GET['action'];

//VAI BUSCAR A TABELA PARA SABER QUE DADOS VAIS MOSTRAR
if (isset($_GET['table']) && $_GET['table'] != "") {

    //SE ID VIER DEFINIDO
    if(isset($_GET['id']) && $_GET['id'] != "") {

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
        $first_col=$nomes_colunas[0]->name;

        //PASSA NO SESSION
        $_SESSION['first_col']=$first_col;


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

}
//SE ISSO NÃO VIER DEFINIDO
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
            if($action=="edit"){

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

                            //PERCORRE O ARRAY
                            for ($i = 0; $i < $num_nomes; $i++) {

                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                $nome = $nomes_colunas[$i]->name;


                                //ESCREVE
                                //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' value=$results[$i] name='$nome'>
                                           </div>";


                            }

                            //COLOCA O BOTÃO DE SUBMETER OS DADOS
                            echo '<div class="col-12 mb-4 text-center ">
                            <button type="submit" class="btn btn-primary shadow font-weight-bolder">Alterar dados</button>
                        </div>
                         </div>
                    </div>
                </form>
            </div>';
            }

            //SENÃO SE FOR INSERIR
            else if($action=="insert") {

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
                for ($i = 0; $i < $num_nomes; $i++) {

                    //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                    $nome = $nomes_colunas[$i]->name;


                    //ESCREVE
                    //SCRIPT PARA COLOCAR TANTOS CAMPOS QUANTOS DADOS EXISTENTES
                    echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>$nome</label>
                                            <input type='text' class='form-control shadow ' name='$nome'>
                                           </div>";


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


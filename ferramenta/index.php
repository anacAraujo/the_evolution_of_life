<!--CHAMA O HEAD -->
<?php
session_start();
include_once "components/cp_head.php";

//CHAMA O FICHEIRO CONNECTIONS
include_once "connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();
//VAI BUSCAR OS DADOS PARA MOSTRAR NOS CARDS INICIAIS
//INICIA O STATEMENT
$stmt_userNum = mysqli_stmt_init($local_link);


//DEFINE A QUERY
$query_userNum = "SELECT COUNT(id) FROM users WHERE last_login < DATE_SUB(NOW(), INTERVAL 30 DAY);";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_userNum, $query_userNum)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_userNum)) {

        //GUARDA O RESULTADO
        mysqli_stmt_bind_result($stmt_userNum, $num_users);

        //VAI BUSCAR OS DADOS
        if (mysqli_stmt_fetch($stmt_userNum)) {

            //GUARDA NUMA VARIÁVEL PARA DEPOIS ESCREVER
            $write_value = $num_users;
        }
        //ERRO AO IR BUSCAR DADOS
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=fetch");
        }
    }
    //SE DER ERRO NA EXECUÇÃO DO STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }
}
//ERRO DE PREPARAÇÃO DE STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
}

//FECHA O STATEMENT DE CIMA
mysqli_stmt_close($stmt_userNum);

//FAZ O PROCESSO PARA O SEGUNDO CARD
//INICIA O STATEMENT
$stmt_itemNum = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query_itemNum = "SELECT COUNT(id) FROM users WHERE MONTH(users.date) = MONTH(CURRENT_DATE()); ";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_itemNum, $query_itemNum)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_itemNum)) {

        //GUARDA O RESULTADO
        mysqli_stmt_bind_result($stmt_itemNum, $num_items);

        //VAI BUSCAR OS DADOS
        if (mysqli_stmt_fetch($stmt_itemNum)) {

            //GUARDA NUMA VARIÁVEL PARA DEPOIS ESCREVER
            $write_value2 = $num_items;
        }
        //ERRO AO IR BUSCAR DADOS
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=fetch");
        }
    }
    //SE DER ERRO NA EXECUÇÃO DO STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }
}
//ERRO DE PREPARAÇÃO DE STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
}

//FECHA O STATEMENT DE CIMA
mysqli_stmt_close($stmt_itemNum);

//FAZ O PROCESSO PARA O TERCEIRO CARD
//INICIA O STATEMENT
$stmt_progress = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query_progress = "SELECT ROUND((COUNT(CASE WHEN progress = 100 THEN 1 END) / COUNT(*) * 100)) AS percentage
FROM planets
INNER JOIN users ON users.id = planets.user_id
WHERE users.active = 1;";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_progress, $query_progress)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_progress)) {

        //GUARDA O RESULTADO
        mysqli_stmt_bind_result($stmt_progress, $progress);

        //VAI BUSCAR OS DADOS
        if (mysqli_stmt_fetch($stmt_progress)) {

            //GUARDA NUMA VARIÁVEL PARA DEPOIS ESCREVER
            $write_value3 = $progress;
        }
        //ERRO AO IR BUSCAR DADOS
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=fetch");
        }
    }
    //SE DER ERRO NA EXECUÇÃO DO STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }
}
//ERRO DE PREPARAÇÃO DE STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
}

//FECHA O STATEMENT DE CIMA
mysqli_stmt_close($stmt_progress);

//FAZ O PROCESSO PARA O QUARTO CARD
//INICIA O STATEMENT
$stmt_market = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query_market = "SELECT COUNT(id) FROM market_offers
WHERE MONTH(date) = MONTH(CURRENT_DATE())
AND YEAR(date) = YEAR(CURRENT_DATE()); ";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_market, $query_market)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_market)) {

        //GUARDA O RESULTADO
        mysqli_stmt_bind_result($stmt_market, $market);

        //VAI BUSCAR OS DADOS
        if (mysqli_stmt_fetch($stmt_market)) {

            //GUARDA NUMA VARIÁVEL PARA DEPOIS ESCREVER
            $write_value4 = $market;
        }
        //ERRO AO IR BUSCAR DADOS
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=fetch");
        }
    }
    //SE DER ERRO NA EXECUÇÃO DO STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }
}
//ERRO DE PREPARAÇÃO DE STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
}

//FECHA O STATEMENT DE CIMA
mysqli_stmt_close($stmt_market);


//SECÇÃO QUE GERE O GRÁFICO
//INICIA O STATEMENT
$stmt_chart = mysqli_stmt_init($local_link);

//ARRAY QUE VAI GUARDAR O NÚMERO DE USERS POR MÊS
$dados_chart = array();

//VARIÁVEL MÊS COMEÇA A 1
$mes = 0;

//ESCREVE A QUERY
$query_chart = "SELECT COUNT(id) FROM users WHERE MONTH(date) =?";

//PREPARA A STATEMENT
if (mysqli_stmt_prepare($stmt_chart, $query_chart)) {


    //ENQUANTO MÊS FOR DIFERENTE DE 12
    while ($mes != 13) {

        //DEFINE O VALOR DO MÊS COMO PARÂMETRO
        mysqli_stmt_bind_param($stmt_chart, "i", $mes);

        //EXECUTA
        if (mysqli_stmt_execute($stmt_chart)) {

            //DÁ BIND DOS RESULTDOS
            mysqli_stmt_bind_result($stmt_chart, $numUsers);

            //VAI BUSCAR OS DADOS
            while (mysqli_stmt_fetch($stmt_chart)) {

                //MANDA PRO ARRAY
                $dados_chart[$mes] = $numUsers;
            }

            //PASSA PARA O PRÓXIMO MÊS
            $mes++;
        } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
        else {
            //VAI PARA A PÁGINA DE ERROS
            header("Location:erros.php?error=execute");
        }
    }

    //RESET A MÊS
    $mes = 1;

    //EXCLUI A PRIMEIRA COISA
    array_shift($dados_chart);

    //MUDA OS DADOS DO ARRAY DO ARRAY E SEPARA POR VÍRGULAS
    $dados_chart_changed = implode(',', $dados_chart);
} //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
else {
    //VAI PARA A PÁGINA DE ERROS
    header("Location:erros.php?error=prepare");
}
//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt_chart);
mysqli_close($local_link);
?>
<script>
    //ESCREVE AS VARIÁVEIS DO GRÁFICO
    var valores = new Array(<?= $dados_chart_changed; ?>);
</script>

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
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Resumo deste mês</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <a href="user_status.php">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Utilizadores sem login à mais de 1 mês</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $write_value ?> </div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Novos Registos</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $write_value2 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Users ativos com Jogo Concluido
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $write_value3 ?>%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= $write_value3 ?>%" aria-valuenow="<?= $write_value3 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Ofertas Mercado</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $write_value4 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-cart-plus fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <!--CHAMA OS GRÁFICOS -->
                        <?php
                        include_once "components/cp_graficos.php";
                        ?>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!--CHAMA O FOOTER -->
            <?php
            include_once "components/cp_footer.php";
            ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up mt-3"></i>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>


</body>

</html>
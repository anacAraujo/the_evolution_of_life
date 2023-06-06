<?php

//CHAMA O HEAD
include_once "cp_head.php";
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!--CHAMA O NAVBAR -->
        <?php
        include_once "cp_sidebar.php";
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!--CHAMA O NAVBAR -->
                <?php
                include_once "cp_navbar.php";
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Erros</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4 text-center">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">Erro!</h6>
                                    <p class="pt-5 ps-3 pe-3">Pedimos desculpa, não foi possível concretizar o seu
                                        pedido!</p>


                                    <?php
                                    //SE O URL TIVER INDICAÇÃO DO ERRO
                                    if (isset($_GET['error']) && $_GET['error'] != "") {

                                        //GUARDA NUMA VARIÁVEL
                                        $erro = $_GET['error'];

                                        //MENSAGEM COM BASE NO ERRO
                                        switch ($erro) {

                                                //SE FOR ERRO DE PREPARAÇÃO DE STATEMENT
                                            case "prepare":

                                                //ESCREVE
                                                echo '<p class="pt-2 ps-3 pe-3 font-weight-bolder"> Ocorreu um problema na preparação da ação à Base de Dados.</p>';

                                                break;

                                                //SE FOR ERRO DE EXECUÇÃO DE STATEMENT
                                            case "execute":
                                                //ESCREVE
                                                echo '<p class="pt-2 ps-3 pe-3 font-weight-bolder"> Ocorreu um problema na execução.</p>';
                                                break;

                                                //SE FOR ERRO DE FETCH
                                            case "fetch":
                                                //ESCREVE
                                                echo '<p class="pt-2 ps-3 pe-3 font-weight-bolder"> Ocorreu um problema ao recolher os dados.</p>';
                                                break;

                                                //SE A TABELA NÃO EXISTIR
                                            case "noTable":
                                                //ESCREVE
                                                echo '<p class="pt-2 ps-3 pe-3 font-weight-bolder">Procura de tabela inválida!</p>';
                                                break;

                                                //SE NÃO HOUVER DADOS PARA EDITAR
                                            case "noData":
                                                //ESCREVE
                                                echo '<p class="pt-2 ps-3 pe-3 font-weight-bolder">Erro ao recuperar dados</p>';
                                        }
                                    }

                                    ?>
                                    <p class="pt-2 ps-3 pe-3 font-weight-bolder"> Por favor, tente
                                        novamente!</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

            <?php
            include_once "cp_footer.php";
            ?>

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!--CHAMA O MODAL -->
        <?php
        include_once "cp_modal_logout.php";
        ?>

        <!-- Bootstrap core JavaScript-->
        <script src="/vendor/jquery/jquery.min.js"></script>
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
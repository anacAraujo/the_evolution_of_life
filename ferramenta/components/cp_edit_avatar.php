<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "./connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "cp_head.php";

//INICIA UM STATEMENT
$stmt = mysqli_stmt_init($local_link);

//DEFINE A QUERY QUE VAI BUSCAR AS IMAGENS
$query = "SELECT path FROM avatars";

//CRIA O ARRAY QUE AS VAI GUARDAR
$imagens = array();

//CRIA A VARIÁVEL QUE VAI PERCORRER O ARRAY
$i = 1;

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt, $query)) {

    //DÁ BIND DE RESULTADOS (NOME IMAGEM)
    mysqli_stmt_bind_result($stmt, $img);

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt)) {

        //VAI BUSCAR TODOS OS DADOS
        while (mysqli_stmt_fetch($stmt)) {

            // MANDA PRO ARRAY
            $imagens[] = $img;
        }
    } //SE DER ERRO NA EXECUÇÃO
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=execute");
    }
} //SE DER ERRO DE PREPARAÇÃO DO STATEMENT
else {
    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=prepare");
}

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
                <div class="container-fluid border border-info rounded shadow mt-5 mb-5" style="width: 80%;">
                    <div class="form-header mt-3">
                        <h2 class="text-center text-dark">Alterar o avatar</h2>
                        <p class="text-center">Selecione <n class="font-weight-bolder text-dark">uma</n> das imagens para proceder às alterações.
                        </p>
                    </div>
                    <form method="post" action="./scripts/sc_edit_avatar.php">
                        <div class="form-row mb-5">

                            <?php

                            //PERCORRE O ARRAY
                            foreach ($imagens as $key => $value) {

                                //ADICIONA AO FORMULÁRIO
                                echo "<div class='col-1'></div><div class='col-2 mb-4 mt-4 text-center rounded'>
                            <input type='image'  src='img/avatars/form/$value' name='$value'>
                        </div><div class='col-1'></div>";
                            }

                            ?>

                        </div>
                    </form>
                </div>
            </div>
            <?php
            include_once "components/cp_footer.php";
            ?>
        </div>

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
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
    </div>
</body>
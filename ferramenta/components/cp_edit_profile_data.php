<?php
session_start();

//CHAMA O HEAD
include_once "cp_head.php";

//VAI BUSCAR A TABELA PARA SABER QUE DADOS VAIS MOSTRAR
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {

    //GUARDA NA VARIÁVEL
    $username = $_SESSION['username'];
} //SE NÃO ESTIVEREM
else {
    header("Location:./errors.php?error=noData");
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
                        <h2 class="text-center">Formulário de Edição de Dados de Perfil</h2>
                        <p class="text-center">Preencha o formulário abaixo para editar os seus dados.
                        </p>
                    </div>
                    <form method="post" action="./scripts/sc_edit_profile_data.php">
                        <div class="form-row mb-5">
                            <div class="col-12 mb-4 mt-4">

                                <?php

                                //COLOCA OS CAMPOS DO FORMULÁRIO
                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>Username</label>
                                            <input type='text' class='form-control shadow '  name='username' value='$username'> 
                                           </div>";

                                echo "<div class='col-12 mb-4 mt-4'>
                                            <label class='text-uppercase font-weight-bolder'>Password</label>
                                            <input type='password' class='form-control shadow'  name='password'>
                                           </div>";


                                //COLOCA O BOTÃO DE SUBMETER OS DADOS
                                echo '<div class="col-12 mb-4 text-center ">
                            <button type="submit" class="btn btn-primary shadow font-weight-bolder">Alterar dados</button>
                        </div>';

                                ?>
                            </div>


                        </div>
                    </form>
                </div>
                <!--CHAMA O FOOTER -->
                <?php
                include_once "cp_footer.php";


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
        include_once "cp_modal_logout.php";
        ?>

        <!-- Bootstrap core JavaScript-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="../js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>
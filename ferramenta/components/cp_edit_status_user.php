<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "./connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "cp_head.php";

//INICIA UM STATEMENT
$stmt_users_login = mysqli_stmt_init($local_link);

//DEFINE A QUERY QUE VAI BUSCAR OS USERS
$query = "SELECT users.id,username, last_login, active FROM users WHERE last_login < DATE_SUB(NOW(), INTERVAL 30 DAY)";

//CRIA O ARRAY QUE OS VAI GUARDAR
$users = array();

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_users_login, $query)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_users_login)) {

        //DÁ BIND DE RESULTADOS (NOME IMAGEM)
        mysqli_stmt_bind_result($stmt_users_login, $id, $username, $date, $active);

        mysqli_stmt_store_result($stmt_users_login);
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
                        <h2 class="text-center text-dark">Utilizadores sem login há um mês</h2>
                    </div>
                    <form method="post" action="./scripts/sc_update_user_status.php">
                        <div class="form-row mb-5">
                            <div class="col-12">

                            </div>

                            <!-- Tabela com os USERS ativos -->
                            <table>
                                <thead>
                                    <tr class="text-center m-auto">
                                        <th class="pr-3 pl-3 text-primary text-uppercase bg-gray-300">Nome</th>
                                        <th class="pr-3 pl-3 text-primary text-uppercase bg-gray-300">Último Login</th>
                                        <th class="pr-3 pl-3 text-primary text-uppercase bg-gray-300">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    while (mysqli_stmt_fetch($stmt_users_login)) {

                                        if ($active == 1) {
                                            $estado_true = "selected";
                                            $estado_false = "";
                                        } else {
                                            $estado_true = "";
                                            $estado_false = "selected";
                                        }

                                        echo '<tr>';
                                        echo "<td class='text-center'>" . $username . "</td>";
                                        echo "<td class='text-center pr-3 pl-3'>" . $date . "</td>";
                                        echo "<td class='text-center pr-3 pl-3'>";
                                        echo "<select name='active' class='form-control form-control-sm pr-3 pl-3' id='active'>";
                                        echo "<option value='$id' $estado_true>Ativo</option>";
                                        echo "<option value='$id' $estado_false>Inativo</option>";
                                        echo "</select>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="submit" value="Submit" class="mb-3 pr-3 pl-3 text-primary text-uppercase bg-gray-300">
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
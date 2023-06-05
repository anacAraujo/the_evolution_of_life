<?php

//INCLUI O FICHEIRO DE LIGAÇÃO À BASE DE ADOS
include_once("./connections/connection.php");

//LIGA À BASE DE DADOS
$local_link = new_db_connection();

//COMEÇA A SESSÃO
$admin = 0;

//SE A SESSÃO TIVER SIDO INICIADA
//GUARA O USERNAME
if (isset($_SESSION["id_user"])) {

    $user_id = $_SESSION["id_user"];

}
//GUARDA  O PERFIL
if (isset($_SESSION['profile_id']) && $_SESSION['profile_id'] == 1) {

    $admin = 1;
    $_SESSION['admin'] = $admin;
} //SE NÃO FOR ADMIN
else {
    $admin = 0;
    $_SESSION['admin'] = $admin;
}

//INCIA O STATEMET
$stmt = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query = "SELECT username,path FROM users INNER JOIN avatars on avatar_id= avatars.id WHERE users.id=?";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt, $query)) {

    //BIND DE PARÂMETROS
    mysqli_stmt_bind_param($stmt, 'i', $user_id);

    //GUARDA O RESULTADO
    mysqli_stmt_bind_result($stmt, $username, $path);

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_fetch($stmt);
    } //SE DER ERRO NA EXECUÇÃO DO STATEMENT
    else {
        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }

} //SE DER ERRO
else {
    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
}
?>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block mr-3"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                //SE A VARIÁVEL ESTIVER DEFINIDA
                if (isset($username) && $username != "") {

                    //COLOCA A IMAGEM DA PESSOA
                    echo '<span class=" d-none d-lg-inline text-gray-600  mr-4 font-weight-bolder" style="font-size: 0.9rem;">' . $username . '</span>

                    <img class="img-profile rounded-circle"
                     src="img/avatars/navbar/' . $path . '">';
                } //SE NÃO ESTIVER
                else {
                    echo '<span class=" d-none d-lg-inline text-gray-600 mr-4 font-weight-bolder">Entrar</span>
                         <img class="img-profile rounded-circle"
                         src="img/avatars/navbar/default.svg">';
                }
                ?>


            </a>

            <?php
            if (isset($user_id) && $user_id != "") {

                //ESCREVE PARA IR PARA O PERFIL
                echo ' 
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="./profile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Perfil
                </a>';

                //ESCREVE UM ITEM DE LOGOUT
                echo '<div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sair
                </a>
                </div>';
            } //SE NÃO ESTIVER COM LOGIN
            else {

                //ESCREVE UM ITEM DE Login
                echo '<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="./profile.php">
                    <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Login
                </a></div>';
            }

            mysqli_stmt_close($stmt);
            ?>

        </li>
    </ul>

</nav>
<!-- End of Topbar -->

<!-- Bootstrap core JavaScript-->
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="./js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="./vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="./js/demo/chart-area-demo.js"></script>


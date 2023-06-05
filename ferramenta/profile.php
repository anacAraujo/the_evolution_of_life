<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "components/cp_head.php";


//VAI BUSCAR O USER ID
if (isset($_SESSION['id_user']) && $_SESSION['id_user'] != "") {

    //GUARDA EM VARIÁVEL
    $id_user = $_SESSION['id_user'];
}

//PREPARA A QUERY QUE VAI BUSCAR O USERNAME E O NOME DO FICHEIRO DE AVATAR
//INICIA O STATEMENT
$stmt = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query = "SELECT username,path FROM users INNER JOIN avatars ON avatar_id= avatars.id WHERE users.id=$id_user ";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt, $query)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt)) {

        //DÁ BIND DOS RESULTADOS
        mysqli_stmt_bind_result($stmt, $username, $file_avatar);

        //VAI BUSCAR OS DADOS
        if (!mysqli_stmt_fetch($stmt)) {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:errors.php?error=fetch");
        }

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

            if(isset($_GET['error']) && $_GET['error']!="") {

                //GUARDA NUMA VARIÁVEL
                $error=$_GET['error'];

                //COM BASE NO VALOR
                //SE AS PASSWORDS NÃO COINCIDIREM
                if($error=="noMatch") {

                    //ESCREVE
                    echo '<p id="feedback" class="text-center font-weight-bolder " style="font-size: 1.5rem;">As passwords indicadas não coincidem</p>';
                }
                //SENÃO SE FOR DIFERENTE DA ATUAL
                 else if($error=="wrong") {

                     //ESCREVE
                     echo '<p id="feedback" class="text-center font-weight-bolder " style="font-size: 1.5rem;">As passwords que introduziu não coincidem com a antiga </p>';
                 }

            }

            //SE NÃO FOR UM ERRO
            else if(isset($_GET['action']) && $_GET['action'] !="") {

                $action=$_GET['action'];

                //SE TIVER SIDO ATUALIZADO COM SUCESSO
                if($action=="updated") {


                    //ESCREVE
                    echo '<p id="feedback" class="text-center font-weight-bolder " style="font-size: 1.5rem;">Dados atualizados com sucesso.</p>';
                }
            }
            ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="row">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-black-50 font-weight-bolder col-12">Perfil</h1>
                    <p style="font-size: 3vh;" class="mb-4 ml-2">Aqui pode visualizar todos os dados do seu perfil.
                    </p>
                </div>


                <div class="row">
                    <div class="col-lg-1">

                    </div>
                    <div class="col-lg-4 col-sm-12 ">
                        <div class="card shadow" style="width: 18rem;">
                            <?php

                            //COLOCA A IMAGEM
                            echo "<img class='card-img-top rounded m-auto' src='img/avatars/profile/$file_avatar'
                                 alt='Card image cap'>";

                            ?>

                            <div class="card-body text-center">
                                <h5 class="card-title">Bem-vindo</h5>
                                <a href="edit_avatar.php" class="btn btn-primary w-75 text-center shadow">Alterar Avatar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">

                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row border border-dark-4">
                                    <div class="col-md-12 text-center font-weight-bolder fa-1x bg-gray-300">
                                        <p class="pt-3 text-primary">Dados</p>
                                    </div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Username</th>
                                            <th scope="col">Password</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th scope="row" class="font-italic text-dark"><?= $username ?></th>
                                            <td>**********</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <p class="text-dark text-center pt-5">Pretende editar os seus dados?</p>
                                    <button class="btn btn-secondary shadow" id="toggle_modal_psswd"><a
                                                class="text-white text-decoration-none">Editar dados</a></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1">

                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>

        <!--CHAMA O FOOTER -->
        <?php
        include_once "components/cp_footer.php";
        ?>
    </div>

    <!-- End of Main Content -->


    <!-- End of Page Wrapper -->
    <!--CHAMA O MODAL -->
    <?php
    include_once "components/cp_modal_logout.php";
    ?>

    <!--MODAL PARA COLOCAR PASSWORD-->
    <div class="modal" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 shadow bg-gray-300">
                    <h5 class="modal-title text-primary" id="exampleModalLabel">Confirmar dados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="scripts/sc_edit_user_data.php">
                    <div class="modal-body">

                        <p class="font-weight-bolder">Precisamos de confirmar a sua identidade</p>
                        <p>Preencha os campos abaixo</p>


                        <div class="form-group pb-0 ">
                            <label for="password1" class="font-weight-bold">Password</label>
                            <input type="password" class="form-control" id="password1" placeholder="Password" name="pssw">
                        </div>
                        <div class="form-group">
                            <label for="password1" class="font-weight-bold">Confirm Password</label>
                            <input type="password" class="form-control" id="password2" name="pssw_repeat"
                                   placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 d-flex justify-content-center mt-0">
                        <button class="btn btn-secondary mr-3" id="cancel_button">Cancelar</button>
                        <button type="submit" class="btn btn-info">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--SCRIPT RELATIVO AO MODAL PARA MUDAR DADOS-->
    <script>
        //ENCURTA O CAMINHO
        var toggle_modal = document.getElementById("toggle_modal_psswd");
        var modal = document.getElementById("form");
        var modal_close = document.getElementById("modal_close");
        var cancel_button = document.getElementById("cancel_button");

        //AO CLICAR NESSE BOTÃO
        toggle_modal.onclick = function () {
            //MOSTRA O MODAL
            modal.style.display = "block";

        }

        //AO CLICAR NO BOTÃO DE FECHAR
        modal_close.onclick = function () {
            modal.style.display = "none";
        }

        //AO CLICAR NO BOTÃO DE CANCELAR
        cancel_button.onclick = function () {

            //FECHA O MODAL
            modal.style.display = "none";
        }

        //QUANDO CARREGA No ICON
        window.onload = function () {

            //COLOCA A OPACIDADE NO MÁXIMO
            document.getElementById("feedback").style.opacity = 1;

            //CRIA UM TIMER QUE VAI REMOVER OPACIDADE DE X EM X TEMPO
            var timerid = setInterval(function () {

                //SE FOR MAIOR QUE 0
                if (document.getElementById("feedback").style.opacity > 0) {

                    //DIMINUI EM 0.05
                    document.getElementById("feedback").style.opacity -= "0.05";
                    //console.log(document.getElementById("feedback").style.opacity);
                }
                //QUANDO FOR 0
                else if (document.getElementById("feedback").style.opacity == 0) {

                    //PARA O TIMER
                    clearInterval(timerid);

                    //ESCONDE O PARÁGRAFO
                    document.getElementById("feedback").style.display = "none";
                }
            }, 150);
        }


    </script>

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

    <script>
        // VAI BUSCAR OS ELEMENTOS QUE CHAMAM O MODAL
        var openModals = document.getElementsByClassName("open_modal");

        // PERCORRE TODOS OS ELEMENTOS E ATRIBUI A FUNÇÃO DE CLIQUE
        for (var i = 0; i < openModals.length; i++) {
            openModals[i].onclick = function () {
                // MOSTRA O MODAL PARA APAGAR O FILME
                document.getElementById("modal_delete").style.display = "block";
            };
        }

        //COLOCA A FUNCIONAR O BOTÃO DE FECHAR
        document.getElementById("close_modal").onclick = function () {

            //FECHA O MODAL
            document.getElementById("modal_delete").style.display = "none";

        }

        //QUANDO CARREGA No ICON
        window.onload = function () {

            //COLOCA A OPACIDADE NO MÁXIMO
            document.getElementById("feedback").style.opacity = 1;

            //CRIA UM TIMER QUE VAI REMOVER OPACIDADE DE X EM X TEMPO
            var timerid = setInterval(function () {

                //SE FOR MAIOR QUE 0
                if (document.getElementById("feedback").style.opacity > 0) {

                    //DIMINUI EM 0.05
                    document.getElementById("feedback").style.opacity -= "0.05";
                    //console.log(document.getElementById("feedback").style.opacity);
                }
                //QUANDO FOR 0
                else if (document.getElementById("feedback").style.opacity == 0) {

                    //PARA O TIMER
                    clearInterval(timerid);

                    //ESCONDE O PARÁGRAFO
                    document.getElementById("feedback").style.display = "none";
                }
            }, 100);
        }
    </script>

</body>

</html>


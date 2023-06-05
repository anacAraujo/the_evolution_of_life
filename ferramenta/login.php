<!--CHAMA O HEAD -->
<?php
include_once "components/cp_head.php";

//VÊ SE  AQUERY STRING TEM INDICAÇÃO
if (isset($_GET["error"]) && $_GET["error"] != "") {

    //ERRO
    $erro = $_GET["error"];

    //MENSAGEM
    if ($erro == "incorrect") {

        //MENSAGEM
        $message = "As credenciais não estão corretas!";
    }
}


?>

<body class="bg-gradient-primary">

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block" style="background: url('img/login/login.svg');background-position:center;background-size:cover;"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Bem vindo de volta!</h1>
                                </div>
                                <form class="user" action="scripts/sc_login.php" method="post" class="was-validated">
                                    <div class="form-group">
                                        <label for="uname" class="form-label">Username:</label>

                                        <input type="text" class="form-control form-control-user"
                                               id="exampleusername" aria-describedby="username" name="Username"
                                               placeholder="Username..." required>

                                        <div class="valid-feedback">Valid.</div>

                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd" class="form-label">Password:</label>
                                        <input type="password" class="form-control form-control-user"
                                               id="examplePassword" placeholder="Enter Password" name="Password"
                                               required>

                                        <div class="valid-feedback">Valid.</div>

                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Entrar
                                    </button>
                                    <hr>
                                </form>
                                <?php
                                //SE A VARIÁVEL DE MENSAGEM DE ERRO ESTIVER DEFINIDA
                                if (isset($message) && $erro == "incorrect") {
                                    //ESCREVE
                                    echo "<p class='font-weight-bolder text-center text-danger'>$message</p>";
                                }
                                ?>
                                <div class="text-center pt-4">
                                    <a class="small" href="register.php">Registar!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

</body>

</html>
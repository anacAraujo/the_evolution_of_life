<!--CHAMA O HEAD -->
<?php
include_once "components/cp_head.php";

//VÊ SE  AQUERY STRING TEM INDICAÇÃO
if(isset($_GET["psswd"]) && $_GET["psswd"]!="") {

    //ERRO
    $erro=$_GET["psswd"];

    //MENSAGEM
    if($erro=="noMatch") {

        //MENSAGEM
        $message="As passwords introduzidas não correspondem!";
    }
}
else if(isset($_GET["error"]) && $_GET["error"]!="") {

    //ERRO
    $erro=$_GET["error"];

    //MENSAGEM
    if($erro=="registered") {

        //MENSAGEM
        $message="A conta já existe, por favor efetue o";
    }
}

?>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block" style="background: url('img/registo/registo.svg');background-position:center;background-size:cover;"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4 font-weight-bolder">Regista-te!</h1>
                            </div>
                            <form class="user" method="post" action="scripts/sc_registo.php" >
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0 ">
                                        <label for="uname" class="form-label font-weight-bold">Username:</label>
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Username" name="Username" required>
                                        <div class="valid-feedback">Valid.</div>

                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label for="uname" class="form-label font-weight-bold">Password:</label>

                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password" name="Password" required>

                                        <div class="valid-feedback">Valid.</div>

                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                    <div class="col-sm-12 pt-3">
                                        <label for="uname" class="form-label font-weight-bold"> Repita a Password:</label>

                                        <input type="password" class="form-control form-control-user"
                                               id="exampleRepeatPassword" placeholder="Repeat Password" name="Repeat_Password" required>

                                        <div class="valid-feedback">Valid.</div>

                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registar
                                </button>
                                <hr>
                            </form>
                            <hr>

                            <?php
                            //SE A VARIÁVEL DE MENSAGEM DE ERRO ESTIVER DEFINIDA
                            if(isset($message) && $erro=="noMatch") {
                                //ESCREVE
                                echo"<p class='font-weight-bolder text-center text-danger'>$message</p>" ;
                            }
                            //SE FOR DE JA ESTAR REGISTADO
                            else if(isset($message) && $erro=="registered") {
                                //ESCREVE
                                echo"<p class='font-weight-bolder text-center text-danger'>$message <a href='login.php' class='text-decoration-none text-info'>Login</a></p>" ;
                            }
                            ?>
                            <div class="text-center pt-3">
                                <a class="small" href="login.php">Já possui uma conta? Entre!</a>
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
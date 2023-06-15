<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="keywords" content="atmosphere, chemical elements, planet earth">
    <meta name="description" content="make Earth's early atmosphere habitable">
    <meta name="author" content="Ana Araújo, João Oliveira, Leonardo Bastos, Tomás Sousa">
    <title>The Evolution of Life</title>
    <link rel="icon" type="image/x-icon" href="assets/icons_gerais/progresso3/Mundo_Azul_x2C__Verde_e_Vermelho.svg">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <script src="js/particles/particles.js"></script>
    <script src="js/particles/app.js"></script>
</head>

<body class="container">
    <!-- mudança avatar -->
    <div id="Avatar_background" class="row ">

        <!--DIV COM PAINEL DE ESCOLHA-->
        <div class="col-8" id="Avatar_form">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left mt-2" id="Avatar_go_back" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
            <!--Fórmulário de Imagens-->
            <form method="post" action="../server/avatar/update_user_avatar.php">

                <?php
                //FAZ LIGAÇÃO À BASE DE DADOS
                include_once "../server/connections/connection.php";

                //CRIA A LIGAÇÃO
                $local_link = new_db_connection();

                //INICIA O STATEMENT QUE VAI BUSCAR OS AVATARES
                $stmt_get_avatars = mysqli_stmt_init($local_link);

                //CRIA A QUERY
                $query_get_avatars = "SELECT id,path FROM avatars";

                //CRIA O ARRAY QUE OS VAI GUARDAR
                $avatars = array();

                //PREPARA O STATEMENT
                if (mysqli_stmt_prepare($stmt_get_avatars, $query_get_avatars)) {

                    //DÁ BIND DOS RESULTADOS
                    mysqli_stmt_bind_result($stmt_get_avatars, $avatar_id, $avatar_path);

                    //EXECUTA O STATEMENT
                    if (mysqli_stmt_execute($stmt_get_avatars)) {


                        //VAI BUSCAR OS DADOS
                        while (mysqli_stmt_fetch($stmt_get_avatars)) {

                            //MANDA PARA O ARRAY
                            $avatars[$avatar_id] = $avatar_path;
                        }
                    } else {
                        echo "Error" . mysqli_error($local_link);
                    }
                } else {
                    echo "Error" . mysqli_error($local_link);
                }

                //FECHA AS LIGAÇÕES
                mysqli_stmt_close($stmt_get_avatars);

                $_SESSION['id'] = 1;
                //VAI DESCOBRIR QUAL É O AVATAR SELECIONADO
                if (isset($_SESSION['id']) && $_SESSION['id'] != "") {

                    //GUARDA NUMA VARIÁVEL
                    $user_id = $_SESSION['id'];



                    //INICIA O STATEMENT QUE VAI BUSCAR O AVATAR SELECIONADO
                    $stmt_current_avatar = mysqli_stmt_init($local_link);

                    //CRIA A QUERY QUE VAI BUSCAR O ATUAL
                    $query_current_avatar = "SELECT avatar_id FROM users WHERE users.id=$user_id";

                    //PREPARA O STATEMENT
                    if (mysqli_stmt_prepare($stmt_current_avatar, $query_current_avatar)) {

                        //DÁ BIND DOS RESULTADOS
                        mysqli_stmt_bind_result($stmt_current_avatar, $avatar_atual);

                        //EXECUTA O STATEMENT
                        if (!mysqli_stmt_execute($stmt_current_avatar)) {

                            echo "Error" . mysqli_error($local_link);
                        } else {

                            mysqli_stmt_fetch($stmt_current_avatar);

                            //CRIA UMA VARIÁVEL PARA CONTROLAR QUANDO ESCREVE ROW
                            $num_avatars_echo = 0;

                            //CRIA A VARIÁVEL QUE ESCREVE OS AVATARES
                            $avatar_write = 1;

                            echo "<div class='row'>
            <div class='col-1 ms-3'></div>";

                            while ($num_avatars_echo != 3) {

                                //SE O AVATAR QUE VAI ESCREVER FOR O DO USER

                                if ($avatar_write == $avatar_atual) {
                                    echo "
            <div class='col-3 pt-3 mt-2 text-center'>
                <button class='Avatar_form_button' id='Avatar_current'>
                    <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                </button>
            </div>";

                                    $num_avatars_echo++;
                                    $avatar_write++;

                                    //VARIÁVEL A ESCREVER
                                    $fullbody = "Avatar" . $avatar_atual . ".svg";
                                } else {

                                    echo "
                    <div class='col-3 pt-3 mt-2 text-center'>
                        <button class='Avatar_form_button'>
                            <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                        </button>
                    </div>";

                                    $num_avatars_echo++;
                                    $avatar_write++;
                                }
                            }
                            echo "</div>";



                            //DÁ RESET À VARIÁVEL DE CONTROLO
                            $num_avatars_echo = 0;

                            ///DÁ RESET VARIÁVEL QUE ESCREVE OS AVATARES
                            $avatar_write = 1;

                            echo "<div class='row'>
                <div class='col-1 ms-3'></div>";

                            while ($num_avatars_echo != 3) {


                                //SE O AVATAR QUE VAI ESCREVER FOR O DO USER

                                if ($avatar_write == $avatar_atual) {
                                    echo "
                <div class='col-3 pt-3 mt-2 text-center'>
                    <button class='Avatar_form_button' id='Avatar_current'>
                        <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                    </button>
                </div>";

                                    $num_avatars_echo++;
                                    $avatar_write++;

                                    //VARIÁVEL A ESCREVER
                                    $fullbody = "Avatar" . $avatar_atual . ".svg";
                                } else {

                                    echo "
                        <div class='col-3 pt-3 mt-2 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";

                                    $num_avatars_echo++;
                                    $avatar_write++;
                                }
                            }
                            echo "</div>";
                            echo "</form>";
                            echo "</div>";
                        }
                    }

                    //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
                    else {
                        echo "Error" . mysqli_error($local_link);
                    }
                    //FECHA AS LIGAÇÕES
                    mysqli_stmt_close($stmt_current_avatar);
                    mysqli_close($local_link);
                }

                echo "
<!--DIV QUE MOSTRA O AVATAR-->
<div class='col-4 align-bottom text-center'>

    <div class='Avatar_selected'>
        <img src='assets/avatar_fullbody/$fullbody' class='ms-3' id='Avatar_img_selected'>
        <img src='assets/avatar_background/form/platform.svg' class=' ms-2'>
    </div>
</div>";



                ?>

        </div>
</body>

</html>
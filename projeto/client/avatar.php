<?php 

if(isset($_GET['origin']) && $_GET['origin']!="") {

    $origin=$_GET['origin'];

    if($origin=="mercado") {

        $return="mercado.html";

        //MANDA PRO SESSION
    $_SESSION['Avatar_referer']=$return;
    }

    

}
else if( isset($_SESSION['Avatar_referer']) && $_SESSION['Avatar_referer'] !="") {

    $origin=$_SESSION['Avatar_referer'];

    if($origin==strpos($origin,"mercado")) {

        $return="mercado.html";

    }
}
else {

    $return="index.html";
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="keywords" content="atmosphere, chemical elements, planet earth">
    <meta name="description" content="make Earth's early atmosphere habitable">
    <meta name="author" content="Ana Araújo, João Oliveira, Leonardo Bastos, Tomás Sousa">
    <title>The Evolution of Life</title>
    <link rel="icon" type="image/x-icon" href="assets/icons_gerais/progresso3/Mundo_Azul_x2C__Verde_e_Vermelho.svg">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <script src="js/particles/particles.js"></script>
    <script src="js/particles/app.js"></script>
</head>

<body class="container">
<!-- mudança avatar -->
<div id="Avatar_background" class="row ">

<!--DIV COM PAINEL DE ESCOLHA-->
<div class="col-8" id="Avatar_form">
    <a href=<?=$return?> class="ps-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
        class="bi bi-arrow-left planeta_quantidade_elementos_seta" id="Avatar_go_back" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
    </svg>
    </a>
    
    <!--Fórmulário de Imagens-->
    <form method="post" action="../server/avatar/update_user_avatar.php">
        
<?php
//FAZ LIGAÇÃO À BASE DE DADOS
include_once "../server/connections/connection.php";

//CRIA A LIGAÇÃO
$local_link= new_db_connection();

//INICIA O STATEMENT QUE VAI BUSCAR OS AVATARES
$stmt_get_avatars=mysqli_stmt_init($local_link);

$fullbody="Avatar1.svg";

//CRIA A QUERY
$query_get_avatars="SELECT id,path FROM avatars";

//CRIA O ARRAY QUE OS VAI GUARDAR
$avatars=array();

//PREPARA O STATEMENT
if(mysqli_stmt_prepare($stmt_get_avatars,$query_get_avatars)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt_get_avatars,$avatar_id,$avatar_path);

    //EXECUTA O STATEMENT
    if(mysqli_stmt_execute($stmt_get_avatars)) {


        //VAI BUSCAR OS DADOS
        while(mysqli_stmt_fetch($stmt_get_avatars)) {

            //MANDA PARA O ARRAY
            $avatars[$avatar_id]=$avatar_path;

        }
    }
    else {
        echo "Error" . mysqli_error($local_link);
    }


}
else {
    echo "Error" . mysqli_error($local_link);
}

//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt_get_avatars);

session_start();

//VAI DESCOBRIR QUAL É O AVATAR SELECIONADO
if(isset($_SESSION['id']) && $_SESSION['id'] != "") {

    //GUARDA NUMA VARIÁVEL
    $user_id=$_SESSION['id'];

   

    //INICIA O STATEMENT QUE VAI BUSCAR O AVATAR SELECIONADO
    $stmt_current_avatar = mysqli_stmt_init($local_link);

    //CRIA A QUERY QUE VAI BUSCAR O ATUAL
    $query_current_avatar="SELECT avatar_id FROM users WHERE users.id=$user_id";

    //PREPARA O STATEMENT
    if(mysqli_stmt_prepare($stmt_current_avatar,$query_current_avatar)) {

        //DÁ BIND DOS RESULTADOS
        mysqli_stmt_bind_result($stmt_current_avatar,$avatar_atual);

        //EXECUTA O STATEMENT
        if(!mysqli_stmt_execute($stmt_current_avatar)) {

            echo "Error" . mysqli_error($local_link);
        }
        else {

            mysqli_stmt_fetch($stmt_current_avatar);

           if($avatar_atual==1) {

            $fullbody="Avatar1.svg";

           }
           else {
             //VARIÁVEL A ESCREVER
             $fullbody="Avatar".$avatar_atual.".svg";

           }

           mysqli_stmt_close($stmt_current_avatar);

           //VAI BUSCAR O PROGRESSO DA PESSOA
           $stmt_progress=mysqli_stmt_init($local_link);

           //QUERY
           $query_progress="SELECT progress FROM planets WHERE user_id=$user_id";

           //PREPARA O STATEMENT
           if(!mysqli_stmt_prepare($stmt_progress,$query_progress)) {
            echo "Error" . mysqli_error($local_link);
           }
           else {

            //DÁ BIND DOS RESULTADOS
            mysqli_stmt_bind_result($stmt_progress,$progresso);

            //EXECUTA O STATEMENT
            if(!mysqli_stmt_execute($stmt_progress)){
                echo "Error" . mysqli_error($local_link);
            }
            else {
                //VAI BUSCAR OS DADOS
                mysqli_stmt_fetch($stmt_progress);
            }

           }

            //CRIA A VARIÁVEL QUE ESCREVE OS AVATARES
            $avatar_write=2;

            echo "<div class='row'>
            <div class='col-1 ms-3'></div>";

            while($avatar_write !=5) {

                //SE O AVATAR QUE VAI ESCREVER FOR O DO USER
                if($avatar_write==$avatar_atual ) {
                    echo "
            <div class='col-3 pt-4 mt-4 text-center'>
                <button class='Avatar_form_button' id='Avatar_current'>
                    <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                </button>
            </div>";
                    $avatar_write++;
                }

                else {

                    echo "
                    <div class='col-3 pt-4 mt-4 text-center'>
                        <button class='Avatar_form_button'>
                            <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                        </button>
                    </div>";
    
                    $avatar_write++;
            }

            }
            echo "</div>";

                ///DÁ RESET VARIÁVEL QUE ESCREVE OS AVATARES
                $avatar_write=2;

                echo"<div class='row'>
                <div class='col-1 ms-3'></div>";
                if($avatar_write!=5) {

                    if($progresso<40) {

                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1' disabled>
                            </button>
                        </div>
                        <div id='lock_icon'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1 disabled'>
                            </button>
                        </div>
                        <div id='lock_icon2'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1 disabled'>
                            </button>
                        </div>
                        <div id='lock_icon3'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;

                    }
                    else if($progresso>40 && $progresso<60) {

                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center '>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1 disabled'>
                            </button>
                        </div>
                        <div id='lock_icon2'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1 disabled'>
                            </button>
                        </div>
                        <div id='lock_icon3'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;

                    }
                    else if($progresso>60 && $progresso<80) {

                        echo "
                        <div class='col-3 pt-4 mt-3 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";
            
                        $avatar_write++;
                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button locked' disabled>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' class='rounded border-1'>
                            </button>
                        </div>
                        <div id='lock_icon3'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-lock-fill' viewBox='0 0 16 16'>
                        <path d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z'/>
                      </svg></div>";
            
                        $avatar_write++;

                    }
                    else if($progresso>80) {
                        echo "
                        <div class='col-3 pt-4 mt-3 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";

                        $avatar_write++;

                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";

                        $avatar_write++;

                        echo "
                        <div class='col-3 pt-4 mt-2 text-center'>
                            <button class='Avatar_form_button'>
                                <input type='image' src='assets/avatar_upperbody/$avatars[$avatar_write]' name='$avatar_write' class='rounded border-1'>
                            </button>
                        </div>";
                        $avatar_write++;
                    }
                }

                echo "</div>";
                echo "</form>";
                echo "</div>";
            
            
            
        }

    }

    //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
    else  {
        echo "Error" . mysqli_error($local_link);
    }
    //FECHA AS LIGAÇÕES
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


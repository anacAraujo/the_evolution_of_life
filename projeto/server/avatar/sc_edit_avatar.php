<?php
session_start();
// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//VAI AO SESSION BUSCAR O USER ID
if(isset($_SESSION['id_user']) && $_SESSION['id_user']!=""){

    //GUARDA NUMA VARIÁVEL
    $id_user=$_SESSION['id_user'];
}

//GUARDA NUM ARRAY O POST
$dados=$_POST;

//CRIA UM ARRAY ONDE OS VALORES SÃO OS NOMES DOS AVATARES
$keys=array_keys($dados);

//PERCORRE O ARRAY KEYS
foreach ($keys as $key => $value) {

    //GUARDA O VALOR NUMÉRICO DA CHAVE AO TIRAR TODOS OS RESTANTES
    $valor_avatar=preg_replace("/[^0-9]/","",$value);

    //FILTRA O VALOR
    $valor_avatar=htmlspecialchars($valor_avatar);


}

//INICIA O STATEMENT
$stmt=mysqli_stmt_init($local_link);

//DEFINE A QUERY DE MUDAR O AVATAR
$query="UPDATE users SET avatar_id =? WHERE users.id =$id_user";

//PREPARA O STATEMENT
if(mysqli_stmt_prepare($stmt,$query)) {

    //DÁ BIND DO PARÂMETRO
    mysqli_stmt_bind_param($stmt,'i',$valor_avatar);

    //EXECUTA A QUERY
    if(mysqli_stmt_execute($stmt)) {

        //DADOS ATUALIZADOS
        header("Location:../profile.php?action=updated");
    }
    //SE DER ERRO NA EXECUÇÃO
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=execute");
    }


}
//SE DER ERRO NA PREPARAÇÃO DE UM STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=prepare");
}

<?php

//Declara a função
function new_db_connection()
{

    $env = "localhost";
    // Variables for the database connection
    if ($env == "localhost") {
        $hostname = 'localhost';
        $username = "root";
        $password = "";
        $dbname = "life_evo";
    } else {
        $hostname = 'labmm.clients.ua.pt';
        $username = "deca_23_BDTSS_68_dbo";
        $password = "uI7Y2AhW";
        $dbname = "deca_23_BDTSS_68";
    }


//Criar a conexão
    $local_link = mysqli_connect($hostname, $username, $password, $dbname);


//Caso a conexão falhe
    if (!$local_link) {

//Termina o processo
        die("Connection failed: " . mysqli_connect_error());
    }

//Se não falhar
// Define o charset pelas questões de escrita
    mysqli_set_charset($local_link, "utf8");

//ESCREVE PARA MOSTRAR QUE A LIGAÇÃO FOI BEM FEITA
    //echo "<h1 style='color:red'> ligação bem sucedida </h1>";

//Retorno da função é o link
    return $local_link;
}

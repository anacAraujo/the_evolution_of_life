<?php

//Declara a função
function new_db_connection()
{

    $env = "";
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

    // Create connection
    $conn = new mysqli($hostname, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Se não falhar
    // Define o charset pelas questões de escrita
    mysqli_set_charset($conn, "utf8");

    // ESCREVE PARA MOSTRAR QUE A LIGAÇÃO FOI BEM FEITA
    //echo "<h1 style='color:red'> ligação bem sucedida </h1>";

    // Retorno da função é a connection
    return $conn;
}

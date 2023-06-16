<?php 
//REMOVE DO SESSION O ID DO UTILIZADOR
unset($_SESSION['id']);


//VAI PARA O LOGIN
header("Location:../../client/login.html");


?>
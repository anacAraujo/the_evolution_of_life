<?php
session_start();
//INCLUI O FICHEIRO DE CONEXÕES
include_once "../connections/connection.php";

//DEFINE A CONEXÃO
$local_link=new_db_connection();

//VAI AO POST BUSCAR OS DADOS
$element_data=$_POST;

//GUARDA O ID
if (isset($_SESSION['id']) && $_SESSION['id'] != "") {

    //GUARDA NUMA VARIÁVEL
    $id_user = $_SESSION['id'];
}

echo "<pre>" . print_r($element_data, true) . "</pre>";

//ARRAY COM OS POSSÍVEIS RESULTADOS
$results=array("H2O","N2","CO2","CH4","NH3","03");

if(!empty($element_data)) {

    //GUARDA OS ITENS
    $item1=$element_data['Elemento1'];
    $item2=$element_data['Elemento2'];
    
    echo "<pre>" . print_r($results, true) . "</pre>";

    //PERCORRE O ARRAY DOS RESULTADOS POSSÍVEIS
    foreach($results as $value) {

        //VÊ SE ESTÁ NO ARRAY OS DOIS VALORES
        if(strpos($value,$item1) && strpos($value,$item2)) {

            $combine_result=$value;
        }
    }

    //MDAL PARA RESOLVER CONFLITO ENTRE O3 E O2 E O RESTO FAZ-SE SEM QUANTIDADES

    echo $combine_result;

    //SE ELE TEM 
    //RETIRAR QUANTIDADE
    //VER SE  VALOR DO ARRAY CONTÉM ALGUM DOS VALORES DAS CHAVES

}
else {

    header("Location:../../client/cp_add_elements.php?error=empty");
}

/* //CRIA UM ARRAY ONDE OS VALORES SÃO OS NOMES DOS INPUTS DAS IMAGENS QUE É O I
$keys = array_keys($element_data);

//PERCORRE O ARRAY KEYS
foreach ($keys as $key => $value) {

    //GUARDA O VALOR NUMÉRICO DA CHAVE AO TIRAR TODOS OS RESTANTES
    $id_item = preg_replace("/[^0-9]/", "", $value);

    //FILTRA O VALOR
    $id_item = htmlspecialchars($id_item);

}

if(isset($_SESSION['first_item']) && $_SESSION['first_item'] !="") {

    //MANDA O SEGUNDO ITEM
    $_SESSION['second_item']=$id_item;

    //VOLTA PARA LAB
    //header("Location:../../client/lab.php?action=add");
}

else {

    //MANDA PRO SESSION PARA DAR PARA ESCREVER O PRIMEIRO ITEM
    $_SESSION['first_item']=$id_item;


    //header("Location:../../client/lab.php?action=add");
} */


?>
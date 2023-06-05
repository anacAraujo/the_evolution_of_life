<?php

session_start();

// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//SE NO SESSION ESTIVER O A TABELA, COLUNA E ID
if (isset($_SESSION['table']) && $_SESSION['table'] != "" && isset($_SESSION['id']) && $_SESSION['id'] != "") {

    //GUARDA NAS VARIÁVEIS
    $table = $_SESSION['table'];
    $id = $_SESSION['id'];


} //SENÃO VAI PARA A PÁGINA DE ERRO
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=noData");


}

//PEGA NA TABELA E VAI BUSCAR TODAS AS SUAS COLUNAS
$stmt_columns = mysqli_stmt_init($local_link);

//QUERY
$query_columns = "SELECT * FROM " . $table . " LIMIT 0";

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_columns, $query_columns)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_columns)) {

        //VAI BUSCAR OS METADADOS PARA CONSEGUIRES ESCREVER OS NOMES DE CAMPOS
        $metadados = mysqli_stmt_result_metadata($stmt_columns);

        //FAZ UM ARRAY PARA GUARDAR OS NOMES
        $nomes_colunas = array();

        //USA OS METADADOS QUE GUARDASTER E VAI BUSCAR OS NOMES DOS CAMPOS
        while ($campo = mysqli_fetch_field($metadados)) {

            //MANDA PRO ARRAY
            $nomes_colunas[] = $campo;
        }

        mysqli_stmt_close($stmt_columns);

        //VÊ SE O ARRAY QUE RETORNA DO POST TEM ALGUMA COISA IGUAL A UMA COLUNA
        $dados_post = $_POST;

        //REORGANIZA O ARRAY DOS DADOS QUE A PESSOA METEU
        $dados_post_reorganizado = array();

        //PERCORRE O ARRAY
        foreach ($dados_post as $chave => $valor) {

            //COLOCA NA CHAVE A INFO PARA COMPARAR
            $dados_post_reorganizado[$chave] = $valor;
        }

        //echo "<pre>" . print_r($dados_post_reorganizado, true) . "</pre>";

        //echo "<pre>" . print_r($nomes_colunas, true) . "</pre>";

        //DECLARA UM ARRAY ONDE VÃO FICAR OS DADOS QUE A PESSOA COLOCA
        $valores_post=array();

        //PERCORRE O ARRAY QUE TEM OS NOMES DAS COLUNAS
        foreach ($nomes_colunas as $cols) {

            //GUARDA O NOME
            $nomes_coluna = $cols->name;

            //VÊ SE O NOME DA COLUNA QUE ESTÁS A PERCORRER EXISTE DENTRO DO ARRAY QUE FOI REORGANIZADO
            if (isset($dados_post_reorganizado[$nomes_coluna])) {

                //SE EXISTIR
                //VALORES INTRODUZIDO
                $valores_post[] = $dados_post_reorganizado[$nomes_coluna];
            }
        }

        //SE HOUVEREM VALORES A INSERIR
        if(!empty($valores_post)) {

            //COMEÇA O STATEMENT DE INSERÇÃO
            $stmt_insert= mysqli_stmt_init($local_link);

            //CONVERTE O ARRAY EM STRING AO USAR A FUNÇÃO ARRAY MAP
            //USA O ARRAY MAP PARA PERCORRER O ARRAY E GUARDA O VALOR DO CAMPO NAME
            //ESSE VALOR VAI PARA UM NOVO ARRAY SÓ DE STRINGS
            $nomes_colunas_strings = array_map(function ($campo) {

                return $campo->name;

            }, $nomes_colunas);

            //ARRAY EM STRING
            $str_array=implode(',',$nomes_colunas_strings);

            //CONTA O NÚMERO DE COLUNAS PARA SABER QUANTOS ?
            $num_param=count($nomes_colunas);
            $bind_param=str_repeat('?,',$num_param -1) .'?';

            //DEFINE A QUERY AO TORNAR EM STRING OS NOMES DAS COLUNAS DA TABELA QUE ESTÃO NO ARRAY
            $query_insert="INSERT INTO $table($str_array) VALUES($bind_param)";


            //PREPARA O STATEMENT
            if(mysqli_stmt_prepare($stmt_insert,$query_insert)) {

                //DÁ BIND AOS PARÃMETROS QUE VAIS INTRODUZIR AO REPETIRES O NÚMERO DE VEZES QUE VEM DO POST
                $repeticoes=count($valores_post);
                $bind=str_repeat('s',$repeticoes);

                //DÁ ENTÃO BIND AO DECOMPOR O POST
                mysqli_stmt_bind_param($stmt_insert,$bind,...$valores_post);

                //EXECUTA O STATEMENT
                if(mysqli_stmt_execute($stmt_insert)) {

                    //SUCESSO NA INSERÇÃO
                    header("Location:../tables.php?table=$table&action=inserted");

                }
                //SE DER ERRO DE EXECUÇÃO
                else {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");
                }

            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {

                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

            //FECHA O STATEMENT
            mysqli_stmt_close($stmt_insert);

        }
    } //SE DER ERRO A EXECUTAR O STATEMENT
    else {
        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=execute");
    }


} //SE DER ERRO A PREPARAR O STATEMENT
else {
    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=prepare");
}

mysqli_close($local_link);
?>
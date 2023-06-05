<?php

session_start();
var_dump($_POST);
// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//SE O POST TROUXER ALGUMA COISA COM ID
foreach ($_POST as $key => $valor) {

    //SE CONTIVER A PALAVRA ID NA CHAVE
    if (strpos($key, 'formula_id') !== false || strpos($key, 'item_id') !== false || strpos($key, 'planets_user_id') !== false || strpos($key, 'land_id') !== false || strpos($key, 'user_id') !== false) {

        //ID VINDO DO POST
        $id_post=$key;
        break;


    }
}

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

//VAI BUSCAR OS DADOS DA COLUNA QUE PRECISAS E GUARDA NUM ARRAY
//INICIA O STATEMENT
$stmt_results = mysqli_stmt_init($local_link);

//FAZ TUDO ISTO
$query_table = "SELECT * FROM " . $table;

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt_results, $query_table)) {

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt_results)) {

        //GUARDA O NÚMERO DE LINHAS
        $linhas_resultado = mysqli_stmt_store_result($stmt_results);

        //FUNÇÃO QUE POSSIBILITA SABER O NUMERO DE COLUNAS
        $colunas = mysqli_stmt_result_metadata($stmt_results);

        //PROCURA O NÚMERO DE COLUNAS
        $colunas_valor = mysqli_num_fields($colunas);

        //DECLARA O NÚMERO DE VARIÁVEIS QUE VAI PRECISAR PARA OS RESULTADOS
        $num_vars_ = $colunas_valor;

        //CRIA O ARRAY QUE VAI GUARDAR ESSES DADOS
        $results = array();

        //CRIA ESSAS VARIÁVEIS E DEIXA AS VAZIAS
        for ($i = 0; $i < $num_vars_; $i++) {
            ${"var" . $i} = "";

            //INSERE PARA O ARRAY A VARIÁVEL ATUAL
            $results[$i] = ${"var" . $i};
        }

        //DÁ BIND DOS RESULTADOS DA QUERY, MAS VAI DANDO UNPACK
        mysqli_stmt_bind_result($stmt_results, ...$results);

        mysqli_stmt_fetch($stmt_results);
        mysqli_stmt_close($stmt_results);


    } //SE DER ERRO NA EXECUÇÃO DE UM STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:errors.php?error=execute");
    }

} //SE HOUVER ERRO NA PREPARAÇÃO DO STATEMENT
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=prepare");
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

        //PERCORRE O ARRAY QUE TEM OS NOMES DAS COLUNAS
        foreach ($nomes_colunas as $cols) {

            //GUARDA O NOME
            $nomes_coluna = $cols->name;

            //VÊ SE O NOME DA COLUNA QUE ESTÁS A PERCORRER EXISTE DENTRO DO ARRAY QUE FOI REORGANIZADO
            if (isset($dados_post_reorganizado[$nomes_coluna])) {

                //VALOR INTRODUZIDO
                $valor_post = $dados_post_reorganizado[$nomes_coluna];

                //PERCORRE O ARRAY PARA ENCONTRAR O VALOR ATUAL DA COLUNA
                foreach ($results as $key => $dados) {


                    //GUARDA O VALOR ATUAL DA COLUNA EM QUESTÃO
                    $current_value = $dados;



                    //COMPARA O VALOR QUE O USER COLOCOU NO POST COM O VALOR QUE A COLUNA TEM ATUALMENTE
                    if (!empty($valor_post) && $valor_post != $current_value) {

                        echo "$valor_post<hr>$current_value";

                        //SE A VARIÁVEL DO VALOR POST ESTIVER DEFINIDA
                        if (isset($valor_post) && $valor_post != "") {

                            //SE TIVER DADOS GUARDA NA VARIÁVEL O NOME DA COLUNA QUE VAIS PRECISAR PARA A QUERY
                            $col_update = $nomes_coluna;

                            //SE NO POSTO VIER A INFORMAÇÃO DOS CAMPOS OBRIGATÓRIOS
                            if (isset($_POST[$col_update]) && $_POST[$col_update] != "") {

                                //VALOR QUE O UTILIZADOR INTRODUZIU
                                //GUARDA O DADO
                                $dado = htmlspecialchars($_POST[$col_update]);

                                //INICIA O STATEMENT
                                $stmt_update = mysqli_stmt_init($local_link);

                                //DEFINE A QUERY
                                $query_update = "UPDATE $table SET $col_update = ? WHERE $id_post=?";

                                //echo $col_update;

                                echo $query_update;

                                //PREPARA O STATEMENT
                                if (mysqli_stmt_prepare($stmt_update, $query_update)) {

                                    //DÁ BIND DOS PARÂMETROS
                                    mysqli_stmt_bind_param($stmt_update, 'si', $dado, $id);

                                    //EXECUTA O STATEMENT
                                    if (mysqli_stmt_execute($stmt_update)) {

                                        //SUCESSO
                                        header("Location:../tables.php?table=$table&action=edited");
                                    } //ERRO NA EXECUÇÃO
                                    else {
                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:../tables.php?table=$table&action=notEdited");
                                    }
                                } //SE DER ERRO A PREPARAR O STATEMENT
                                else {
                                    //VAI PARA A PÁGINA DE ERROS
                                    header("Location:../errors.php?error=prepare");
                                }
                            } //SE NÃO ESTIVER
                            else {
                                //VAI PARA A PÁGINA DE ERROS
                                header("Location:../errors.php?error=noData");
                            }

                            //FECHA OS STATEMENTS E A LIGAÇÃO
                            mysqli_stmt_close($stmt_update);


                        }

                    }

                }
            }
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
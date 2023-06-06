<?php
session_start();
//CHAMA O FICHEIRO CONNECTIONS
include_once "connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//CHAMA O HEAD
include_once "components/cp_head.php";

//VAI BUSCAR A TABELA PARA SABER QUE DADOS VAIS MOSTRAR
if (isset($_GET['table']) && $_GET['table'] != "") {

    //GUARDA NA VARIÁVEL
    $tabela_query = $_GET['table'];
}

?>
<?php

//TABELAS POSSÍVEIS
$tabelas = array("avatars", "formulas", "formula_itens", "formula_location", "items", "land", "market_offers", "microorganism_settings", "microorganism_usage", "planets", "planets_items_inventory", "planets_land_items", "profiles", "used_formulas_planet", "users");

//INICIA O STATEMENT
$stmt_results = mysqli_stmt_init($local_link);

//DEFINE A QUERY
//VÊ SE ESTÁ NO ARRAY DAS TABELAS
if (in_array($tabela_query, $tabelas, true)) {

    //SUCESSO
    //FAZ TUDO ISTO
    $query_table = "SELECT * FROM " . $tabela_query;

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
} else {
    //VAI PARA ERRO
    //VAI PARA A PÁGINA DE ERROS
    header("Location:errors.php?error=noTable");


}
?>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!--CHAMA O NAVBAR -->
    <?php
    include_once "components/cp_sidebar.php";
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!--CHAMA O NAVBAR -->
            <?php
            include_once "components/cp_navbar.php";

            //SE ESTIVER NA QUERY STRING A INDICAÇÃO DE QUE ALGO FOI OU NÃO APAGADO
            if (isset($_GET['action']) && $_GET['action'] != "") {

                //GUARDA NUMA VARÁVEL
                $action = $_GET['action'];

                switch ($action) {

                    //SE TIVER SIDO APAGADO
                    case "deleted":

                        //ESCREVE
                        echo '<p id="feedback" class="text-center font-weight-bolder" style="font-size: 1.5rem;">Registo apagado com sucesso!</p>';
                        break;

                    //SE NÃO DER PARA APAGAR
                    case "notDeleted":
                        echo '<p id="feedback" class="text-center font-weight-bolder" style="font-size: 1.5rem;">Registo não apagado!</p>';
                        break;

                    //SE TIVER SIDO EDITADO COM SUCESSO
                    case "edited":
                        echo '<p id="feedback" class="text-center font-weight-bolder" style="font-size: 1.5rem;">Registo editado com sucesso!</p>';
                        break;

                    //SE NÃO TIVER SIDO EDITADO
                    case "notEdited":
                        echo '<p id="feedback" class="text-center font-weight-bolder" style="font-size: 1.5rem;">Registo não editado!</p>';
                        break;

                    //SE OS DADOS TIVEREM SIDO ADICIONADOS
                    case "inserted":
                        echo '<p id="feedback" class="text-center font-weight-bolder" style="font-size: 1.5rem;">Registo adicionado!</p>';
                        break;
                }
            }
            ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="row">

                    <div class="col-9">
                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-black-50 font-weight-bolder">Resultados</h1>
                        <p style="font-size: 3vh;" class="mb-4">Estes são todos os dados presentes em
                            <n class="font-weight-bolder"><?= $tabela_query ?></n>
                            .
                        </p>
                    </div>
                </div>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gray-200">
                        <h6 class="m-0 font-weight-bold text-primary text-uppercase"><?= $tabela_query ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>

                                    <?php
                                    //FAZ TANTAS COLUNAS QUANTO NECESSÁRIO
                                    //PREPARA NOVO STATEMENT
                                    $stmt_columns = mysqli_stmt_init($local_link);

                                    //QUERY
                                    $query_describe = "SELECT * FROM " . $tabela_query . " LIMIT 0";

                                    //PREPARA O STATEMENT
                                    if (mysqli_stmt_prepare($stmt_columns, $query_describe)) {


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

                                            //VÊ O TAMANHO DO ARRAY
                                            $num_nomes = count($nomes_colunas);

                                            //PERCORRE O ARRAY
                                            for ($i = 0; $i < $num_nomes; $i++) {

                                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                                $nome = $nomes_colunas[$i]->name;

                                                //GUARDA NA VARIÁVEL NOME A COLUNA QUE PERCORRESTE
                                                $nome_coluna_atual = $nomes_colunas[$i]->name;

                                                //SE O NOME FOR DIFERENTE DE PASSWORD
                                                if ($nome_coluna_atual != 'pwd_hash') {

                                                    //ESCREVE NO HEADER
                                                    echo "<th class='text-uppercase text-center text-primary bg-gray-300'>" . $nome . "</th>";
                                                }
                                            }

                                            //SE FOR ADMIN
                                            if ($_SESSION['admin'] == 1) {


                                                echo '<th class="text-uppercase text-center text-primary bg-gray-300">Editar dados</th>';

                                                if(!$tabela_query=="users") {
                                                    //VALOR A ESCREVER
                                                    echo '<th class="text-uppercase text-center text-primary bg-gray-300">Apagar registos</th>';
                                                }
                                            }


                                        } //ERRO NO EXECUTE STATEMENT
                                        else {

                                            //VAI PARA A PÁGINA DE ERROS
                                            header("Location:errors.php?error=execute");

                                        }
                                    } //ERRO NO PREPARE STATEMENT
                                    else {

                                        //VAI PARA A PÁGINA DE ERROS
                                        header("Location:errors.php?error=prepare");

                                    }


                                    ?>
                                </tr>
                                </thead>
                                <?php

                                //DEPOIS ESCREVE NO FOOTER
                                echo "<tfoot>";

                                //PERCORRE O ARRAY
                                for ($i = 0; $i < $num_nomes; $i++) {


                                    $nome_coluna_atual = $nomes_colunas[$i]->name;

                                    //echo $i;

                                    //SE O NOME FOR DIFERENTE DE PASSWORD
                                    if ($nome_coluna_atual != 'pwd_hash') {

                                        //ESCREVE NO HEADER
                                        echo "<th class='text-uppercase text-center text-primary bg-gray-300'>" . $nome_coluna_atual . "</th>";
                                    }
                                }

                                //QUANDO i FOR IGUAL AO VALOR QUE REGE O CICLO, ESCREVE REGISTAR REGISTOS
                                if ($i == $num_nomes && $_SESSION['admin'] == 1) {

                                    echo '<th class="text-uppercase text-center text-primary bg-gray-300">Editar dados</th>';

                                    if(!$tabela_query=="users") {
                                        //VALOR A ESCREVER
                                        echo '<th class="text-uppercase text-center text-primary bg-gray-300">Apagar registos</th>';
                                    }

                                }

                                echo "</tfoot>";

                                ?>
                                <tbody>
                                <?php
                                //I COMEÇA A 1 PARA ESCREVER LOGO O PRIMEIRO VALOR
                                $i = 0;
                                //VARIÁVEL QUE CONTROLA A ESCRITA DE DADOS
                                $max = max(array_keys($results));

                                //VAI BUSCAR CADA RESULTADO
                                while (mysqli_stmt_fetch($stmt_results)) {


                                    //ESCREVE INÍCIO DE LINHA DE DADOS
                                    echo "<tr>";

                                    //ENQUANTO I FOR DIFERENTE DE MÁXIMO DO ARRAY +1
                                    while ($i != $max + 1) {

                                        //GUARDA O NOME DA COLUNA ATUAL
                                        $nome_coluna_atual = $nomes_colunas[$i]->name;

                                        //SE FOR ADMINISTRADOR
                                        if ($_SESSION['admin'] == 1) {

                                            //SE O NOME FOR DIFERENTE DE PASSWORD
                                            if ($nome_coluna_atual != 'pwd_hash') {

                                                echo "<td class='text-center'>" . $results[$i] . "</td>";

                                            }


                                        } //SE NÃO FOR ADIM
                                        else {

                                            //SE O NOME FOR DIFERENTE DE PASSWORD
                                            if ($nome_coluna_atual != 'pwd_hash') {

                                                echo "<td class='text-center'>" . $results[$i] . "</td>";

                                            }
                                        }

                                        //AUMENTA 1 PARA PASSAR AO DADO SEGUINTE
                                        $i++;
                                    }
                                    //SE FOR ADMIN
                                    if ($_SESSION['admin'] == 1) {

                                        //SE NOME FOR FORMULAS_ITENS
                                        if($tabela_query=="formula_itens") {

                                            //COLOCA O ÍCONE PARA EDITAR com o primeiro e segundo IDs
                                            echo "<td class='text-center'><a href='edit_data.php?table=" . $tabela_query . "&id=" . $results[0] . "&item_id=$results[1]&action=edit' class='text-decoration-none'><i class='fas fa-edit mx-2'></i></a></td>";

                                        }

                                        else if($tabela_query=="planets_items_inventory") {

                                            //COLOCA O ÍCONE PARA EDITAR com o primeiro e segundo IDs
                                            echo "<td class='text-center'><a href='edit_data.php?table=" . $tabela_query . "&id=" . $results[0] . "&item_id=$results[1]&action=edit' class='text-decoration-none'><i class='fas fa-edit mx-2'></i></a></td>";
                                        }
                                        else {

                                            //COLOCA O ÍCONE PARA EDITAR
                                            echo "<td class='text-center'><a href='edit_data.php?table=" . $tabela_query . "&id=" . $results[0] . "&col=" . $nome_coluna_atual . "&action=edit' class='text-decoration-none'><i class='fas fa-edit mx-2'></i></a></td>";
                                        }


                                        if(!$tabela_query=="users") {
                                            //COLOCA O ÍCONE PARA APAGAR
                                            echo "<td class='text-center'><a href='#' class='open_modal text-center'><i class='fa fa-trash'></i></a></td>";
                                        }

                                    }

                                    //RESET A I
                                    $i = 0;
                                    echo '</tr>';
                                }

                                //SE FOR ADMIN
                                if ($_SESSION['admin'] == 1) {

                                    echo '<div class="text-right">
                                    <button class="btn btn-primary mt-0 mb-1 mr-1 shadow"><a href="edit_data.php?action=insert&table=' . $tabela_query . '&id=' . $results[0] . '" class="text-white font-weight-bolder text-decoration-none"><i class="fas fa-plus-circle mr-2"></i>Inserir Dados</a></div>';

                                }


                                //FECHA O STATEMENT E A LIGAÇÃO
                                mysqli_stmt_close($stmt_columns);
                                mysqli_stmt_close($stmt_results);
                                mysqli_close($local_link);
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!--CHAMA O FOOTER -->
        <?php
        include_once "components/cp_footer.php";
        ?>


    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!--CHAMA O MODAL -->
<?php
include_once "components/cp_modal_logout.php";
?>

<!--Modal APAGAR  -->
<div class="pt-5 modal" tabindex="" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header shadow">
                <h5 class="modal-title fw-bold">Apagar dado</h5>
                <a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </a>
            </div>

            <div class="modal-body text-center pb-0 pt-3">
                <p>Tem a certeza que pretende remover este dado?</p>
            </div>
            <div class="modal-footer m-auto">
                <a href="#" class="pe-2">
                    <button type="button" class="btn btn- btn-info shadow" data-bs-dismiss="modal" id="cancelar">
                        Cancelar
                    </button>
                </a>
                <?php
                //DETERMINA O QUE É ESCRITO
                if($tabela_query=="formula_itens") {

                    echo '<a href="scripts/sc_delete_data.php?table=' . $tabela_query . '&id_formula=' . $results[0] . '&items_id=' . $results[1] . '" class="ps-2">'.'<button type="button" class="btn btn-primary shadow"> Apagar
                    </button></a>';


                }
                else if($tabela_query=="formulas") {

                    echo '<a href="scripts/sc_delete_data.php?table=' . $tabela_query . '&id=' . $results[0] . '&formula_location_id=' . $results[1] . '" class="ps-2">'.'<button type="button" class="btn btn-primary shadow"> Apagar
                    </button></a>';
                }
                else {
                    echo '<a href="scripts/sc_delete_data.php?table=' . $tabela_query . '&id=' . $results[0] . '"
                   class="ps-2">
                   <button type="button" class="btn btn-primary shadow"> Apagar
                    </button>
                </a>';
                }

                ?>

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

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

<script>
    // VAI BUSCAR OS ELEMENTOS QUE CHAMAM O MODAL
    var openModals = document.getElementsByClassName("open_modal");

    // PERCORRE TODOS OS ELEMENTOS E ATRIBUI A FUNÇÃO DE CLIQUE
    for (var i = 0; i < openModals.length; i++) {
        openModals[i].onclick = function () {
            // MOSTRA O MODAL PARA APAGAR O FILME
            document.getElementById("modal_delete").style.display = "block";
        };
    }

    //COLOCA A FUNCIONAR O BOTÃO DE FECHAR
    document.getElementById("close_modal").onclick = function () {

        //FECHA O MODAL
        document.getElementById("modal_delete").style.display = "none";

    }

    //COLOCA A FUNCIONAR O BOTÃO DE FECHAR
    document.getElementById("cancelar").onclick = function () {

        //FECHA O MODAL
        document.getElementById("modal_delete").style.display = "none";

    }

    //QUANDO CARREGA No ICON
    window.onload = function () {

        //COLOCA A OPACIDADE NO MÁXIMO
        document.getElementById("feedback").style.opacity = 1;

        //CRIA UM TIMER QUE VAI REMOVER OPACIDADE DE X EM X TEMPO
        var timerid = setInterval(function () {

            //SE FOR MAIOR QUE 0
            if (document.getElementById("feedback").style.opacity > 0) {

                //DIMINUI EM 0.05
                document.getElementById("feedback").style.opacity -= "0.05";
                //console.log(document.getElementById("feedback").style.opacity);
            }
            //QUANDO FOR 0
            else if (document.getElementById("feedback").style.opacity == 0) {

                //PARA O TIMER
                clearInterval(timerid);

                //ESCONDE O PARÁGRAFO
                document.getElementById("feedback").style.display = "none";
            }
        }, 150);
    }
</script>

</body>

</html>


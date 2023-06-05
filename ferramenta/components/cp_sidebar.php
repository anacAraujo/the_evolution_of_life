<?php
//CHAMA O FICHEIRO CONNECTIONS
include_once "./connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();
?>


<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center mt-3 mb-3 pe-1" href="index.php">
        <div class="sidebar-brand-icon ">
            <i class="fas fa-globe"></i>
        </div>
        <div class="sidebar-brand-text ">The Evolution of Life</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link text-center" href="index.php">
            <i class="fas fa-database"></i>
            <span>Homepage</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Informações
    </div>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapsePages"
           aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-table"></i>
            <span>Tabelas na BD</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tabelas:</h6>

                <?php

                //ARRAY DE TABLEAS QUE NÃO DEVE SER ESCRITO
                $unnecessary_table=array("land","market_offers", "microorganism_usage","planets","used_formulas_planet");

                //VAI BUSCAR AS TABELAS
                //COMEÇA O STATEMENT
                $stmt=mysqli_stmt_init($local_link);

                //QUERY QUE VAI BUSCAR OS NOMES DAS TABELAS
                $query="SHOW TABLES";

                //PREPARA O STATEMENT
                if(mysqli_stmt_prepare($stmt,$query)) {

                    //EXECUTA O STATEMENT
                    if(mysqli_stmt_execute($stmt)) {

                        //GUARDA O RESULTADO ATUAL
                        mysqli_stmt_bind_result($stmt,$tabela);

                        //FAZ FETCH DOS DADOS
                        while(mysqli_stmt_fetch($stmt)) {

                            //SE A TABELA FOR UMA DAS QUE DEVE APARECER
                            if(!in_array($tabela, $unnecessary_table)) {

                                //ESCREVE OS DADOS NA PÁGINA
                                echo'<a class="collapse-item" href="tables.php?table='.$tabela.'">'.$tabela.'</a>';
                            }

                        }
                    }
                    //SE DER ERRO NA EXECUÇÃO DE UM STATEMENT
                    else {

                        //VAI PARA A PÁGINA DE ERROS
                        header("Location:errors.php?error=execute");

                    }

                }
                //SE HOUVER ERRO NA PREPARAÇÃO DO STATEMENT
                else {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:errors.php?error=prepare");
                }

                ?>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->
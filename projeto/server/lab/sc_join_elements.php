<?php
session_start();
//INCLUI O FICHEIRO DE CONEXÕES
include_once "../connections/connection.php";

//DEFINE A CONEXÃO
$local_link = new_db_connection();

//VAI AO POST BUSCAR OS DADOS
$element_data = $_POST;

//GUARDA O ID
if (isset($_SESSION['id']) && $_SESSION['id'] != "") {

    //GUARDA NUMA VARIÁVEL
    $id_user = $_SESSION['id'];
}


if (!empty($element_data)) {


    //GUARDA OS ITENS
    $item1 = $element_data['Elemento1'];
    $item2 = $element_data['Elemento2'];


    if (($item1 == "H" && $item2 == "O") || ($item1 == "O" && $item2 == "H")) {

        //FAZER H20
        //VE SE TEM ITENS
        $stmt_h2o = mysqli_stmt_init($local_link);

        //QUERY
        $query_h2o = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Hidrogénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_h2o, $query_h2o)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_h2o, $qty_H);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_h2o)) {

                mysqli_stmt_store_result($stmt_h2o);

                if (!mysqli_stmt_fetch($stmt_h2o)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_h2o);

        //VE SE TEM ITENS
        $stmt_O = mysqli_stmt_init($local_link);

        //QUERY
        $query_O = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Oxigénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_O, $query_O)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_O, $qty_O);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_O)) {

                mysqli_stmt_store_result($stmt_O);

                if (!mysqli_stmt_fetch($stmt_O)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {

            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_O);

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_H >= 2 && $qty_O >= 1) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_h2o = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_h2o = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =1 AND planets_user_id=$id_user 
            THEN qty - 2 WHEN item_id=2 AND planets_user_id=$id_user 
            THEN qty-1 
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_h2o, $query_remove_h2o)) {

                if (mysqli_stmt_execute($stmt_remove_h2o)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "H2O";
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_h2o);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_H >= 2 && $qty_O >= 1) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_h2o = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_h2o = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =1 AND planets_user_id=$id_user 
            THEN qty - 2 WHEN item_id=2 AND planets_user_id=$id_user 
            THEN qty-1 
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_h2o, $query_remove_h2o)) {

                if (mysqli_stmt_execute($stmt_remove_h2o)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "H2O";

                    header("Location:../../client/cp_finish_criar.php");
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_h2o);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_h2o = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_h2o = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=3 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_h2o, $query_add_h2o)) {

            if (mysqli_stmt_execute($stmt_add_h2o)) {

                header("Location:../../client/cp_finish_criar.php");
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_add_h2o);
    } else if (($item1 == "H" && $item2 == "C") || ($item1 == "C" && $item2 == "H")) {
        //FAZER METANO
        //VE SE TEM ITENS
        $stmt_ch4 = mysqli_stmt_init($local_link);

        //QUERY
        $query_ch4 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Hidrogénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_ch4, $query_ch4)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_ch4, $qty_H);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_ch4)) {

                mysqli_stmt_store_result($stmt_ch4);

                if (!mysqli_stmt_fetch($stmt_ch4)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_ch4);

        //VE SE TEM ITENS
        $stmt_C = mysqli_stmt_init($local_link);

        //QUERY
        $query_C = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Carbono'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_C, $query_C)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_C, $qty_C);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_C)) {

                mysqli_stmt_store_result($stmt_C);

                if (!mysqli_stmt_fetch($stmt_C)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {

            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_C);

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_H >= 4 && $qty_C >= 1) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_ch4 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_ch4 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =1 AND planets_user_id=$id_user 
            THEN qty - 4 WHEN item_id=10 AND planets_user_id=$id_user 
            THEN qty-1 
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_ch4, $query_remove_ch4)) {

                if (mysqli_stmt_execute($stmt_remove_ch4)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "CH4";

                    header("Location:../../client/cp_finish_criar.php");
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_ch4);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }

        //ADICIONA OS ELEMENTOS
        $stmt_add_ch4 = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_ch4 = "UPDATE planets_items_inventory 
        SET qty = qty+1 WHERE item_id=6 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_ch4, $query_add_ch4)) {

            if (mysqli_stmt_execute($stmt_add_ch4)) {

                header("Location:../../client/cp_finish_criar.php");
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_add_ch4);
    } else if (($item1 == "H" && $item2 == "N") || ($item1 == "N" && $item2 == "H")) {

        //FAZER AMÓNIO
        //VE SE TEM ITENS
        $stmt_nh4 = mysqli_stmt_init($local_link);

        //QUERY
        $query_nh4 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Hidrogénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_nh4, $query_nh4)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_nh4, $qty_H);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_nh4)) {

                mysqli_stmt_store_result($stmt_nh4);

                if (!mysqli_stmt_fetch($stmt_nh4)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_nh4);

        //VE SE TEM ITENS
        $stmt_N = mysqli_stmt_init($local_link);

        //QUERY
        $query_N = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Nitrogénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_N, $query_N)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_N, $qty_N);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_N)) {

                mysqli_stmt_store_result($stmt_N);

                if (!mysqli_stmt_fetch($stmt_N)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {

            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_N);

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_H >= 4 && $qty_N >= 1) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_nh4 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_nh4 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =1 AND planets_user_id=$id_user 
            THEN qty - 4 WHEN item_id=4 AND planets_user_id=$id_user 
            THEN qty-1 
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_nh4, $query_remove_nh4)) {

                if (mysqli_stmt_execute($stmt_remove_nh4)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "NH3";

                    header("Location:../../client/cp_finish_criar.php");
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_nh4);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }

        //RETIRA OS ELEMENTOS
        $stmt_add_nh4 = mysqli_stmt_init($local_link);

        $query_add_nh4 = "UPDATE planets_items_inventory 
        SET qty = qty+1 WHERE item_id=7 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_nh4, $query_add_nh4)) {

            if (mysqli_stmt_execute($stmt_add_nh4)) {

                header("Location:../../client/cp_finish_criar.php");
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_add_nh4);
    } else if (($item1 == "O" && $item2 == "O")) {

        //FAZER OZONO
        //VE SE TEM ITENS
        $stmt_o3 = mysqli_stmt_init($local_link);

        //QUERY
        $query_o3 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Oxigénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_o3, $query_o3)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_o3, $qty_O);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_o3)) {

                mysqli_stmt_store_result($stmt_o3);

                if (!mysqli_stmt_fetch($stmt_o3)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_o3);

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_O >= 3) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_O = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_O= "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =2 AND planets_user_id=$id_user 
            THEN qty - 3
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_O, $query_remove_O)) {

                if (mysqli_stmt_execute($stmt_remove_O)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "O3";

                   
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_O);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }

        //RETIRA OS ELEMENTOS
        $stmt_add_o3 = mysqli_stmt_init($local_link);

        $query_add_o3 = "UPDATE planets_items_inventory 
        SET qty = qty+1 WHERE item_id=9 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_o3, $query_add_o3)) {

            if (mysqli_stmt_execute($stmt_add_o3)) {

                header("Location:../../client/cp_finish_criar.php");
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_add_o3);

        
    } else if (($item1 == "O" && $item2 == "C") || ($item1 == "C" && $item2 == "O")) {

        //FAZER DIÓXIDO DE CARBONO
        //VE SE TEM ITENS
        $stmt_co2 = mysqli_stmt_init($local_link);

        //QUERY
        $query_co2 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Carbono'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_co2, $query_co2)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_co2, $qty_C);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_co2)) {

                mysqli_stmt_store_result($stmt_co2);

                if (!mysqli_stmt_fetch($stmt_co2)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_co2);

        //VE SE TEM ITENS
        $stmt_O = mysqli_stmt_init($local_link);

        //QUERY
        $query_O = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Oxigénio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_O, $query_O)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_O, $qty_O);

            //EXECUTA
            if (mysqli_stmt_execute($stmt_O)) {

                mysqli_stmt_store_result($stmt_O);

                if (!mysqli_stmt_fetch($stmt_O)) {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }
        }
        //ERRO PREPARE
        else {

            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_O);

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_C >= 1 && $qty_O >= 2) {

            //RETIRA OS ELEMENTOS
            $stmt_remove_co2 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_co2 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =10 AND planets_user_id=$id_user 
            THEN qty - 1 WHEN item_id=2 AND planets_user_id=$id_user 
            THEN qty-2 
            ELSE qty END";

            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_co2, $query_remove_co2)) {

                if (mysqli_stmt_execute($stmt_remove_co2)) {

                    //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                    $_SESSION['created_element'] = "CO2";
                } else {

                    echo "Error" . mysqli_error($local_link);
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_co2);
        } else {

            header("Location:../../client/cp_add_elements.php?error=qty&lab_action=0");
        }

        //RETIRA OS ELEMENTOS
        $stmt_add_co2 = mysqli_stmt_init($local_link);

        $query_add_co2 = "UPDATE planets_items_inventory 
        SET qty = qty+1 WHERE item_id=5 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_co2, $query_add_co2)) {

            if (mysqli_stmt_execute($stmt_add_co2)) {

                header("Location:../../client/cp_finish_criar.php");
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }

        mysqli_stmt_close($stmt_add_co2);
    } else {
        //NÃO PODE
        header("Location:../../client/cp_add_elements.php?error=formula&lab_action=0");
    }
} else {

    header("Location:../../client/cp_add_elements.php?error=empty&lab_action=0");
}

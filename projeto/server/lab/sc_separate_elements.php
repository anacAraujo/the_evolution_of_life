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

if (!empty($element_data)) {

     //GUARDA OS ITENS
     $item3 = $element_data['Elemento3'];

    if (($item3 == "H2O")) {

        //DESFAZER AGUA
        //VE SE TEM ITENS
        $stmt_h2o = mysqli_stmt_init($local_link);

        //QUERY
        $query_h2o = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Água'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_h2o, $query_h2o)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_h2o, $qty_h2o);

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

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_h2o > 0) {

            //RETIRA O ELEMENTO
            $stmt_remove_h2o = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_h2o = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =3 AND planets_user_id=$id_user 
            THEN qty - 1 
            ELSE qty END";
 
            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_h2o, $query_remove_h2o)) {
 
                if (!mysqli_stmt_execute($stmt_remove_h2o)) {

                    echo "Error" . mysqli_error($local_link);
 
                
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_h2o);


        } else {

            header("Location:../../client/cp_decompose_elements.php?error=qty&lab_action=1");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_h = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_h = "UPDATE planets_items_inventory 
                    SET qty = qty+2 WHERE item_id=1 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_h, $query_add_h)) {

            if (mysqli_stmt_execute($stmt_add_h)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element1'] = "H";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_h);

        //ADICIONA OS ELEMENTOS
        $stmt_add_o = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_o = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=2 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_o, $query_add_o)) {

            if (mysqli_stmt_execute($stmt_add_o)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element2'] = "O";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_o);

        header("Location:../../client/cp_finish_decompor.php");

    } else if (($item3 == "CO2")) {
        //DESFAZER DIOXIDO DE CARBONO
        //VE SE TEM ITENS
        $stmt_co2 = mysqli_stmt_init($local_link);

        //QUERY
        $query_co2 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Dióxido de Carbono'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_co2, $query_co2)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_co2, $qty_co2);

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

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_co2 > 0) {

            //RETIRA O ELEMENTO
            $stmt_remove_co2 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_co2 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id = 5 AND planets_user_id=$id_user 
            THEN qty - 1 
            ELSE qty END";
 
            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_co2, $query_remove_co2)) {
 
                if (!mysqli_stmt_execute($stmt_remove_co2)) {

                    echo "Error" . mysqli_error($local_link);

                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_co2);

        } else {

            header("Location:../../client/cp_decompose_elements.php?error=qty&lab_action=1");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_c = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_c = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=10 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_c, $query_add_c)) {

            if (mysqli_stmt_execute($stmt_add_c)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element1'] = "C";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_c);

        //ADICIONA OS ELEMENTOS
        $stmt_add_o = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_o = "UPDATE planets_items_inventory 
                    SET qty = qty+2 WHERE item_id=2 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_o, $query_add_o)) {

            if (mysqli_stmt_execute($stmt_add_o)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element2'] = "O";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_o);

        header("Location:../../client/cp_finish_decompor.php");
    } else if (($item3 == "CH4")) {
         //DESFAZER METANO
        //VE SE TEM ITENS
        $stmt_ch4 = mysqli_stmt_init($local_link);

        //QUERY
        $query_ch4 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Metano'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_ch4, $query_ch4)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_ch4, $qty_ch4);

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

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_ch4 > 0) {

            //RETIRA O ELEMENTO
            $stmt_remove_ch4 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_ch4 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =6 AND planets_user_id=$id_user 
            THEN qty - 1 
            ELSE qty END";
 
            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_ch4, $query_remove_ch4)) {
 
                if (!mysqli_stmt_execute($stmt_remove_ch4)) {

                    echo "Error" . mysqli_error($local_link);
 
                
                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_ch4);

        } else {

            header("Location:../../client/cp_decompose_elements.php?error=qty&lab_action=1");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_c = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_c = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=10 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_c, $query_add_c)) {

            if (mysqli_stmt_execute($stmt_add_c)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element1'] = "C";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_c);

        //ADICIONA OS ELEMENTOS
        $stmt_add_h = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_h = "UPDATE planets_items_inventory 
                    SET qty = qty+4 WHERE item_id=1 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_h, $query_add_h)) {

            if (mysqli_stmt_execute($stmt_add_h)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element2'] = "H";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_h);

        header("Location:../../client/cp_finish_decompor.php");
    } else if (($item3 == "NH4")) {
         //DESFAZER AMONIO
        //VE SE TEM ITENS
        $stmt_nh4 = mysqli_stmt_init($local_link);

        //QUERY
        $query_nh4 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Amónio'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_nh4, $query_nh4)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_nh4, $qty_nh4);

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

        //VÊ SE OS ITENS SÃO SUFICIENTES
        if ($qty_nh4 > 0) {

            //RETIRA O ELEMENTO
            $stmt_remove_nh4 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_nh4 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =7 AND planets_user_id=$id_user 
            THEN qty - 1 
            ELSE qty END";
 
            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_nh4, $query_remove_nh4)) {
 
                if (!mysqli_stmt_execute($stmt_remove_nh4)) {

                    echo "Error" . mysqli_error($local_link);


                }
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_nh4);

        } else {

            header("Location:../../client/cp_decompose_elements.php?error=qty&lab_action=1");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_n = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_n = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=4 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_n, $query_add_n)) {

            if (mysqli_stmt_execute($stmt_add_n)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element1'] = "N";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_n);

        //ADICIONA OS ELEMENTOS
        $stmt_add_h = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_h = "UPDATE planets_items_inventory 
                    SET qty = qty+4 WHERE item_id=1 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_h, $query_add_h)) {

            if (mysqli_stmt_execute($stmt_add_h)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element2'] = "H";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_h);

        header("Location:../../client/cp_finish_decompor.php");
    } else if (($item3 == "O3")) {
        //DESFAZER OZONO
        //VE SE TEM ITENS
        $stmt_o3 = mysqli_stmt_init($local_link);

        //QUERY
        $query_o3 = "SELECT qty FROM planets_items_inventory INNER JOIN items ON items.id =item_id WHERE planets_user_id = $id_user AND items.name='Ozono'";

        //PREPARA O STATEMENT
        if (mysqli_stmt_prepare($stmt_o3, $query_o3)) {

            //BIND DE RESULTADOS
            mysqli_stmt_bind_result($stmt_o3, $qty_o3);

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
        if ($qty_o3 > 0) {

            //RETIRA O ELEMENTO
            $stmt_remove_o3 = mysqli_stmt_init($local_link);

            //QUERY
            $query_remove_o3 = "UPDATE planets_items_inventory 
            SET qty = 
            CASE WHEN item_id =9 AND planets_user_id=$id_user 
            THEN qty - 1 
            ELSE qty END";
 
            //PREPARA A QUERY
            if (mysqli_stmt_prepare($stmt_remove_o3, $query_remove_o3)) {
 
                if (!mysqli_stmt_execute($stmt_remove_o3)) {
 
                    echo "Error" . mysqli_error($local_link);

                } 
            } else {
                echo "Error" . mysqli_error($local_link);
            }

            mysqli_stmt_close($stmt_remove_o3);

        } else {

            header("Location:../../client/cp_decompose_elements.php?error=qty&lab_action=1");
        }
        //ADICIONA OS ELEMENTOS
        $stmt_add_o2 = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_o2 = "UPDATE planets_items_inventory 
                    SET qty = qty+2 WHERE item_id=2 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_o2, $query_add_o2)) {

            if (mysqli_stmt_execute($stmt_add_o2)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element1'] = "O";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_o2);

        //ADICIONA OS ELEMENTOS
        $stmt_add_o = mysqli_stmt_init($local_link);

        //QUERY
        $query_add_o = "UPDATE planets_items_inventory 
                    SET qty = qty+1 WHERE item_id=2 AND planets_user_id=$id_user";

        //PREPARA A QUERY
        if (mysqli_stmt_prepare($stmt_add_o, $query_add_o)) {

            if (mysqli_stmt_execute($stmt_add_o)) {

                //MANDA PRO SESSION OO ELEMENTO  A MOSTRAR
                $_SESSION['created_element2'] = "O";

                
            } else {

                echo "Error" . mysqli_error($local_link);
            }
        } else {
            echo "Error" . mysqli_error($local_link);
        }
        mysqli_stmt_close($stmt_add_o);

        header("Location:../../client/cp_finish_decompor.php");
    } else {

        //NÃO PODE
        header("Location:../../client/cp_decompose_elements.php?error=formula&lab_action=1");
     }
} else {

    header("Location:../../client/cp_decompose_elements.php?error=empty&lab_action=1");
}

?>
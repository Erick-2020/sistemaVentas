<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if( isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente'])
    || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item'])
    || isset($_POST['id_agregar_item']) ){

        require_once "../controllers/prestamosController.php";
        $insPrestamo = new prestamosController();

        if( isset($_POST['buscar_cliente']) ){
            echo $insPrestamo->searchClientPrestamoController();
        }

        if( isset($_POST['id_agregar_cliente']) ){
            echo $insPrestamo->addClientPrestamoController();
        }

        if( isset($_POST['id_eliminar_cliente']) ){
            echo $insPrestamo->deleteClientPrestamoController();
        }

        if( isset($_POST['buscar_item']) ){
            echo $insPrestamo->searchItemPrestamoController();
        }

        if(isset($_POST['id_agregar_item'])){
            echo $insPrestamo->addItemPrestamoController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
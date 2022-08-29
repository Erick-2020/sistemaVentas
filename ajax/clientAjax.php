<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if(isset($_POST['cliente_dni_reg']) || isset($_POST['cliente_id_del']) || isset($_POST['cliente_id_up'])){
        // INTANCIAR CONTROLADOR
        require_once "../controllers/clientController.php";
        $insClient = new clientController();

        // AGREGAR UN CLIENTE
        if(isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg'])){
            echo $insClient->addClientController();
        }
        // ELIMIAR UN CLIENTE
        if(isset($_POST['cliente_id_del'])){
            echo $insClient->deleteClientController();
        }
        // ACTUALIZAR UN CLIENTE
        if(isset($_POST['cliente_id_up'])){
            echo $insClient->updateClientController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
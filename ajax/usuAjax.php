<?php
// <!-- RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO -->
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if(isset($_POST['usuario_dni_reg'])){
        // INTANCIAR CONTROLADOR
        require_once "../controllers/usuController.php";
        $insUsu = new usuController();

        // agregar un usuario
        if(isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])){
            // HACEMOS USO DE LA CLASE DEL CONTROLADOR
            echo $insUsu->addUsuController();
        }
    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
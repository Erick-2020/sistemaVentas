<?php
// <!-- RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO -->
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if(isset($_POST['token']) && isset($_POST['usuario'])){
        // INTANCIAR CONTROLADOR
        require_once "../controllers/loginController.php";
        $insLog = new loginController();

        echo $insLog->closedSesion();

    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
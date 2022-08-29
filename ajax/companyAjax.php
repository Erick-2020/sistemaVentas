<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if(isset($_POST['empresa_nombre_reg']) || isset($_POST['company_id_up']) ){
        // INTANCIAR CONTROLADOR
        require_once "../controllers/companyController.php";
        $insCompany = new companyController();

        if(isset($_POST['empresa_nombre_reg'])){
            echo $insCompany->addCompanyController();
        }

        if(isset($_POST['company_id_up'])){
            echo $insCompany->updateCompanyController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if(isset($_POST['item_codigo_reg']) || isset($_POST['item_id_del']) || isset($_POST['item_id_up']) ){

        require_once "../controllers/itemController.php";
        $insItem = new itemController();

        if(isset($_POST['item_codigo_reg'])){
            echo $insItem->addItemController();
        }

        if(isset($_POST['item_id_del'])){
            echo $insItem->deleteItemController();
        }
        if(isset($_POST['item_id_up']))
        {
            echo $insItem->updateItemController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE USUARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if( isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente'])
    || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item'])
    || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item'])
    || isset($_POST['prestamo_fecha_final_reg']) || isset($_POST['prestamo_codigo_del'])
    || isset($_POST['pago_codigo_reg']) || isset($_POST['prestamo_codigo_up']) ){

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
        if(isset($_POST['id_eliminar_item'])){
            echo $insPrestamo->deleteItemPrestamoController();
        }
        if(isset($_POST['prestamo_fecha_final_reg'])){
            echo $insPrestamo->addPrestamoController();
        }
        if(isset($_POST['prestamo_codigo_del'])){
            echo $insPrestamo->deletePrestamoController();
        }
        // INPUT DE LA MODAL PAGO DEL PRESTAMO PARA AGREGAR
        // UN PAGO NUEVO AL ACTUALZIAR EL PRESTAMO
        if(isset($_POST['pago_codigo_reg'])){
            echo $insPrestamo->addPagoPrestamoController();
        }
        // INPUT DEL PRESTAMO PARA ACTUALIZAR
        if(isset($_POST['prestamo_codigo_up'])){
            echo $insPrestamo->updatePrestamoController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
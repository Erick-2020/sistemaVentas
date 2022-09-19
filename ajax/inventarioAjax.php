<?php
// RECIBIR LOS DATOS QUE SON ENVIADOS DESDE LOS FORMULARIOS DE INVENTARIO
    $ajaxPeticions = true;
    require_once "../config/APP.php";

    // detectar si se envian datos del formulario
    if( isset($_POST['busqueda_vendedor'])
     || isset($_POST['id_agregar_vendedor'])
    || isset($_POST['id_eliminar_vendedor']) || isset($_POST['buscar_item'])
    || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item'])
    || isset($_POST['inventario_id_del']) || isset($_POST['add_inventario'])
    ){

        require_once "../controllers/inventarioController.php";
        $insInventario = new inventarioController();

        if( isset($_POST['busqueda_vendedor']) ){
            echo $insInventario->searchVendedorInventarioController();
        }
        if( isset($_POST['id_agregar_vendedor']) ){
            echo $insInventario->addVendedorInventarioController();
        }
        if( isset($_POST['id_eliminar_vendedor']) ){
            echo $insInventario->deleteVendedorInventarioController();
        }
        if( isset($_POST['buscar_item']) ){
            echo $insInventario->searchItemPrestamoController();
        }
        if(isset($_POST['id_agregar_item'])){
            echo $insInventario->addItemInventarioController();
        }
        if(isset($_POST['id_eliminar_item'])){
            echo $insInventario->deleteItemInventarioController();
        }
        if(isset($_POST['add_inventario'])){
            echo $insInventario->addInventarioController();
        }
        if(isset($_POST['inventario_id_del'])){
            echo $insInventario->deleteInventarioController();
        }


    }else{
        // restringir el ingreso de este archivo desde el nav
        session_start(['name'=>'SV']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
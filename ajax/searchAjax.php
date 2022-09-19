<?php
    // DEFINIR A ASIGNAR VALORES A LAS VARIABLES DE SESION
    session_start(['name'=>'SV']);
    require_once "../config/APP.php";

    if(isset($_POST['busquedaInicial']) || isset($_POST['eliminarBusqueda'])
    || isset($_POST['busquedaPrestamoInicial']) || isset($_POST['busquedaPrestamoFinal']) ){

        // ARRAY QUE TIENE LAS URL DONDE SE VAN A DIRECCIONAR
        $dataUrl = [
            "usuario"=>"user-search",
            "cliente"=>"client-search",
            "item"=>"item-search",
            "prestamo"=>"reservation-search",
            "vendedor"=>"inventario-search"
        ];

        // COMPROBAR QUE VENGA DEFINIDO EL VALOR QUE VAMOS A BUSCAR
        if(isset($_POST['modul'])){
            // COMPROBAR QUE EL DATO CORRESPONDA A LOS QUE HEMOS PARAMETRIZADO
            $modulo = $_POST['modul'];
            if(!isset($dataUrl[$modulo])){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No logramos ejecutar la busqueda, intente nuevamente",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
        }else{
            $alert=[
                "Alerta"=>"simple",
                "title"=>"Error",
                "message"=>"Error al ejecutar la busqueda en el sistema, intente nuevamente",
                "type"=>"error"
            ];
            echo json_encode($alert);
            exit();
        }

        if($modulo == "prestamo"){
            // VARIABLES DE SESION PARALAS FECHAS
            $inicioDate = "fecha_inicio_".$modulo;
            $finalDate = "fecha_final_".$modulo;

            // INICIA LA BUSQUEDA DEFINIENDO LAS VARIABLES DE SESION
            if(isset($_POST['busquedaPrestamoInicial']) || isset($_POST['busquedaPrestamoFinal'])){

                if($_POST['busquedaPrestamoInicial'] == "" || $_POST['busquedaPrestamoFinal'] == "" ) {
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"Debe llenar el rango de fechas correctamente por favor",
                        "type"=>"error"
                    ];
                    echo json_encode($alert);
                    exit();
                }
                // LAS VARIABLES SI ESTAN CORRECTAMENTE ASI QUE CREAMOS LAS VARIABLES DE SESION
                $_SESSION[$inicioDate] = $_POST['busquedaPrestamoInicial'];
                $_SESSION[$finalDate] = $_POST['busquedaPrestamoFinal'];
            }

            // ELIMINAMOS LA BUSQUEDA CON LAS VARIABLES DE SESION
            if(isset($_POST['eliminarBusqueda'])){
                unset($_SESSION[$inicioDate]);
                unset($_SESSION[$finalDate]);
            }

        // SI ESTA EN OTRO MODULO QUE NO ES PRESTAMO
        }else{
            $nameVar = "busqueda_".$modulo;
            // COMPROBAMOS LA VARIABLES DE SESION
            if(isset($_POST['busquedaInicial'])){
                // COMPROBAMOS QUE VENGA DEFINIDO EL VALOR
                if($_POST['busquedaInicial'] == "" ){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"Introduzca los datos para generar la busqueda",
                        "type"=>"error"
                    ];
                    echo json_encode($alert);
                    exit();
                }

                // CREAMOS LAS VARIABLES DE SESION
                $_SESSION[$nameVar] = $_POST['busquedaInicial'];
            }

            // ELIMINAR LA BUSQUEDA
            if(isset($_POST['eliminarBusqueda'])){
                unset($_SESSION[$nameVar]);
            }
        }
        // REDIRECCIONAR AL USUARIO A LA PAGINA DE NUEVO
        // QUE MODULO SE ESTA TRABAJANDO
        $url = $dataUrl[$modulo];
        $alert = [
            "Alerta"=>"redireccionar",
            "url"=>SERVERURL.$url."/"
        ];
        echo json_encode($alert);

    }else{
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }

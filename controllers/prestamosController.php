<?php
    if($ajaxPeticions){
        require_once "../models/prestamosModel.php";
    }else{
        require_once "./models/prestamosModel.php";
    }

    class prestamosController extends prestamosModel{

        // CONTROLADOR PARA LA TABLE MODELO DE BUSCAR UN CLIENTE AL PRESTAMO
        public function searchClientPrestamoController(){
            // RECUPERAR EL TEXTO
            $client = mainModel::stringClear($_POST['buscar_cliente']);

            // comprobar texto
            if($client == ""){
                return '
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Por favor ingresa los datos necesarios para buscar el cliente
                        </p>
                    </div>
                ';
                exit();
            }
                // SELECCIONAR CLIENTES DE LA BD
                $dataClient = mainModel::sqlConsult_Simple("SELECT * FROM cliente WHERE cliente_dni
                LIKE '%$client%' OR cliente_nombre LIKE '%$client%' OR cliente_apellido LIKE '%$client%'
                OR cliente_telefono LIKE '%$client%' ORDER BY cliente_nombre ASC");

                if($dataClient->rowCount()>=1){
                    $dataClient = $dataClient->fetchAll();

                    $table = '
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>
                    ';
                    // MOSTRAMOS LA LISTA DEL ARRAY DE CLIENTES
                    foreach($dataClient as $rows){
                        $table.= '
                            <tr class="text-center">
                                <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' -
                                '.$rows['cliente_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                        onclick="addClient('.$rows['cliente_id'].')">
                                        <i class="fas fa-user-plus"></i></button>
                                    </td>
                            </tr>
                        ';
                    }

                    $table.= '</tbody></table></div>';

                    return $table;
                }else{
                    return '
                        <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$client.'”</strong>
                        </p>
                        </div>
                    ';
                    exit();
                }
        } //FIN CONTROLADOR

        public function addClientPrestamoController(){
            $id = mainModel::stringClear($_POST['id_agregar_cliente']);

            $checkId = mainModel::sqlConsult_Simple("SELECT * FROM cliente
            WHERE cliente_id = '$id'");

            if($checkId->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encotramos el cliente en la base de datos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }else{
                $dataArray = $checkId->fetch();
            }

            // INICIAMOS LA SESION
            session_start(['name'=>'SV']);

            if(empty($_SESSION['datos_cliente'])){
                $_SESSION['datos_cliente'] = [
                    "ID"=>$dataArray['cliente_id'],
                    "DNI"=>$dataArray['cliente_dni'],
                    "NAME"=>$dataArray['cliente_nombre'],
                    "LASTNAME"=>$dataArray['cliente_apellido']
                ];

                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Agregado correctamente",
                    "message"=>"Se ha agregado el cliente al prestamo",
                    "type"=>"success"
                ];
                echo json_encode($alert);
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos agregar el cliente al prestamo",
                    "type"=>"error"
                ];
                echo json_encode($alert);
            }
        } //FIN CONTROLADOR

        public function deleteClientPrestamoController(){
            // INICIAMOS LA SESION
            session_start(['name'=>'SV']);

            // ELIMINAMOS LOS DATOS DEL CLIENTE DE LA SESION
            unset($_SESSION['datos_cliente']);

            if(empty($_SESSION['datos_cliente'])){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Cliente eliminado",
                    "message"=>"Se ha eliminado el cliente con exito",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No hemos podido eliminar el cliente",
                    "type"=>"error"
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR

        public function searchItemPrestamoController(){
            // RECUPERAR EL TEXTO
            $item = mainModel::stringClear($_POST['buscar_item']);

            // comprobar texto
            if($item == ""){
                return '
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Por favor ingresa los datos necesarios para buscar el producto
                        </p>
                    </div>
                ';
                exit();
            }
                // SELECCIONAR PRODUCTOS DE LA BD
                $dataItem = mainModel::sqlConsult_Simple("SELECT * FROM item WHERE (item_codigo
                LIKE '%$item%' OR item_nombre LIKE '%$item%')
                AND (item_estado='Habilitado') ORDER BY item_nombre ASC");

                if($dataItem->rowCount()>=1){
                    $dataItem = $dataItem->fetchAll();

                    $table = '
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>
                    ';
                    // MOSTRAMOS LA LISTA DEL ARRAY DE CLIENTES
                    foreach($dataItem as $rows){
                        $table.= '
                            <tr class="text-center">
                                <td>'.$rows['item_nombre'].'-'.$rows['item_estado'].' -
                                '.$rows['item_stock'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                        onclick="addItem('.$rows['item_id'].')">
                                        <i class="fas fa-box-open"></i></button>
                                    </td>
                            </tr>
                        ';
                    }

                    $table.= '</tbody></table></div>';

                    return $table;
                }else{
                    return '
                        <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún producto en el sistema que coincida con <strong>“
                            '.$item.'”</strong>
                        </p>
                        </div>
                    ';
                    exit();
                }
        } // FIN CONTROLADOR

        public function addItemPrestamoController(){
            // recuperando id del item
            $id = mainModel::stringClear($_POST['id_agregar_item']);

            $checkItem = mainModel::sqlConsult_Simple("SELECT * FROM item
            WHERE item_id = '$id' AND item_estado = 'Habilitado'");

            if($checkItem->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encotramos el item en la base de datos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }else{
                $dataArray = $checkItem->fetch();
            }

            // ALMACENAMOS EN VARIABLES LOS DATOS DEL FORMULARIO
            $format = mainModel::stringClear($_POST['detalle_formato']);
            $amount = mainModel::stringClear($_POST['detalle_cantidad']);
            $tiempo = mainModel::stringClear($_POST['detalle_tiempo']);
            $costo = mainModel::stringClear($_POST['detalle_costo_tiempo']);

            // COMPROBAMOS QUE LOS CAMPOS TENGAN TEXTO

            if($amount=="" || $tiempo=="" || $costo == ""){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Debes llenar todos los campos del formulario por favor",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            if(mainModel::validationData("[0-9]{1,7}",$amount)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La cantidad no es valida",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9]{1,7}",$tiempo)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El tiempo no coincide con el formato correcto",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9.]{1,15}",$costo)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El costo no coincide con el formato correcto",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS EL FORMATO DEL ITEM PARA EL PRESTAMO
            if($format != "Horas" && $format != "Dias" && $format != "Evento"
            && $format != "Mes" ){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El formato no es valido",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // INICIAMOS SESION
            session_start(['name'=>'SV']);

            // VERIFICAR SI ESTA VACIA O NO UNA VARIABLE DE SESION
            // VERIFICAR SI EL ARRAY TIENE EL ID DEFINIDO (ID DEL ITEM)
            if(empty($_SESSION['datos_item'][$id])){
                // SI NO ESTA DEFINIDO LO CREAMOS
                $costo = number_format($costo,0,'','');
                // CREAMOS EL ARRAY DE SESION
                $_SESSION['datos_item'][$id] = [
                "ID"=>$dataArray['item_id'],
                "CODE"=>$dataArray['item_codigo'],
                "NAME"=>$dataArray['item_nombre'],
                "DETAIL"=>$dataArray['item_detalle'],
                "FORMAT"=>$format,
                "AMOUNT"=>$amount,
                "TIEMPO"=>$tiempo,
                "COSTO"=>$costo
                ];

                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Item agregado",
                    "message"=>"El item ha sido agregado correctamente para el prestamo",
                    "type"=>"success"
                ];
                echo json_encode($alert);
                exit();

            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El item que intenta agregar ya esta seleccionado",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
        } // FIN CONTROLADOR

        public function deleteItemPrestamoController(){
            $idDelete = mainModel::stringClear($_POST['id_eliminar_item']);

            session_start(['name'=>'SV']);

            // MEDIANTE EL ID ELIMINAMOS LOS DATOS DEL ARRAY DEL ITEM SELECCIONADO
            unset($_SESSION['datos_item'][$idDelete]);

            if(empty($_SESSION['datos_item'])){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Item eliminado",
                    "message"=>"El item ha sido eliminado correctamente para el prestamo",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El item no ha sido eliminado correctamente, intente nuevamente",
                    "type"=>"error"
                ];
            }
            echo json_encode($alert);

        } //FIN CONTROLADOR

        public function dataPrestamoController($tipo, $id){
            $tipo = mainModel::stringClear($tipo);

            $id = mainModel::decryption($id);
            $id = mainModel::stringClear($id);

            return prestamosModel::dataPrestamoModel($tipo,$id);

        } //FIN CONTROLADOR

        public function addPrestamoController(){
            // INICIAMOS SESION PARA UTILIZAR VARAIBLES DE SESION
            session_start(["name"=>"SV"]);

            // COMPROBANDO PRODUCTOS
            if($_SESSION['prestamo_item'] == 0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No has seleccionado productos para realizar el prestamo",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS EL CLIENTE
            // EMPTY COMPRUEBA SI VIENE VACIO
            if(empty($_SESSION['datos_cliente'])){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No has seleccionado el cliente para realizar el prestamo",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // RECIBIMO LAS VARIABLES QUE ENVAIMOS POR EL FORM DE AGREGAR EL PRESTAMO
            $fechaInicio = mainModel::stringClear($_POST['prestamo_fecha_inicio_reg']);
            $horaInicio = mainModel::stringClear($_POST['prestamo_hora_inicio_reg']);
            $fechaFinal = mainModel::stringClear($_POST['prestamo_fecha_final_reg']);
            $horaFinal = mainModel::stringClear($_POST['prestamo_hora_final_reg']);
            $estado = mainModel::stringClear($_POST['prestamo_estado_reg']);
            $totalPagado = mainModel::stringClear($_POST['prestamo_pagado_reg']);
            $observacion = mainModel::stringClear($_POST['prestamo_observacion_reg']);

            // VALIDACION DE DATOS
            if(mainModel::validationDate($fechaInicio)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La fecha inicial no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$horaInicio)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La hora inicial no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationDate($fechaFinal)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La fecha final no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$horaFinal)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La hora final no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if($estado != "Reservacion" && $estado != "Prestamo" &&
            $estado != "Finalizado"){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El estado del prestamo no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9.]{1,10}",$totalPagado)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El dato del pago no es valida",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            if($observacion != ""){
                if(mainModel::validationData("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El dato de la observacion no es valida",
                        "type"=>"warning"
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            //  COMPROBAMOS QUE LAS FECHAS SEAN CORRECTAS
            // VALIDANDO DE LA SIGUIENTE MANERA
            if(strtotime($fechaFinal) < strtotime($fechaFinal)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"La fecha de entrega no puede ser antes a la fecha de inicio
                    del prestamo",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }
            // FORMATEAR TODOS LOS DATOS QUE MANDAMOS A LA BD
            $totalPrestamo = number_Format($_SESSION['prestamo_total'],0,'','');
            $totalPagado = number_Format($totalPagado,0,'','');
            $fechaInicio = date("Y-m-d", strtotime($fechaInicio));
            $fechaFinal = date("Y-m-d", strtotime($fechaFinal));
            // FORMATO HORA:MINUTOS FORMATO(AM,PM)
            $horaInicio = date("h:i a", strtotime($horaInicio));
            $horaFinal = date("h:i a", strtotime($horaFinal));

            // GENERAR CODIGO DE PRESTAMOS
            // SI EN EL PRESTAMO NO HAY DATOS = 1
            // SI HAY DATOS VA A HACER = 2 Y ASI SUSESIVAMENTE
            $correlativo = mainModel::sqlConsult_Simple("SELECT prestamo_id FROM prestamo");
            // CONTAR CUANTO REGISTROS SELECCIONO Y SUMARLE UNO
            $correlativo = ($correlativo->rowCount()) + 1;
            // GENERAMOS EL CODIGO
            // codigGenerate($letra, $long, $number)
            $codigo = mainModel::codigGenerate("P",7,$correlativo);

            // CREAMOS EL ARAY DE DATOS
            $dataArrayPrestamo = [
                "CODE"=> $codigo,
                "DATEINICIO"=>$fechaInicio,
                "HOURINICIO"=>$horaInicio,
                "DATEFINAL"=>$fechaFinal,
                "HOURFINAL"=>$horaFinal,
                "CANTIDAD"=>$_SESSION['prestamo_item'],
                "TOTAL"=>$totalPrestamo,
                "PAGO"=>$totalPagado,
                "STATUS"=>$estado,
                "OBSERVATION"=>$observacion,
                // ADMINISTRADOR QUE HACE EL PRESTAMO
                "IDUSER"=>$_SESSION['id_sv'],
                // cliente al que pide el prestamo
                "IDCLIENT"=>$_SESSION['datos_cliente']['ID']
            ];

            $addPrestamo = prestamosModel::addPrestamoModel($dataArrayPrestamo);

            // AGREGAMOS PRIMERO LA PRIMERA TABLA DE RELACION QUE ES EL PRESTAMO
            if($addPrestamo->rowCount() != 1){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error 001",
                    "message"=>"No hemos podido registrar el prestamo, intente nuevamente!",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // AGREGAMOS LA SEGUNDA TABLA DE RELACION QUE ES EL PAGO
            // CUANTO EN EL CAMPO TOTAL DEPOSITADO ES MAYOR A CERO SE ESTAN REGISTRANDO DATOS
            // EN LA TABLA DE PAGO
            if($totalPagado > 0){
                $dataArrayPago = [
                    "TOTAL"=>$totalPagado,
                    "FECHA"=>$fechaInicio,
                    "CODEPRESTAMO"=>$codigo
                ];

                $addPago = prestamosModel::addPagoPrestamoModel($dataArrayPago);

                if($addPago->rowCount() != 1){
                    // COMO NO SE REGISTRO UN PAGO, ELIMINAMOS LOS DATOS DEL PRESTAMO
                    // INSERTADOS ANTERIORMENTE
                    prestamosModel::deletePrestamoModel($codigo,"Prestamo");
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error 002",
                        "message"=>"No hemos podido registrar el pago del prestamo, intente nuevamente!",
                        "type"=>"error"
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            // AGREGAMOS LA TERCERA TABLA DE RELACION QUE ES EL DETALLE
            $erroresDetalle = 0;

            foreach($_SESSION['datos_item'] as $items){
                $costo = number_format($items['COSTO'],0,'','');
                $descripcion = $items['CODE']." ".$items['NAME'];

                $dataArrayDetalle = [
                    "CANTIDAD"=>$items["AMOUNT"],
                    "FORMATO"=>$items["FORMAT"],
                    "TIEMPO"=>$items["TIEMPO"],
                    "COSTO"=>$costo,
                    "DESCRIPCION"=>$descripcion,
                    "CODEPRESTAMO"=>$codigo,
                    "IDITEM"=>$items["ID"],
                ];

                $addDetalle = prestamosModel::addDetailModel($dataArrayDetalle);

                if($addDetalle->rowCount() != 1){
                    $erroresDetalle = 1;
                    break;
                }
            }

            if($erroresDetalle == 0){
                unset($_SESSION['datos_cliente']);
                unset($_SESSION['datos_item']);
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Agregado correctamente",
                    "message"=>"Los datos del prestamo han sido agregados en el sistema",
                    "type"=>"success"
                ];
            }else{
                prestamosModel::deletePrestamoModel($codigo,"Detalle");
                prestamosModel::deletePrestamoModel($codigo,"Pago");
                prestamosModel::deletePrestamoModel($codigo,"Prestamo");
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error 003",
                    "message"=>"No hemos podido registrar el pago del prestamo, intente nuevamente!",
                    "type"=>"warning"
                ];
            }
            echo json_encode($alert);
        }
    }
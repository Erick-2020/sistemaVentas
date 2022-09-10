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
        } //FIN CONTROLADOR
        // ES EL MISMO CONTROLADOR QUE LOS DEMAS
        // CAMBIAMOS LA VARIABLE BUSUQEDA POR TIPO
        // PARA SABER CUENDA ESTAMOS UTILIZANDO EL PAGINADOR
        // SI EN LA VISTA DE PRESTAMOS, FINZALIZADOS O BUSQUEDA
        public function paginadorPrestamoController($actualPage, $registers,
        $privilegio,$url,$tipo, $fechaInicio, $fechaFinal){

            $actualPage = mainModel::stringClear($actualPage);
            $registers = mainModel::stringClear($registers);
            $privilegio = mainModel::stringClear($privilegio);

            $url = mainModel::stringClear($url);
            $url = SERVERURL.$url."/";

            $tipo = mainModel::stringClear($tipo);

            $fechaInicio = mainModel::stringClear($fechaInicio);
            $fechaFinal = mainModel::stringClear($fechaFinal);

            $table = "";

            $actualPage = (isset($actualPage) && $actualPage> 0) ? (int) $actualPage : 1;
            //determinar en que pagina estoy
            $inicio = ($actualPage >0) ? (($actualPage * $registers) - $registers) : 0;

            // COMPROBAMOS QUE LA FECHA DE INICIO Y LA FINAL SEAN CORRECTAS
            if($tipo == "Busqueda"){
                if(mainModel::validationDate($fechaInicio) || mainModel::validationDate($fechaFinal) ){
                    return '
                        <div class="alert alert-danger text-center" role="alert">
                            <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                            <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
                            <p class="mb-0">Las fechas ingresadas para la busqueda no son correctas.</p>
                        </div>
                    ';
                    exit();
                }
            }
            
            // NO SELEECIONAMOS TODOS LOS DATOS DE UNA SOLA TABLA
            // SINO QUE SELECCCIONAMOS DATOS DE VARIAS TABLAS
            // COMO LA DEL PRESTAMO Y LA DE CLIENTES
            $dataConsult = " prestamo.prestamo_id, prestamo.prestamo_codigo, prestamo.prestamo_fecha_inicio,
            prestamo.prestamo_fecha_final, prestamo.prestamo_total, prestamo.prestamo_pagado,
            prestamo.prestamo_estado, prestamo.usuario_id, prestamo.cliente_id, cliente.cliente_nombre,
            cliente.cliente_apellido";

            // between funciona para declarar el rago por el cual queremos buscar
            if($tipo == "Busqueda" && $fechaInicio != "" && $fechaFinal != ""){
                $consulta = "SELECT SQL_CALC_FOUND_ROWS $dataConsult FROM prestamo
                INNER JOIN cliente ON cliente.cliente_id = prestamo.cliente_id
                WHERE (prestamo.prestamo_fecha_inicio BETWEEN '$fechaInicio' AND '$fechaFinal')
                ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio, $registers";
            }else{
                $consulta = "SELECT SQL_CALC_FOUND_ROWS $dataConsult FROM prestamo
                INNER JOIN cliente ON cliente.cliente_id = prestamo.cliente_id
                WHERE prestamo_estado = '$tipo'
                ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio, $registers";
            }

            $conexion = mainModel::connection();

            // almacenar los datos que se seleccionan en la bd
            $data = $conexion->query($consulta);
            // REALIAMOS EL ARRAY DE DATOS
            $data = $data->fetchAll();

            // contamos todos los registros gracias al parametro que se esta utilizando en la consulta
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            //REDONDEAR EL NUMERO DE PAGINAS
            $nPages = ceil($total/$registers);

            $table.='
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>CLIENTE</th>
                            <th>FECHA DE PRÉSTAMO</th>
                            <th>FECHA DE ENTREGA</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>FACTURA</th>';
                            if($privilegio == 1 || $privilegio == 2){
                                $table.='<th>ACTUALIZAR</th>';
                            }
                            if($privilegio == 1){
                                $table.='<th>ELIMINAR</th>';
                            }
                            $table.='</tr>
                        </thead>
                        <tbody>
            ';
            // VISTA PARA QUE RECORRA LOS NUMEROS DE LAS PAGINAS Y LOS REGISTROS Y LOS MUESTRE
            if($total>=1 && $actualPage<=$nPages){
                $contador = $inicio + 1;

                $reg_inicio = $inicio + 1;

                // CICLO PARA MOSTRAR CADA TR
                foreach($data as $rows){
                    $table.= '<tr class="text-center" >
                                <td>'.$contador.'</td>
                                <td>'.$rows['cliente_nombre']." ".$rows['cliente_apellido'].'</td>
                                <td>'.date("d-m-Y",strtotime($rows['prestamo_fecha_inicio'])).'</td>
                                <td>'.date("d-m-Y",strtotime($rows['prestamo_fecha_final'])).'</td>
                                <td>'.$rows['prestamo_estado'].'</td>
                            ';
                            if($rows['prestamo_pagado'] < $rows['prestamo_total']){
                                $table.='<td>Pendiente: <span class="badge badge-danger">
                                    '.MONEDA.number_format(
                                    ($rows['prestamo_total']-$rows['prestamo_pagado']),0,'',',').'
                                </span></td>';
                            }else{
                                $table.='<td><span class="badge badge-light">Cancelado</span></td>';
                            }

                            // FACTURA
                            $table.='
                                <td>
                                    <a href="'.SERVERURL.'facturas/invoice.php?id=
                                    '.mainModel::encryption($rows['prestamo_id']).'"
                                    class="btn btn-info" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            ';


                            if($privilegio == 1 || $privilegio == 2){
                                if($rows['prestamo_estado'] == "Finalizado" &&
                                $rows['prestamo_pagado'] == $rows['prestamo_total'] ){
                                    $table.='<td>
                                        <button class="btn btn-success" disabled>
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </td>';
                                }else{
                                    $table.='<td>
                                        <a href="'.SERVERURL.'reservation-update/'
                                        .mainModel::encryption($rows['prestamo_id']).'/"
                                        class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </td>';
                                }
                            }
                            if($privilegio == 1){
                            $table.='<td>
                                <form action="'.SERVERURL.'ajax/prestamosAjax.php"
                                    class="FormularioAjax" method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="prestamo_codigo_del"
                                    value="'.mainModel::encryption($rows['prestamo_codigo']).'">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>';
                            }
                        $table.='</tr>';
                    $contador++;
                }
                $reg_final = $contador - 1;
            }else{
                // VISTA CUANDO NO SE ENCUENTRA UN REGISTRO EN LA TABLA O NO EXISTE
                if($total>=1){
                    $table.= '<tr class="text-center"><td colspan="9">
                        <a href="'.$url.'"
                            class="btn btn_raised btn_primary btn_sm">Haga click aquí para recargar el listado
                        </a></td></tr>';
                }else{
                    $table.= '<tr class="text-center"> <td colspan="9"> No hay registros en el Sistema</td></tr>';
                }
            }
            $table.= '</tbody></table></div>';

            if($total>=1 && $actualPage<=$nPages){
                $table.='<p class="text-right">Mostrando prestamos '.$reg_inicio.' al '.$reg_final.'
                de un total de '.$total.'</p>';

                $table.=mainModel::paginador($actualPage, $nPages, $url, 7);
            }

            return $table;

        } //FIN CONTROLADOR

        public function deletePrestamoController(){

            // RECUPERAR EL codigo del PRESTAMO a eliminar
            $codigoDel = mainModel::decryption($_POST['prestamo_codigo_del']);
            $codigoDel = mainModel::stringClear($codigoDel);

            // COMPROBAMOS PRESTAMO EN LA BD
            $checkPrestamo = mainModel::sqlConsult_Simple("SELECT prestamo_codigo FROM
            prestamo WHERE prestamo_codigo ='$codigoDel'");

            if($checkPrestamo->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encontramos el prestamo que intenta eliminar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            // COMPROBANDO PRIVILEGIOS
            // SOLAMENTE LOS QUE TIENEN PRIVILEGIOS NIVEL 1 (ADMIN) PUEDEN ELIMINAR
            session_start(['name'=>'SV']);
            if($_SESSION['privilegio_sv']!=1){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No tienes los permisos necesarios para eliminar prestamos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS Y ELIMINAMOS DATOS DE LA PRRIMERA TABLA ASOCIADA QUE ES LA DE PAGOS
            $checkPagos = mainModel::sqlConsult_Simple("SELECT prestamo_codigo FROM pago
            WHERE prestamo_codigo = '$codigoDel'");
            $checkPagos=$checkPagos->rowCount();
            if($checkPagos > 0){

                $pagosDelete = prestamosModel::deletePrestamoModel($codigoDel, "Pago");

                // COMPROBAMOS QUE TODOS LOS DATOS HAYAN SIDO ELIMINADOS
                if($pagosDelete->rowCount() != $checkPagos){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error (Pagos)",
                        "message"=>"No hemos podido eliminar el prestamo, por favor intente nuevamente",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }
            // COMPROBAMOS Y ELIMINAMOS DATOS DE LA SEGUNDA TABLA ASOCIADA QUE ES LA DE DETALLE
            $checkDetalle = mainModel::sqlConsult_Simple("SELECT prestamo_codigo FROM detalle
            WHERE prestamo_codigo = '$codigoDel'");
            $checkDetalle=$checkDetalle->rowCount();
            if($checkDetalle > 0){

                $detalleDelete = prestamosModel::deletePrestamoModel($codigoDel, "Detalle");

                // COMPROBAMOS QUE TODOS LOS DATOS HAYAN SIDO ELIMINADOS
                if($detalleDelete->rowCount() != $checkDetalle){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error (Detalle)",
                        "message"=>"No hemos podido eliminar el prestamo, por favor intente nuevamente",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            // ELIMINAMOS LOS DATOS DE LA TABLA PRESTAMO
            $prestamoDelete = prestamosModel::deletePrestamoModel($codigoDel, "Prestamo");
            if($prestamoDelete->rowCount() ==1){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Eliminado Correctamente",
                    "message"=>"Se ha eliminado el prestamo correctamente",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No hemos podido eliminar el prestamo, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR

        // CONTROLADOR PARAADD PAGOS EN LA MODAL DEL PRESTAMO AL ACTUALIZAR
        public function addPagoPrestamoController(){
            $codigo = mainModel::decryption($_POST['pago_codigo_reg']);
            $codigo = mainModel::stringClear($codigo);

            //CANTIDADA
            $monto = mainModel::stringClear($_POST['pago_monto_reg']);
            //FORMATEAR LA CANTIDAD PARA INGRESARLA A LA BD
            $monto = number_format($monto,0,'','');

            // COMPROBAMOS QUE EL PAGO SEA MAYOR A 0
            if($monto <= 0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El pago debe ser mayor a 0",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS QUE EL PRESTAMO EXISTA EN LA BD
            // SI EXISTE HACEMOS EL ARRAY DE TODOS LOS DATS DEL PRESTAMO
            $datosPrestamo = mainModel::sqlConsult_Simple("SELECT * FROM prestamo
            WHERE prestamo_codigo = '$codigo'");

            if($datosPrestamo->rowCount() <= 0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El prestamo al que intentas agregar al pago
                    no existe en el sistema",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }else{
                $datosPrestamo = $datosPrestamo->fetch();
            }

            // COMPROBAMOS QUE EL MONTO NO SEA MAYOR A LA DEUDA
            // REALIZAMOS LA VALIDACION DE CUANTO DINERO FALTA
            $pendiente = number_format((
                $datosPrestamo['prestamo_total']-$datosPrestamo['prestamo_pagado']
            ),0,'','');

            if($monto > $pendiente){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No puedes pagar mas de lo que debes",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS LOS PRIVILEGIOS DEL USUARIO PARA ACTUALZIAR EL PAGO
            session_start(['name'=>'SV']);
            if($_SESSION['privilegio_sv'] <1 || $_SESSION['privilegio_sv'] >2){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No tienes permisos para actualizar el pago del prestamo",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // CALCULAMOS CUANTO A PAGADO EL CLIENTE
            // FORMATEAMOS Y CREMOS LA FECHA ACTUAL
            $totalPagado = number_format((
                $monto + $datosPrestamo['prestamo_pagado']
            ),0,'','');

            $fecha = date("Y-m-d");

            // LOS DATOS QUE LE ENVIAMOS AL MODELO
            $datosPagoReg = [
                "TOTAL"=> $monto,
                "FECHA"=>$fecha,
                "CODEPRESTAMO"=>$codigo
            ];

            $addPago = prestamosModel::addPagoPrestamoModel($datosPagoReg);

            if($addPago->rowCount() == 1){
                $datosPrestamoUp = [
                    "Tipo"=>"Pago",
                    "MONTO"=>$totalPagado,
                    "CODIGO"=>$codigo
                ];
                if(prestamosModel::updatePrestamoModel($datosPrestamoUp)){
                    $alert=[
                        "Alerta"=>"recargar",
                        "title"=>"Actualizado Correctamente",
                        "message"=>"El pago de ".MONEDA.$monto." a sido actualizado
                        correctamente",
                        "type"=>"success",
                    ];
                }else{
                    // YA SE HA REGISTRADO UN DATO EN LA BD, PERO COMO HA OCURRIDO UN ERROR
                    // DEBEMOS ELIMINAR EL REGISTRO QUE SE HA HECHO PREVIAMNETE
                    prestamosModel::deletePrestamoModel($codigo,"Pago");
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error 001 upPago",
                        "message"=>"No hemos podido registrar el pago, intente nuevamente",
                        "type"=>"error",
                    ];
                }
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error 002 upPago",
                    "message"=>"No hemos podido registrar el pago, intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);

        } //FIN CONTROLADOR

        // ACTUALIZAR LOS DATOS DEL PRESTAMO
        public function updatePrestamoController(){

            $codigo = mainModel::decryption($_POST['prestamo_codigo_up']);
            $codigo = mainModel::stringClear($codigo);

            // / COMPROBAMOS QUE EL PRESTAMO EXISTA EN LA BD
            // SI EXISTE HACEMOS EL ARRAY DE TODOS LOS DATS DEL PRESTAMO
            $checkPrestamo = mainModel::sqlConsult_Simple("SELECT prestamo_codigo
            FROM prestamo WHERE prestamo_codigo = '$codigo'");

            // COM PROBAMOS QUE EL PRESTAMO EXISTA EN LA BD
            if($checkPrestamo->rowCount() <= 0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El prestamo no existe en el sistema",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }else{
                $checkPrestamo = $checkPrestamo->fetch();
            }

            // RECIBIMOS DATOS
            $estado = mainModel::stringClear($_POST['prestamo_estado_up']);
            $observacion = mainModel::stringClear($_POST['prestamo_observacion_up']);

            // VALIDAMOS LOS DATOS
            if($observacion != ""){
                if(mainModel::validationData("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"La observacion no es valida",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }
            // COMPROBAMOS EL ESTADO DEL PRESTAMO
            if($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado"){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El estado no es valido",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS LOS PRIVILEGIOS DEL USUARIO PARA ACTUALZIAR EL PRESTAMO
            session_start(['name'=>'SV']);
            if($_SESSION['privilegio_sv'] <1 || $_SESSION['privilegio_sv'] >2){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No tienes permisos para actualizar el pago del prestamo",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            $datosPrestamoUp =[
                "Tipo"=>"Prestamo",
                "ESTADO"=>$estado,
                "OBSERVACION"=>$observacion,
                "CODIGO"=>$codigo
            ];

            if(prestamosModel::updatePrestamoModel($datosPrestamoUp)){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Actualizado Correctamente",
                    "message"=>"El prestamo a sido actualizado correctamente",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No hemos podido actualizar el prestamo, intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);

        } // FIN CONTROLADOR
    }
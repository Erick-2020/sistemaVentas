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
            if(mainModel::validationData("[0-9]{1,7}", $tiempo)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El tiempo no coincide con el formato correcto",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9.]{1,15}", $costo)){
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
                $costo = number_format($costo,2,'.','');
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
    }
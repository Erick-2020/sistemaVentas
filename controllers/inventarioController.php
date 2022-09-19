<?php
    if($ajaxPeticions){
        require_once "../models/inventarioModel.php";
    }else{
        require_once "./models/inventarioModel.php";
    }

    class inventarioController extends inventarioModel{

        // CONTROLADOR PARA LA TABLE MODELO DE BUSCAR UN CLIENTE AL PRESTAMO
        public function searchVendedorInventarioController(){
            // RECUPERAR EL TEXTO
            $vendedor = mainModel::stringClear($_POST['buscar_vendedor']);

            // comprobar texto
            if($vendedor == ""){
                return '
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                Por favor ingresa los datos necesarios para buscar el vendedor
                        </p>
                    </div>
                ';
                exit();
            }
                // SELECCIONAR CLIENTES DE LA BD
                $dataVendedor = mainModel::sqlConsult_Simple("SELECT * FROM usuario WHERE usuario_dni
                LIKE '%$vendedor%' OR usuario_nombre LIKE '%$vendedor%' OR usuario_apellido LIKE '%$vendedor%'
                OR usuario_telefono LIKE '%$vendedor%' ORDER BY usuario_nombre ASC");

                if($dataVendedor->rowCount()>=1){
                    $dataVendedor = $dataVendedor->fetchAll();

                    $table = '
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>
                    ';
                    // MOSTRAMOS LA LISTA DEL ARRAY DE CLIENTES
                    foreach($dataVendedor as $rows){
                        $table.= '
                            <tr class="text-center">
                                <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].' -
                                '.$rows['usuario_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                        onclick="addVendedor('.$rows['usuario_id'].')">
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
                            No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$vendedor.'”</strong>
                        </p>
                        </div>
                    ';
                    exit();
                }
        } //FIN CONTROLADOR

        public function addVendedorInventarioController(){
            $id = mainModel::stringClear($_POST['id_agregar_vendedor']);

            $checkId = mainModel::sqlConsult_Simple("SELECT * FROM usuario
            WHERE usuario_id = '$id'");

            if($checkId->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encotramos el vendedor en la base de datos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }else{
                $dataArray = $checkId->fetch();
            }

            // INICIAMOS LA SESION
            session_start(['name'=>'SV']);

            if(empty($_SESSION['datos_usuario'])){
                $_SESSION['datos_usuario'] = [
                    "ID"=>$dataArray['usuario_id'],
                    "DNI"=>$dataArray['usuario_dni'],
                    "NAME"=>$dataArray['usuario_nombre'],
                    "LASTNAME"=>$dataArray['usuario_apellido']
                ];

                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Agregado correctamente",
                    "message"=>"Se ha agregado el vendedor al inventario",
                    "type"=>"success"
                ];
                echo json_encode($alert);
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos agregar el vendedor al prestamo",
                    "type"=>"error"
                ];
                echo json_encode($alert);
            }
        } //FIN CONTROLADOR

        public function deleteVendedorInventarioController(){
            // INICIAMOS LA SESION
            session_start(['name'=>'SV']);

            // ELIMINAMOS LOS DATOS DEL VENDEDOR DE LA SESION
            unset($_SESSION['datos_usuario']);

            if(empty($_SESSION['datos_usuario'])){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Usuario eliminado",
                    "message"=>"Se ha eliminado el vendedor con exito",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No hemos podido eliminar el vendedor",
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

        public function addItemInventarioController(){
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
            $amount = mainModel::stringClear($_POST['detalle_cantidad']);

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

            // INICIAMOS SESION
            session_start(['name'=>'SV']);

            // VERIFICAR SI ESTA VACIA O NO UNA VARIABLE DE SESION
            // VERIFICAR SI EL ARRAY TIENE EL ID DEFINIDO (ID DEL ITEM)
            if(empty($_SESSION['datos_item'][$id])){

                // CREAMOS EL ARRAY DE SESION
                $_SESSION['datos_item'][$id] = [
                "ID"=>$dataArray['item_id'],
                "CODE"=>$dataArray['item_codigo'],
                "NAME"=>$dataArray['item_nombre'],
                "DETAIL"=>$dataArray['item_detalle'],
                "AMOUNT"=>$amount
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

        public function deleteItemInventarioController(){
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

        public function addInventarioController(){
            // INICIAMOS SESION PARA UTILIZAR VARAIBLES DE SESION
            session_start(["name"=>"SV"]);

            // COMPROBANDO PRODUCTOS
            if($_SESSION['prestamo_item'] == 0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No has seleccionado productos para realizar el inventario",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAMOS EL CLIENTE
            // EMPTY COMPRUEBA SI VIENE VACIO
            if(empty($_SESSION['datos_usuario'])){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No has seleccionado el vendedor para realizar el inventario",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // CREAMOS EL ARAY DE DATOS
            foreach($_SESSION['datos_item'] as $items){
                $descripcion = $items['CODE']." ".$items['NAME'];

                $dataArrayPrestamo = [
                    "CANTIDAD"=>$_SESSION['prestamo_item'],
                    // VENDEDOR al que pide el prestamo
                    "IDUSER"=>$_SESSION['datos_usuario']['ID'],
                    "ITEM"=>$items['NAME'],
                    "NAMEUSER"=>$_SESSION['datos_usuario']['NAME'],
                ];
            }

            $addPrestamo = inventarioModel::addInventarioModel($dataArrayPrestamo);

            // AGREGAMOS PRIMERO LA PRIMERA TABLA DE RELACION QUE ES EL PRESTAMO
            if($addPrestamo->rowCount() != 1){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error 001",
                    "message"=>"No hemos podido registrar el inventario, intente nuevamente!",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }else{
                unset($_SESSION['datos_cliente']);
                unset($_SESSION['datos_item']);
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Agregado Correctamente",
                    "message"=>"El inventario a sido creado correctamente",
                    "type"=>"success"
                ];
                echo json_encode($alert);
                exit();
            }
        } //FIN CONTROLADOR

        public function paginadorInventarioController($actualPage, $registers,
        $privilegio,$url,$busqueda){

            $actualPage = mainModel::stringClear($actualPage);
            $registers = mainModel::stringClear($registers);
            $privilegio = mainModel::stringClear($privilegio);

            $url = mainModel::stringClear($url);
            $url = SERVERURL.$url."/";

            $busqueda = mainModel::stringClear($busqueda);

            $table = "";

            $actualPage = (isset($actualPage) && $actualPage> 0) ? (int) $actualPage : 1;
            //determinar en que pagina estoy
            $inicio = ($actualPage >0) ? (($actualPage * $registers) - $registers) : 0;

            if(isset($busqueda) && $busqueda != ""){
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM inventarios WHERE
                inventario_id LIKE '%$busqueda%'
                OR usuario_nombre LIKE '%$busqueda%'
                OR item_nombre LIKE '%$busqueda%'
                ORDER BY usuario_nombre ASC LIMIT $inicio, $registers";
            }else{
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM inventarios
                ORDER BY item_nombre ASC LIMIT $inicio, $registers";
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
                            <th>VENDEDOR</th>
                            <th>PRODUCTO</th>
                            <th>CANTIDAD</th>';
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
                                <td>'.$rows['usuario_nombre']." ". 'vendedor'.'</td>
                                <td>'.$rows['item_nombre'].'</td>
                                <td>'.$rows['inventario_cantidad'].'</td>
                            ';
                            if($privilegio == 1){
                            $table.='<td>
                                <form action="'.SERVERURL.'ajax/inventarioAjax.php"
                                    class="FormularioAjax" method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="inventario_id_del"
                                    value="'.mainModel::encryption($rows['inventario_id']).'">
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
                $table.='<p class="text-right">Mostrando inventarios '.$reg_inicio.' al '.$reg_final.'
                de un total de '.$total.'</p>';

                $table.=mainModel::paginador($actualPage, $nPages, $url, 7);
            }

            return $table;

        } //FIN CONTROLADOR

        public function deleteInventarioController(){

            // RECUPERAR EL codigo del PRESTAMO a eliminar
            $idDelete = mainModel::decryption($_POST['inventario_id_del']);
            $idDelete = mainModel::stringClear($idDelete);

            // COMPROBAMOS PRESTAMO EN LA BD
            $checkInventario = mainModel::sqlConsult_Simple("SELECT inventario_id FROM
            inventarios WHERE inventario_id ='$idDelete'");

            if($checkInventario->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encontramos el inventario que intenta eliminar",
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
                    "message"=>"No tienes los permisos necesarios para eliminar inventarios",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // ELIMINAMOS LOS DATOS DE LA TABLA INVENTARIOS
            $inventarioDelete = inventarioModel::deleteInventarioModel($idDelete, "Inventario");
            if($inventarioDelete->rowCount() ==1){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Eliminado Correctamente",
                    "message"=>"Se ha eliminado el inventario correctamente",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No hemos podido eliminar el inventario, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR
    }
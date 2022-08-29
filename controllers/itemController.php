<?php
    if($ajaxPeticions){
        require_once "../models/itemModel.php";
    }else{
        require_once "./models/itemModel.php";
    }

    class itemController extends itemModel{
        public function addItemController(){
            $code = mainModel::stringClear($_POST['item_codigo_reg']);
            $name = mainModel::stringClear($_POST['item_nombre_reg']);
            $stock = mainModel::stringClear($_POST['item_stock_reg']);
            $status = mainModel::stringClear($_POST['item_estado_reg']);
            $detail = mainModel::stringClear($_POST['item_detalle_reg']);

            if($code == "" || $name == "" || $stock == "" || $status == ""){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            if(mainModel::validationData("[a-zA-Z0-9-]{1,45}", $code)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El codigo esta en un formato invalido",
                    "type"=>"error"
                ];
                json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $name)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El nombre esta en un formato invalido",
                    "type"=>"error"
                ];
                json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9]{1,9}", $stock)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El stock del producto esta en un formato invalido",
                    "type"=>"error"
                ];
                json_encode($alert);
                exit();
            }
            if($status != "Habilitado" && $status != "Deshabilitado"){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El estado no corresponde a los valores definidos del sistema",
                    "type"=>"error"
                ];
                json_encode($alert);
                exit();   
            }
            if($detail != ""){
                if(mainModel::validationData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $detail)){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El detalle esta en un formato invalido",
                        "type"=>"error"
                    ];
                    json_encode($alert);
                    exit();
                }
            }

            // COMPROBANDO QUE EL CODIGO NO ESTE REGISTRADO
            $checkCode = mainModel::sqlConsult_Simple("SELECT item_codigo FROM item WHERE item_codigo = '$code'");
            if($checkCode->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El Codigo del producto ya existe",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBANDO QUE EL NAME NO ESTE REGISTRADO
            $checkName = mainModel::sqlConsult_Simple("SELECT item_nombre FROM item
            WHERE item_nombre = '$name'");
            if($checkName->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El Nombre del producto ya existe",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            $dataItem = [
                "CODE"=>$code,
                "NAME"=>$name,
                "STOCK"=>$stock,
                "STATUS"=>$status,
                "DETAILS"=>$detail
            ];

            $addItem = itemModel::addItemModel($dataItem);

            if($addItem->rowCount()==1){
                $alert=[
                    "Alerta"=>"limpiar",
                    "title"=>"Finalizado",
                    "message"=>"El Producto a sido agregado correctamente",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Finalizado",
                    "message"=>"El Producto no se ha podido agregar, intente nuevamente",
                    "type"=>"error"
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR

        public function itemPaginatorController($actualPage, $registers, $privilegio, $url, $busqueda){
            $actualPage = mainModel::stringClear($actualPage);
            $registers = mainModel::stringClear($registers);
            $privilegio = mainModel::stringClear($privilegio);

            $url = mainModel::stringClear($url);
            $url = SERVERURL.$url."/";

            $busqueda = mainModel::stringClear($busqueda);
            $table="";

            // VERIFICAMOS QUE ESTE EN UNA PAGINA, QUE ESTE DEFINIDA Y QUE SEA ENTERO.
            // determinar que sea un numero en la url y si corresponda a un numero entero valido
            // SI NO VIENE DEFINIA O NO ES UN NUMERO ENTERO, LE DECIMOS QUE SE UBIQUE EN  LA PAGINA UNO
            $actualPage = (isset($actualPage) && $actualPage> 0) ? (int) $actualPage : 1;

            //determinar en que pagina estoy
            $inicio = ($actualPage >0) ? (($actualPage * $registers) - $registers) : 0;

            if(isset($busqueda) && $busqueda != ""){
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$busqueda%'
                OR item_nombre LIKE '%$busqueda%'
                OR item_stock LIKE '%$busqueda%'
                ORDER BY item_nombre ASC LIMIT $inicio, $registers";
            }else{
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item
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
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>STOCK</th>
                            <th>ESTADO</th>
                            <th>DETALLE</th>';
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
                                <td>'.$rows['item_codigo'].'</td>
                                <td>'.$rows['item_nombre'].'</td>
                                <td>'.$rows['item_stock'].'</td>
                                <td>'.$rows['item_estado'].'</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="popover"
                                    data-trigger="hover" title="'.$rows['item_nombre'].'"
                                    data-content="'.$rows['item_detalle'].'">
                                    <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>';
                                if($privilegio == 1 || $privilegio == 2){
                                    $table.='<td>
                                        <a href="'.SERVERURL.'item-update/'
                                        .mainModel::encryption($rows['item_id']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </td>';
                                }
                                if($privilegio == 1){
                                $table.='<td>
                                    <form action="'.SERVERURL.'ajax/itemAjax.php"
                                    class="FormularioAjax" method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="item_id_del"
                                    value="'.mainModel::encryption($rows['item_id']).'">
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
                    $table.= '<tr class="text-center"><td colspan="8">
                        <a href="'.$url.'"
                            class="btn btn_raised btn_primary btn_sm">Haga click aquí para recargar el listado
                        </a></td></tr>';
                }else{
                    $table.= '<tr class="text-center"> <td colspan="8"> No hay registros en el Sistema</td></tr>';
                }
            }
            $table.= '</tbody></table></div>';

            if($total>=1 && $actualPage<=$nPages){
                $table.='<p class="text-right">Mostrando productos '.$reg_inicio.' al '.$reg_final.'
                de un total de '.$total.'</p>';

                $table.=mainModel::paginador($actualPage, $nPages, $url, 7);
            }

            return $table;
        } //FIN CONTROLADOR

        public function deleteItemController(){
            // RECUPERAR EL ID del producto a eliminar
            $idDelete = mainModel::decryption($_POST['item_id_del']);
            $idDelete = mainModel::stringClear($idDelete);

            // COMPROBAR EL PRODUCTO EN LA BD
            $checkItem = mainModel::sqlConsult_Simple("SELECT item_id FROM item
            WHERE item_id = '$idDelete'");

            if($checkItem->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encontramos el producto que intenta eliminar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            // COMPROBAMOS QUE EL ITEM NO ESTE EN ALGUN DETALLE DE ALGUN PPRESTAMO
            $checkPrestamo = mainModel::sqlConsult_Simple("SELECT item_id FROM detalle
            WHERE item_id = '$idDelete' LIMIT 1");

            if($checkPrestamo->rowCount()<0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos eliminar el producto ya que tiene prestamos asociados",
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
                    "message"=>"No tienes los permisos necesarios para eliminar productos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            $deleteItem = itemModel::deleteItemModel($idDelete);
            if($deleteItem->rowCount()==1){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Excelente!!",
                    "message"=>"Producto eliminado correctamente!",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No se ha podido eliminar el producto, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR

        public function dataItemController($type, $id){
            $type = mainModel::stringClear($type);

            $id = mainModel::decryption($id);
            $id = mainModel::stringClear($id);

            return itemModel::dataItemModel($type, $id);
        } //FIN CONTROLADOR DATAITEM

        public function updateItemController(){
            // RECIBIENDO EL ID DEL producto
            $idUpdate = mainModel::decryption($_POST['item_id_up']);
            $idUpdate = mainModel::stringClear($idUpdate);

            //COMPRIBAR PRODCUTO EN LA BD
            $checkItem = mainModel::sqlConsult_Simple("SELECT * FROM item WHERE item_id = '$idUpdate'");

            if($checkItem->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos encontrar el producto que deseas actualizar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }else{
                $dataArray = $checkItem->fetch();
            }

            $code = mainModel::stringClear($_POST['item_codigo_up']);
            $name = mainModel::stringClear($_POST['item_nombre_up']);
            $stock = mainModel::stringClear($_POST['item_stock_up']);
            $status = mainModel::stringClear($_POST['item_estado_up']);
            $detail = mainModel::stringClear($_POST['item_detalle_up']);

            // COMPROBAR CAMPOES VACIOS
            if($code == "" || $name == "" || $stock == ""||
            $status == "" || $detail == "" ){
                $alert=[
                    'Alerta'=>'simple',
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error",
                ];
                return json_encode($alert);
                exit();
            }

            // COMBROBAR QUE LOS DATOS CORRESPONDAN A SUS FORMATOS DE LOS INPUT
             // VERIFICAR EL TIPADO DE CARACTERES
             if(mainModel::validationData("[a-zA-Z0-9-]{1,45}",$code)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del CODIGO no son validos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$name)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del NOMBRE no son validos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9]{1,9}",$stock)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del STOCK no son validos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if($status != "Habilitado" && $status != "Deshabilitado"){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El estado del producto no ha sido definido correctamente",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detail)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del DETALLE no son validos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBANDO QUE EL codigo NO ESTE REGISTRADO
            if($code != $dataArray['item_codigo']){
                $checkCode = mainModel::sqlConsult_Simple("SELECT item_codigo FROM
                item WHERE item_codigo = '$code'");
                if($checkCode->rowCount()>0){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El CODIGO del producto ya existe",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }
            if($name != $dataArray['item_nombre']){
                $checkName = mainModel::sqlConsult_Simple("SELECT item_nombre FROM
                item WHERE item_nombre = '$name'");
                if($checkName->rowCount()>0){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El NOMBRE del producto ya existe",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

           //  COMPROBAR LOS PRIVILEGIOS PARA ACTUALIZAR
           session_start(['name'=>'SV']);
           if($_SESSION['privilegio_sv'] <1 || $_SESSION['privilegio_sv'] >2){
               $alert=[
                   "Alerta"=>"simple",
                   "title"=>"Error",
                   "message"=>"No tienes permisos para actualizar los datos de los productos",
                   "type"=>"error",
               ];
               echo json_encode($alert);
               exit();
           }

            // PREPARAR LOS DATOS PARA ENVIARLOS AL MODELO
            $dataArrayItemUp = [
                "CODE" => $code,
                "NAME" => $name,
                "STOCK" => $stock,
                "STATUS" => $status,
                "DETAIL"=> $detail,
                "ID" => $idUpdate
            ];

            // ENVIARLOS AL MODELO
            if(itemModel::updateItemModel($dataArrayItemUp)){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Perfecto",
                    "message"=>"El producto ha sido actualizado correctamente",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No se ha podido actualizar los datos, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
       }
    }
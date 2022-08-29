<?php
    if($ajaxPeticions){
        require_once "../models/clientModel.php";
    }else{
        require_once "./models/clientModel.php";
    }

    class clientController extends clientModel{
        public function addClientController(){
            $dni = mainModel::stringClear($_POST['cliente_dni_reg']);
            $name = mainModel::stringClear($_POST['cliente_nombre_reg']);
            $lastName = mainModel::stringClear($_POST['cliente_apellido_reg']);
            $phone = mainModel::stringClear($_POST['cliente_telefono_reg']);
            $address = mainModel::stringClear($_POST['cliente_direccion_reg']);

            if($dni == "" || $name == "" || $lastName == ""||
            $phone == "" || $address == ""){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error"
                ];

                // convertir el array en json para que lo entienda js
                json_encode($alert);
                exit();
            }

            if(mainModel::validationData("[0-9-]{1,20}",$dni)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$name)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$lastName)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[0-9()+]{8,20}",$phone)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$address)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBANDO QUE EL ID NO ESTE REGISTRADO
            $checkDni = mainModel::sqlConsult_Simple("SELECT cliente_dni FROM cliente WHERE cliente_dni = '$dni'");
            if($checkDni->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El ID del cliente ya existe",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }

            // DATOS QUE SE ENVIAN AL MODELO
            $dataArray = [
                "DNI"=>$dni,
                "NAME"=>$name,
                "LASTNAME"=>$lastName,
                "PHONE"=>$phone,
                "ADDRESS"=>$address
            ];

            $addClient = clientModel::addClientModel($dataArray);

            if($addClient->rowCount()==1){
                $alert=[
                    "Alerta"=>"limpiar",
                    "title"=>"Cliente registrado",
                    "message"=>"Cliente registrado correctamente",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Lo sentimos",
                    "message"=>"No hemos podido registrar el Cliente",
                    "type"=>"warning"
                ];
            }
            echo json_encode($alert);

        } //FIN CONTROLADOR

        public function clientPaginatorController($actualPage, $registers, $privilegio, $url, $busqueda){
            $actualPage = mainModel::stringClear($actualPage);
            $registers = mainModel::stringClear($registers);
            $privilegio = mainModel::stringClear($privilegio);

            $url = mainModel::stringClear($url);
            // ENVIAMOS TODA LA URL COMPLETA
            $url = SERVERURL.$url."/";

            $busqueda = mainModel::stringClear($busqueda);

            $table = "";

            // VERIFICAMOS QUE ESTE EN UNA PAGINA, QUE ESTE DEFINIDA Y QUE SEA ENTERO.
            // determinar que sea un numero en la url y si corresponda a un numero entero valido
            // SI NO VIENE DEFINIA O NO ES UN NUMERO ENTERO, LE DECIMOS QUE SE UBIQUE EN  LA PAGINA UNO
            $actualPage = (isset($actualPage) && $actualPage> 0) ? (int) $actualPage : 1;

            //determinar en que pagina estoy
            $inicio = ($actualPage >0) ? (($actualPage * $registers) - $registers) : 0;

            if(isset($busqueda) && $busqueda != ""){
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE (cliente_dni LIKE '%$busqueda%'
                OR cliente_nombre LIKE '%$busqueda%'
                OR cliente_apellido LIKE '%$busqueda%')
                ORDER BY cliente_nombre ASC LIMIT $inicio, $registers";
            } else{
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente
                ORDER BY cliente_nombre ASC LIMIT $inicio, $registers";
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
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>TELEFONO</th>
                            <th>DIRECCIÓN</th>';
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
                                <td>'.$rows['cliente_dni'].'</td>
                                <td>'.$rows['cliente_nombre'].'</td>
                                <td>'.$rows['cliente_apellido'].'</td>
                                <td>'.$rows['cliente_telefono'].'</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="popover"
                                    data-trigger="hover" title="'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'"
                                    data-content="'.$rows['cliente_direccion'].'">
                                    <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>';
                                if($privilegio == 1 || $privilegio == 2){
                                    $table.='<td>
                                        <a href="'.SERVERURL.'client-update/'
                                        .mainModel::encryption($rows['cliente_id']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </td>';
                                }
                                if($privilegio == 1){
                                $table.='<td>
                                    <form action="'.SERVERURL.'ajax/clientAjax.php"
                                    class="FormularioAjax" method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="cliente_id_del"
                                    value="'.mainModel::encryption($rows['cliente_id']).'">
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
                $table.='<p class="text-right">Mostrando clientes '.$reg_inicio.' al '.$reg_final.'
                de un total de '.$total.'</p>';

                $table.=mainModel::paginador($actualPage, $nPages, $url, 7);
            }

            return $table;
        } //FIN CONTROLADOR CLIENTEPAGINADOR

        public function deleteClientController(){
            // RECUPERAR EL ID del cliente a eliminar
            $idDelete = mainModel::decryption($_POST['cliente_id_del']);
            $idDelete = mainModel::stringClear($idDelete);

            // COMPROBAR EL CLIENTE EN LA BD
            $checkClient = mainModel::sqlConsult_Simple("SELECT cliente_id FROM cliente
            WHERE cliente_id = '$idDelete'");

            if($checkClient->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encontramos el cliente que intenta eliminar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAR SI EL CLIENTE TIENE ALMENOS UN PRESTAMO ASOCIADO
            $checkPrestamo = mainModel::sqlConsult_Simple("SELECT cliente_id FROM prestamo
            WHERE cliente_id = '$idDelete' LIMIT 1");
            if($checkPrestamo->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos eliminar el cliente ya que tiene prestamos asociados",
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
                    "message"=>"No tienes los permisos necesarios para eliminar usuarios",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            $deleteClient = clientModel::deleteClientModel($idDelete);
            if($deleteClient->rowCount()==1){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Excelente!!",
                    "message"=>"Cliente eliminado correctamente!",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No se ha podido eliminar el cliente, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
            exit();
        } //FIN CONTROLADOR

        public function dataClientController($type, $id){
            $type = mainModel::stringClear($type);

            $id = mainModel::decryption($id);
            $id = mainModel::stringClear($id);

            return clientModel::dataClientModel($type, $id);
        } //FIN CONTROLADOR DATAUSER

        public function updateClientController(){
             // RECIBIENDO EL ID DEL USU
             $idUpdate = mainModel::decryption($_POST['cliente_id_up']);
             $idUpdate = mainModel::stringClear($idUpdate);
 
             //COMPRIBAR USUARIO EN LA BD
             $checkClient = mainModel::sqlConsult_Simple("SELECT * FROM cliente WHERE cliente_id = '$idUpdate'");
 
             if($checkClient->rowCount()<=0){
                 $alert=[
                     "Alerta"=>"simple",
                     "title"=>"Error",
                     "message"=>"No podemos encontrar el cliente que deseas actualizar",
                     "type"=>"error",
                 ];
                 echo json_encode($alert);
                 exit();
             }else{
                 $dataArray = $checkClient->fetch();
             }
 
             $dni = mainModel::stringClear($_POST['cliente_dni_up']);
             $name = mainModel::stringClear($_POST['cliente_nombre_up']);
             $lastName = mainModel::stringClear($_POST['cliente_apellido_up']);
             $phone = mainModel::stringClear($_POST['cliente_telefono_up']);
             $address = mainModel::stringClear($_POST['cliente_direccion_up']);
 
             // COMPROBAR CAMPOES VACIOS
             if($dni == "" || $name == "" || $lastName == ""||
             $phone == "" || $address == "" ){
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
              if(mainModel::validationData("[0-9-]{1,20}",$dni)){
                 $alert=[
                     "Alerta"=>"simple",
                     "title"=>"Error",
                     "message"=>"Los datos ingresados del DNI no son validos",
                     "type"=>"error",
                 ];
                 echo json_encode($alert);
                 exit();
             }
             if(mainModel::validationData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$name)){
                 $alert=[
                     "Alerta"=>"simple",
                     "title"=>"Error",
                     "message"=>"Los datos ingresados del NOMBRE no son validos",
                     "type"=>"error",
                 ];
                 echo json_encode($alert);
                 exit();
             }
             if(mainModel::validationData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$lastName)){
                 $alert=[
                     "Alerta"=>"simple",
                     "title"=>"Error",
                     "message"=>"Los datos ingresados del APELLIDO no son validos",
                     "type"=>"error",
                 ];
                 echo json_encode($alert);
                 exit();
             }
             if($phone != ""){
                 if(mainModel::validationData("[0-9()+]{8,20}",$phone)){
                     $alert=[
                         "Alerta"=>"simple",
                         "title"=>"Error",
                         "message"=>"Los datos ingresados no son validos",
                         "type"=>"error",
                     ];
                     echo json_encode($alert);
                     exit();
                 }
             }
             if($address != ""){
                 if(mainModel::validationData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$address)){
                     $alert=[
                         "Alerta"=>"simple",
                         "title"=>"Error",
                         "message"=>"Los datos ingresados no son validos",
                         "type"=>"error",
                     ];
                     echo json_encode($alert);
                     exit();
                 }
             }

             // COMPROBANDO QUE EL ID NO ESTE REGISTRADO
             if($dni != $dataArray['cliente_dni']){
                 $checkDni = mainModel::sqlConsult_Simple("SELECT cliente_dni FROM
                 cliente WHERE cliente_dni = '$dni'");
                 if($checkDni->rowCount()>0){
                     $alert=[
                         "Alerta"=>"simple",
                         "title"=>"Error",
                         "message"=>"El ID del cliente ya existe",
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
                    "message"=>"No tienes permisos para actualizar los datos de los clientes",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
 
             // PREPARAR LOS DATOS PARA ENVIARLOS AL MODELO
             $dataArrayClientUp = [
                 "DNI" => $dni,
                 "NAME" => $name,
                 "LASTNAME" => $lastName,
                 "PHONE" => $phone,
                 "ADDRESS"=> $address,
                 "ID" => $idUpdate
             ];
 
             // ENVIARLOS AL MODELO
             if(clientModel::updateClientModel($dataArrayClientUp)){
                 $alert=[
                     "Alerta"=>"recargar",
                     "title"=>"Perfecto",
                     "message"=>"El cliente ha sido actualizado correctamente",
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
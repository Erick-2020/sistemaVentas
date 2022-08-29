<?php
    if($ajaxPeticions){
        require_once "../models/usuModel.php";
    }else{
        require_once "./models/usuModel.php";
    }

    class usuController extends usuModel{
        // agregar usuario
        public function addUsuController(){
            // ALMACENAR LOS DATOS DEL FORMULARIO
            $dni = mainModel::stringClear($_POST['usuario_dni_reg']);
            $name = mainModel::stringClear($_POST['usuario_nombre_reg']);
            $apellido = mainModel::stringClear($_POST['usuario_apellido_reg']);
            $phone = mainModel::stringClear($_POST['usuario_telefono_reg']);
            $address = mainModel::stringClear($_POST['usuario_direccion_reg']);

            $usuario = mainModel::stringClear($_POST['usuario_usuario_reg']);
            $email = mainModel::stringClear($_POST['usuario_email_reg']);
            $password = mainModel::stringClear($_POST['usuario_clave_1_reg']);
            $password2 = mainModel::stringClear($_POST['usuario_clave_2_reg']);

            $privilegio = mainModel::stringClear($_POST['usuario_privilegio_reg']);

            // COMPROBAR CAMPOES VACIOS
            if($dni == "" || $name == "" || $apellido == ""||
            $usuario == "" || $password == "" || $password2  == ""){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error"
                ];

                // convertir el array en json para que lo entienda js
                return json_encode($alert);
                exit();
            }

            // VERIFICAR EL TIPADO DE CARACTERES
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
            if(mainModel::validationData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$apellido)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
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
                        "type"=>"error"
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
                        "type"=>"error"
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }
            if(mainModel::validationData("[a-zA-Z0-9]{1,35}",$usuario)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}",$password)
            || (mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}",$password2))){
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
            $checkDni = mainModel::sqlConsult_Simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = '$dni'");
            if($checkDni->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El ID del usuario ya existe",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            // COMPROBANDO QUE EL NOMBRE DE USUARIO NO ESTE REGISTRADO
            $checkUsu = mainModel::sqlConsult_Simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
            if($checkUsu->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El Nombre del usuario ya existe, cambielo",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            // COMPROBANDO EL CAMPO EMAIL NO ESTE VACIO
            if($email!=""){
                // EMAIL VALIDO
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    // COMPROBANDO QUE EL EMAIL DE USUARIO NO ESTE REGISTRADO
                    $checkEmail = mainModel::sqlConsult_Simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
                    if($checkEmail->rowCount()>0){
                        $alert=[
                            "Alerta"=>"simple",
                            "title"=>"Error",
                            "message"=>"El correo del usuario ingresado ya existe, cambielo",
                            "type"=>"warning"
                        ];
                        echo json_encode($alert);
                        exit();
                    }
                }else{
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El correo electronico no es valido, verifique el dato ingresado",
                        "type"=>"error"
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }
            // COMBROBAR IGUALDAD DE CONTRASEÑAS
            if($password != $password2){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Las claves ingresadas no corresponden, verifique el dato ingresado",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }else{
                $passwords = mainModel::encryption($password);
            }
            // COMPROBAR EL PRIVILEGIO
            if($privilegio<1 || $privilegio>3){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No has seleccionado el nivel de privilegio, verifique el dato ingresado",
                    "type"=>"warning"
                ];
                echo json_encode($alert);
                exit();
            }

            // DATOS QUE SE ENVIAN AL MODELO
            $dataArray = [
                "DNI"=>$dni,
                "NAME"=>$name,
                "LASTNAME"=>$apellido,
                "PHONE"=>$phone,
                "ADDRESS"=>$address,
                "EMAIL"=>$email,
                "USUARIO"=>$usuario,
                "PASSWORD"=>$passwords,
                "STATUS"=>"Activa",
                "PRIVILEGIO"=>$privilegio
            ];

            $addUser = usuModel::addUsuModel($dataArray);

            if($addUser->rowCount()==1){
                $alert=[
                    "Alerta"=>"limpiar",
                    "title"=>"Usuario registrado",
                    "message"=>"Usuario registrado correctamente",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Lo sentimos",
                    "message"=>"No hemos podido registrar el usuario",
                    "type"=>"warning"
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR DE ADDUSU

        // LISTADO PAGINADOR USUARIOS
        public function UsupaginatorController($actualPage, $registers, $privilegio, $id, $url, $busqueda){
            $actualPage = mainModel::stringClear($actualPage);
            $registers = mainModel::stringClear($registers);
            $privilegio = mainModel::stringClear($privilegio);
            $id = mainModel::stringClear($id);

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
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id != '$id' AND
                usuario_id != '1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%'
                OR usuario_apellido LIKE '%$busqueda%'))
                ORDER BY usuario_nombre ASC LIMIT $inicio, $registers";
            } else{
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id != '$id' AND
                usuario_id != '1' ORDER BY usuario_nombre ASC LIMIT $inicio, $registers";
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
                                <th>TELÉFONO</th>
                                <th>USUARIO</th>
                                <th>EMAIL</th>
                                <th>ACTUALIZAR</th>
                                <th>ELIMINAR</th>
                            </tr>
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
                                <td>'.$rows['usuario_dni'].'</td>
                                <td>'.$rows['usuario_nombre'].'</td>
                                <td>'.$rows['usuario_apellido'].'</td>
                                <td>'.$rows['usuario_telefono'].'</td>
                                <td>'.$rows['usuario_usuario'].'</td>
                                <td>'.$rows['usuario_email'].'</td>
                                <td>
                                    <a href="'.SERVERURL.'user-update/'
                                    .mainModel::encryption($rows['usuario_id']).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="'.SERVERURL.'ajax/usuAjax.php"
                                    class="FormularioAjax" method="POST" data-form="delete"
                                    autocomplete="off">
                                    <input type="hidden" name="usuario_id_del"
                                    value="'.mainModel::encryption($rows['usuario_id']).'">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    </form>
                                </td>
				            </tr>';
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
                $table.='<p class="text-right">Mostrando usuarios '.$reg_inicio.' al '.$reg_final.'
                de un total de '.$total.'</p>';

                $table.=mainModel::paginador($actualPage, $nPages, $url, 7);
            }

            return $table;
        } //FIN CONTROLADOR USUPAGINADOR

        // ELIMINAR USUARIO
        public function deleteUsuController(){
            // RECIBIMOS EL ID PROCESADO POR HASH QUE VIENE ENCRIPTADO DE NUESTRO CONTROLADOR DEL PAGINADOR
            $idDelete = mainModel::decryption($_POST['usuario_id_del']);
            $idDelete = mainModel::stringClear($idDelete);

            // COMPROBAR SI ES EL ID UNO O NO
            if($idDelete == 1){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos eliminar el usuario principal",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            // COMPROBAR EL USUARIO EN LA BD
            $checkUser = mainModel::sqlConsult_Simple("SELECT usuario_id FROM usuario
            WHERE usuario_id = '$idDelete'");

            if($checkUser->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No encontramos el usuario que intenta eliminar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBAR SI EL USUARIO TIENE ALMENOS UN PRESTAMO ASOCIADO
            $checkPrestamo = mainModel::sqlConsult_Simple("SELECT usuario_id FROM prestamo
            WHERE usuario_id = '$idDelete' LIMIT 1");

            if($checkPrestamo->rowCount()>0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos eliminar el usuario ya que tiene prestamos asociados",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBANDO PRIVILEGIOS
            // SOLAMENTE LOS QUE TIENEN PRIVILEGIOS NIVEL 1 (ADMIN) PUEDEN ELIMINAR
            session_start(['name'=>'SV']);

            if($_SESSION['privilegio_sv']!=1){
                if($checkPrestamo->rowCount()>0){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"No tienes los permisos necesarios para eliminar usuarios",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            $deleteUser = usuModel::deleteUsuModel($idDelete);
            if($deleteUser->rowCount()==1){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Excelente!!",
                    "message"=>"¡Usuario eliminado correctamente!",
                    "type"=>"success",
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No se ha podido eliminar el usuario, por favor intente nuevamente",
                    "type"=>"error",
                ];
            }
            echo json_encode($alert);
            exit();
        } //FIN CONTROLADOR DELETEUSU

        // DATOS DEL USUARIO
        public function dataUserController($type, $id){
            $type = mainModel::stringClear($type);

            $id = mainModel::decryption($id);
            $id = mainModel::stringClear($id);

            return usuModel::dataUserModel($type, $id);
        } //FIN CONTROLADOR DATAUSER

        public function updateUsuController(){
            // RECIBIENDO EL ID DEL USU
            $idUpdate = mainModel::decryption($_POST['usuario_id_up']);
            $idUpdate = mainModel::stringClear($idUpdate);

            //COMPRIBAR USUARIO EN LA BD
            $checkUser = mainModel::sqlConsult_Simple("SELECT * FROM usuario WHERE usuario_id = '$idUpdate'");

            if($checkUser->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No podemos encontrar el usuario que deseas actualizar",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }else{
                $dataArray = $checkUser->fetch();
            }

            $dni = mainModel::stringClear($_POST['usuario_dni_up']);
            $name = mainModel::stringClear($_POST['usuario_nombre_up']);
            $lastName = mainModel::stringClear($_POST['usuario_apellido_up']);
            $phone = mainModel::stringClear($_POST['usuario_telefono_up']);
            $address = mainModel::stringClear($_POST['usuario_direccion_up']);
            $usu = mainModel::stringClear($_POST['usuario_usuario_up']);
            $email = mainModel::stringClear($_POST['usuario_email_up']);

            if(isset($_POST['usuario_estado_up']) || isset($_POST['usuario_privilegio_up'])){
                $status = mainModel::stringClear($_POST['usuario_estado_up']);
                $privilegio = mainModel::stringClear($_POST['usuario_privilegio_up']);
            }else{
                // SI EL VALOR DE ESTADO NO ESTA DEFINIDO, TOMAMOS POR DEFECTO EL VALOR DE LA BD
                $status = $dataArray['usuario_estado'];
                $privilegio = $dataArray['usuario_privilegio'];
            }

            // usuario y contraseña para guardar los datos
            $adminUser = mainModel::stringClear($_POST['usuario_admin']);
            $adminPassword = mainModel::stringClear($_POST['clave_admin']);

            $AccountType = mainModel::stringClear($_POST['tipo_cuenta']);

            // COMPROBAR CAMPOES VACIOS
            if($dni == "" || $name == "" || $lastName == ""||
            $usu == "" || $adminUser == "" || $adminPassword == "" ){
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
            if(mainModel::validationData("[a-zA-Z0-9]{1,35}",$usu)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del usuario no son validos",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            if(mainModel::validationData("[a-zA-Z0-9]{1,35}",$adminUser)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Tu nombre de usuario no es valido",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}",$adminPassword)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Tu contraseña de usuario no es valido",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }
            // ENCRIPTAMOS LA CONTRASEÑA QUE VAMOS A MODIFICAR
            $adminPassword = mainModel::encryption($adminPassword);

            // verificacion del nivel de privilegio
            if($privilegio<1 || $privilegio>3){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El nivel de privilegio no ha sido definido correctamente",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            if($status != "Activa" && $status != "Deshabilitada"){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"El estado del usuario no ha sido definido correctamente",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // COMPROBANDO QUE EL ID NO ESTE REGISTRADO
            if($dni != $dataArray['usuario_dni']){
                $checkDni = mainModel::sqlConsult_Simple("SELECT usuario_dni FROM
                usuario WHERE usuario_dni = '$dni'");
                if($checkDni->rowCount()>0){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El ID del usuario ya existe",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            // COMPROBANDO QUE EL NOMBRE DE USUARIO NO ESTE REGISTRADO
            if($usu != $dataArray['usuario_usuario']){
                $checkUsu = mainModel::sqlConsult_Simple("SELECT usuario_usuario FROM
                usuario WHERE usuario_usuario = '$usu'");
                if($checkUsu->rowCount()>0){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"El Nombre del usuario ya existe, cambielo",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            // COMPROBAMOS EL EMAIL
            if($email != $dataArray['usuario_email'] && $email != ""){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $checkEmail = mainModel::sqlConsult_Simple("SELECT usuario_email FROM usuario
                    WHERE usuario_email = '$email'");
                    if($checkEmail->rowCount()>0){
                        $alert=[
                            "Alerta"=>"simple",
                            "title"=>"Error",
                            "message"=>"El nuevo email ya se encuentra registrado en el sistema",
                            "type"=>"error",
                        ];
                        echo json_encode($alert);
                        exit();
                    }
                }else{
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"A ingresado un correo electronico no valido",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
            }

            // COMPROBAMOS CLAVES
            if($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "" ){
                if($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"Las contraseñas no coinciden para actualizarlas",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }else{
                    if(mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_1'])
                    || mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_2'])){
                        $alert=[
                            "Alerta"=>"simple",
                            "title"=>"Error",
                            "message"=>"Las nuevas contraseñas no coinciden para actualizarlas",
                            "type"=>"error",
                        ];
                        echo json_encode($alert);
                        exit();
                    }
                    $Newpassword = mainModel::encryption($dataArray['usuario_clave']);
                }
            }else{
                // Si no viene definida le pasamos la misma clave que ya esta en la bd
                $Newpassword = $dataArray['usuario_clave'];
            }

            // COMPROBAR CREDENCIALES PARA ACTUALIZAR LOS DATOS
            if($AccountType == "Propia"){
                //CUANDO LA CUENTA SEA PROPIA VERIFICAR QUE LOS DATOS SEAN TOTALMENTE IGUALES
                $checkAccount = mainModel::sqlConsult_Simple("SELECT usuario_id FROM usuario
                WHERE usuario_usuario = '$adminUser' AND usuario_clave = '$adminPassword'
                AND usuario_id = '$idUpdate'");
            }else{
                // CUANDO NO SEA PROPIA
                // PRIMERO VERIFICAMOS SI TIENE O NO PERMISOS
                session_start(['name'=>'SV']);
                if($_SESSION['privilegio_sv'] != 1){
                    $alert=[
                        "Alerta"=>"simple",
                        "title"=>"Error",
                        "message"=>"No tienes permisos para actualizar",
                        "type"=>"error",
                    ];
                    echo json_encode($alert);
                    exit();
                }
                // SI TIENE PERMISOS REALIZAMOS ESTA CONSULTA
                $checkAccount = mainModel::sqlConsult_Simple("SELECT usuario_id FROM usuario
                WHERE usuario_usuario = '$adminUser' AND usuario_clave = '$adminPassword'");
            }

            // Contar cuantas filas fueron aceptadas
            if($checkAccount->rowCount()<=0){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Nombre y clave de administrador no validas",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            }

            // PREPARAR LOS DATOS PARA ENVIARLOS AL MODELO
            $dataArrayUsuUp = [
                "DNI" => $dni,
                "NAME" => $name,
                "LASTNAME" => $lastName,
                "PHONE" => $phone,
                "ADDRESS"=> $address,
                "EMAIL" => $email,
                "USER" => $adminUser,
                "PASSWORD" => $adminPassword,
                "STATUS" => $status,
                "PRIVILEGIO" => $privilegio,
                "ID" => $idUpdate
            ];

            // ENVIARLOS AL MODELO
            if(usuModel::updateUsuModel($dataArrayUsuUp)){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Perfecto",
                    "message"=>"El usuario ha sido actualizado correctamente",
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


        } //FIN DEL CONTROLADOR UPDATEUSU
    }
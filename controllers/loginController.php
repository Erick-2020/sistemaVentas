<?php
    if($ajaxPeticions){
        require_once "../models/loginModel.php";
    }else{
        require_once "./models/loginModel.php";
    }

    class loginController extends loginModel{
        // CONTROLADOR PARA EL INICIO DE SESION
        public function loginSesionController(){
            $usuario = mainModel::stringClear($_POST['usuarioLog']);
            $password = mainModel::stringClear($_POST['passwordLog']);

            // COMPROBAR LOS CAMPOS VACIOS
            if($usuario == "" || $password == ""){
                echo '
                <script>
                    Swal.fire({
                        title: "ERROR",
                        text: "Escribe las credenciales para el ingreso",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    })
                </script>
                ';
                exit();
            }

            // VALIDACIONES DE DATOS
            if(mainModel::validationData("[a-zA-Z0-9]{1,35}",$usuario)){
                echo '
                <script>
                    Swal.fire({
                        title: "ERROR",
                        text: "Nombre de usuario no valido",
                        icon: "warning",
                        confirmButtonText: "Aceptar"
                    })
                </script>
                ';
                exit();
            }
            if(mainModel::validationData("[a-zA-Z0-9$@.-]{7,100}",$password)){
                echo '
                <script>
                    Swal.fire({
                        title: "ERROR",
                        text: "La clave de usuario no es valida",
                        icon: "warning",
                        confirmButtonText: "Aceptar"
                    })
                </script>
                ';
                exit();
            }

            // PROCESAMOS LA CLAVE ENCRIPTANDOLA
            $password = mainModel::encryption($password);

            // ARRAY DE DATOS PARA EL MODELO
            $dataArrayLogin = [
                "USUARIO"=>$usuario,
                "PASSWORD"=>$password
            ];

            $dataLogin=loginModel::loginSesionModel($dataArrayLogin);

            if($dataLogin->rowCount()==1){
                // PERMITIR HACER UN ARRAY DE DATOS CON EL MODELO
                $row = $dataLogin->fetch();
                // CRAMOS VARIABLES DE SESION
                session_start(['name'=>'SV']);

                // VARIABLE DE SESION PARA EL USUARIO QUE INICIE SESION
                $_SESSION['id_sv'] = $row['usuario_id'];
                $_SESSION['nombre_sv'] = $row['usuario_nombre'];
                $_SESSION['apellido_sv'] = $row['usuario_apellido'];
                $_SESSION['usuario_sv'] = $row['usuario_usuario'];
                $_SESSION['privilegio_sv'] = $row['usuario_privilegio'];

                // CERRAR SESION DE FORMA SEGURA
                // PROCESAR POR HASH(MD5) UN ID UNICO DE INICIO SESION Y PODER VOLVERLA A CERRAR CON DICHO ID
                $_SESSION['token_sv'] = md5(uniqid(mt_rand(),true));

                // verificamos si se envian encabezados por php
                if(headers_sent()){
                    echo "<script> window.location.href='".SERVERURL."home/'; </script>";
                }else{
                    return header("Location: ".SERVERURL."home/");
                }

            }else{
                echo '
                <script>
                    Swal.fire({
                        title: "ERROR",
                        text: "El USUARIO o CLAVE son incorrectos",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    })
                </script>'
                ;
                exit();
            } //FIN DEL CONTROLADOR
        } //FIN CONTROLADOR

        // CONTROLADOR FORZAR CIERRE DE SESION
        public function logoutSesionController(){
            // vaciar la sesion
            session_unset();
            // destruir la sesion
            session_destroy();
            // verificamos si se enviar encabezados por php
            if(headers_sent()){
                echo "<script> window.location.href='".SERVERURL."login/'; </script>";
            }else{
                return header("Location: ".SERVERURL."login/");
            }
        } //FIN CONTROLADOR

        public function closedSesion(){
            session_start(['name'=>'SV']);

            $token = mainModel::decryption($_POST['token']);
            $usuario = mainModel::decryption($_POST['usuario']);

            // VERIFICAR QUE LAS DOS VARIABLES DEL BTN DE CERRAR SESION SEAN OGUALES A LAS QUE
            // ESTAN ALMACENADAS EN LAS VARIABLES DE SESION
            if($token == $_SESSION['token_sv'] && $usuario == $_SESSION['usuario_sv']){
                session_unset();
                session_destroy();
                $alert = [
                    "Alerta"=>"redireccionar",
                    "url"=>SERVERURL."login/"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"error",
                    "message"=>"No se pudo cerrar la sesion del sistema",
                    "type"=>"error"
                ];
            }
            echo json_encode($alert);
        } //FIN CONTROLADOR

    }
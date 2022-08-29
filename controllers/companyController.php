<?php
    if($ajaxPeticions){
        require_once "../models/companyModel.php";
    }else{
        require_once "./models/companyModel.php";
    }

    class companyController extends companyModel{
        public function dataCompanyController(){
            return companyModel::dataCompanyModel();
        }

        public function addCompanyController(){
            $name = mainModel::stringClear($_POST['empresa_nombre_reg']);
            $email = mainModel::stringClear($_POST['empresa_email_reg']);
            $phone = mainModel::stringClear($_POST['empresa_telefono_reg']);
            $address = mainModel::stringClear($_POST['empresa_direccion_reg']);

            if($name == "" || $email == ""||
            $phone == "" || $address == ""){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error"
                ];
                echo json_endode($alert);
                exit();
            }
            if(mainModel::validationData("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ. ]{1,70}",$name)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados no son validos",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            // SI EL CORREO NO ES VALIDO COMPROBAMOS
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del correo no son validos",
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

            // COMPROBAMOS EMPRESAS REGISTRADAS
            $chechCompany = mainModel::sqlConsult_Simple("SELECT empresa_id FROM empresa");
            if($checkCompany->rowCount()>=1){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Ya existe una empresa registrada, no puedes registrar mas",
                    "type"=>"error"
                ];
                echo json_encode($alert);
                exit();
            }
            // DATOS QUE SE ENVIAN AL MODELO
            $dataArray = [
                "NAME"=>$name,
                "EMAIL"=>$email,
                "PHONE"=>$phone,
                "ADDRESS"=>$address
            ];

            $addCompany = companyModel::addCompanyModel($dataArray);

            if($addCompany->rowCount()==1){
                $alert=[
                    "Alerta"=>"limpiar",
                    "title"=>"Empresa registrada",
                    "message"=>"Empresa registrada correctamente",
                    "type"=>"success"
                ];
            }else{
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Lo sentimos",
                    "message"=>"No hemos podido registrar la Empresa",
                    "type"=>"warning"
                ];
            }
            echo json_encode($alert);
        }

        public function updateCompanyController(){
            $idUpdate = mainModel::stringClear($_POST['company_id_up']);
            $name = mainModel::stringClear($_POST['empresa_nombre_up']);
            $email = mainModel::stringClear($_POST['empresa_email_up']);
            $phone = mainModel::stringClear($_POST['empresa_telefono_up']);
            $address = mainModel::stringClear($_POST['empresa_direccion_up']);

            // COMPROBAR CAMPOES VACIOS
            if($name == "" || $email == ""|| $phone == "" || $address == "" ){
                $alert=[
                    'Alerta'=>'simple',
                    "title"=>"Error",
                    "message"=>"Debe llenar todos los campos obligatorios",
                    "type"=>"error",
                ];
                json_encode($alert);
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
            // SI EL CORREO NO ES VALIDO COMPROBAMOS
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"Los datos ingresados del correo no son validos",
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
            // COMPROBANDO PRIVILEGIOS
            session_start(['name'=>'SV']);
            if($_SESSION['privilegio_sv']<1 || $_SESSION['privilegio_sv']>2 ){
                $alert=[
                    "Alerta"=>"simple",
                    "title"=>"Error",
                    "message"=>"No tienes los permiso para editar la informacion de la empresa",
                    "type"=>"error",
                ];
                echo json_encode($alert);
                exit();
            } 

            $dataArrayCompanyUp = [
                "ID"=>$idUpdate,
                "NAME" => $name,
                "EMAIL" => $email,
                "PHONE" => $phone,
                "ADDRESS"=> $address,
            ];

            // ENVIARLOS AL MODELO
            if(companyModel::updateCompanyModel($dataArrayCompanyUp)){
                $alert=[
                    "Alerta"=>"recargar",
                    "title"=>"Perfecto",
                    "message"=>"La empresa ha sido actualizada correctamente",
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
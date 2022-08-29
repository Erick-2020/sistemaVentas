<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class usuModel extends mainModel{
        // agregar usuario
        protected static function addUsuModel($data){
            // consulta con los valores de la tabla sustituendolos por marcadores
            $sql =  mainModel::connection()->prepare("INSERT INTO usuario(usuario_dni,
            usuario_nombre, usuario_apellido, usuario_telefono, usuario_direccion, usuario_email,
            usuario_usuario, usuario_clave, usuario_estado, usuario_privilegio)
            VALUES(:DNI, :NAMES, :LASTNAME, :PHONE, :ADRESS, :EMAIL, :USUARIO, :PASSWORDS, :STATUSS, :PRIVILEGIO)");

            // sustituier el marcador por el valor de la tabla que se define en el array
            $sql-> bindParam(":DNI", $data['DNI']);
            $sql-> bindParam(":NAMES", $data['NAME']);
            $sql-> bindParam(":LASTNAME", $data['LASTNAME']);
            $sql-> bindParam(":PHONE", $data['PHONE']);
            $sql-> bindParam(":ADRESS", $data['ADDRESS']);
            $sql-> bindParam(":EMAIL", $data['EMAIL']);
            $sql-> bindParam(":USUARIO", $data['USUARIO']);
            $sql-> bindParam(":PASSWORDS", $data['PASSWORD']);
            $sql-> bindParam(":STATUSS", $data['STATUS']);
            $sql-> bindParam(":PRIVILEGIO", $data['PRIVILEGIO']);
            $sql-> execute();

            return $sql;
        }

        // eliminar usuario
        protected static function deleteUsuModel($idDelete){
            $sql = mainModel::connection()->prepare("DELETE FROM usuario WHERE usuario_id=:ID");
            $sql->bindParam(":ID", $idDelete);
            $sql->execute();
            return $sql;
        }

        // Datos del usuario
        protected static function dataUserModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM usuario WHERE usuario_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT usuario_id FROM usuario
                WHERE usuario_id !='1'");
            }
            $sql->execute();
            return $sql;
        }

        // ACTUALIZAR USUARIO
        protected static function updateUsuModel($dataArray){
            $sql = mainModel::connection()->prepare("UPDATE usuario SET usuario_dni=:DNI,
            usuario_nombre=:NAMES, usuario_apellido=:LASTNAME, usuario_telefono=:PHONE,
            usuario_direccion=:ADDRESSS, usuario_email=:EMAIL, usuario_usuario=:USER,
            usuario_clave=:PASSWORDS, usuario_estado=:STATUSS, usuario_privilegio=:PRIVILEGIO
            WHERE usuario_id =:ID");

            $sql->bindParam(":DNI", $dataArray['DNI']);
            $sql->bindParam(":NAMES", $dataArray['NAME']);
            $sql->bindParam(":LASTNAME", $dataArray['LASTNAME']);
            $sql->bindParam(":PHONE", $dataArray['PHONE']);
            $sql->bindParam(":ADDRESSS", $dataArray['ADDRESS']);
            $sql->bindParam(":EMAIL", $dataArray['EMAIL']);
            $sql->bindParam(":USER", $dataArray['USER']);
            $sql->bindParam(":PASSWORDS", $dataArray['PASSWORD']);
            $sql->bindParam(":STATUSS", $dataArray['STATUS']);
            $sql->bindParam(":PRIVILEGIO", $dataArray['PRIVILEGIO']);
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->execute();

            return $sql;

        }
    }
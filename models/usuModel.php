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
        // protected static function deleteUsuModel($id){
        //     $sql = mainModel::connection()->prepare()
        // }
    }
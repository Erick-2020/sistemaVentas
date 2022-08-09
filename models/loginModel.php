<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class loginModel extends mainModel{
        // MODELO PARA INICIAR SESION
        protected static function loginSesionModel($data){
            $sql = mainModel::connection()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :USUARIO
            AND usuario_clave = :PASSWORDS AND usuario_estado= 'Activa'");

            // CAMBIAR LOS MARCADORES POR LOS VALORES REALES
            $sql->bindParam(":USUARIO", $data['USUARIO']);
            $sql->bindParam(":PASSWORDS", $data['PASSWORD']);
            $sql->execute();

            return $sql;
        }
    }
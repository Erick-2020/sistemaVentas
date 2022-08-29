<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class clientModel extends mainModel{
        protected static function addClientModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO cliente(cliente_dni, cliente_nombre,
            cliente_apellido, cliente_telefono, cliente_direccion)
            VALUES(:DNI, :NAMES, :LASTNAME, :PHONE, :ADDRRESS)");

            $sql->bindParam(":DNI", $data['DNI']);
            $sql->bindParam(":NAMES", $data['NAME']);
            $sql->bindParam(":LASTNAME", $data['LASTNAME']);
            $sql->bindParam(":PHONE", $data['PHONE']);
            $sql->bindParam(":ADDRRESS", $data['ADDRESS']);
            $sql->execute();

            return $sql;
        }

        protected static function deleteClientModel($idDelete){
            $sql = mainModel::connection()->prepare("DELETE FROM cliente WHERE cliente_id=:ID");
            $sql->bindParam(":ID", $idDelete);
            $sql->execute();
            return $sql;
        }

        // RECIBE EL TIPO PARA IDENTIFICAR SI ES UNA SELECCION DE DATOS PARA EDITAR O PARA CONTAR CLIENTES
        // Y MOESTRAR SI TOTAL
        protected static function dataClientModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM cliente WHERE cliente_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT cliente_id FROM cliente");
            }
            $sql->execute();
            return $sql;
        }

        // ACTUALIZAR CLIENTE
        protected static function updateClientModel($dataArray){
            $sql = mainModel::connection()->prepare("UPDATE cliente SET cliente_dni=:DNI,
            cliente_nombre=:NAMES, cliente_apellido=:LASTNAME, cliente_telefono=:PHONE,
            cliente_direccion=:ADDRESSS WHERE cliente_id =:ID");

            $sql->bindParam(":DNI", $dataArray['DNI']);
            $sql->bindParam(":NAMES", $dataArray['NAME']);
            $sql->bindParam(":LASTNAME", $dataArray['LASTNAME']);
            $sql->bindParam(":PHONE", $dataArray['PHONE']);
            $sql->bindParam(":ADDRESSS", $dataArray['ADDRESS']);
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->execute();

            return $sql;
        }
    }
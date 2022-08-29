<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class itemModel extends mainModel{
        protected static function addItemModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO item(item_codigo, item_nombre, item_stock,
            item_estado, item_detalle) VALUES(:code, :names, :stock, :statuss, :details)");
            $sql->bindParam("code", $data['CODE']);
            $sql->bindParam("names", $data['NAME']);
            $sql->bindParam("stock", $data['STOCK']);
            $sql->bindParam("statuss", $data['STATUS']);
            $sql->bindParam("details", $data['DETAILS']);
            $sql->execute();

            return $sql;
        }

        protected static function deleteItemModel($idDelete){
            $sql = mainModel::connection()->prepare("DELETE FROM item WHERE item_id=:ID");
            $sql->bindParam(":ID", $idDelete);
            $sql->execute();
            return $sql;
        }

        // RECIBE EL TIPO PARA IDENTIFICAR SI ES UNA SELECCION DE DATOS PARA EDITAR O PARA CONTAR PRODUCTOS
        // Y MOESTRAR SU TOTAL
        protected static function dataItemModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM item WHERE item_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT item_id FROM item");
            }
            $sql->execute();
            return $sql;
        }

        // ACTUALIZAR CLIENTE
        protected static function updateItemModel($dataArray){
            $sql = mainModel::connection()->prepare("UPDATE item SET item_codigo=:CODE,
            item_nombre=:NAMES, item_stock=:STOCK, item_estado=:STATUSS,
            item_detalle=:DETAIL WHERE item_id =:ID");

            $sql->bindParam(":CODE", $dataArray['CODE']);
            $sql->bindParam(":NAMES", $dataArray['NAME']);
            $sql->bindParam(":STOCK", $dataArray['STOCK']);
            $sql->bindParam(":STATUSS", $dataArray['STATUS']);
            $sql->bindParam(":DETAIL", $dataArray['DETAIL']);
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->execute();

            return $sql;
        }
    }
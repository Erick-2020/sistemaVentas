<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class inventarioModel extends mainModel{
        protected static function addInventarioModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO inventarios(inventario_cantidad,
            usuario_id, item_nombre, usuario_nombre)
            VALUES(:CANTIDAD, :IDUSER, :ITEM, :NAMEUSER)");
            $sql->bindParam(":CANTIDAD", $data['CANTIDAD']);
            $sql->bindParam(":IDUSER", $data['IDUSER']);
            $sql->bindParam(":ITEM", $data['ITEM']);
            $sql->bindParam(":NAMEUSER", $data['NAMEUSER']);
            $sql->execute();

            return $sql;
        }

        // CON EL TIPO DEFINIMOS DE CUAL DE LAS TRES TABLAS QUEREMOS ELIMINAR LOS DATOS
        // YA SEA DE LA TABLA PRESTAMO, DETALLE O PAGO, YA QUE ESTAN RELACIANADAS
        // CON EL CODIGO ELIMINAMOS EL REGISTRO DE LA TABLA QUE ESTA RELACIONADA CON EL PRESTAMO
        protected static function deleteInventarioModel($idDelete){
            $sql = mainModel::connection()->prepare("DELETE FROM inventarios WHERE inventario_id =:ID");
            $sql->bindParam(":ID", $idDelete);
            $sql->execute();
            return $sql;
        }

        // SELECCION DE DATOS DE LAS TRES TABLAS DE RELACION PRESTAMOS, DETALLE Y PAGO
        protected static function dataInventarioModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM inventarios WHERE inventario_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT inventario_id FROM inventarios");
            }

            $sql->execute();
            return $sql;
        }
    }
<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class prestamosModel extends mainModel{
        protected static function addPrestamoModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO prestamo(prestamo_id, prestamo_codigo,
            prestamo_fecha_inicio, prestamo_hora_inicio, prestamo_fecha_final, prestamo_hora_final,
            prestamo_cantidad, prestamo_total, prestamo_pagado,prestamo_estado, prestamo_observacion,
            usuario_id, cliente_id)
            VALUES(:ID, :CODE, :DATEINICIO, :HOURINICIO, :DATEFINAL, :HOURFINAL, :CANTIDAD, :TOTAL,
            :PAGO, :STATUSS, :OBSERVATION, :IDUSER, :IDCLIENT)");
            $sql->bindParam(":ID", $data['ID']);
            $sql->bindParam(":CODE", $data['CODE']);
            $sql->bindParam(":DATEINICIO", $data['DATEINICIO']);
            $sql->bindParam(":HOURINICIO", $data['HOURINICIO']);
            $sql->bindParam(":DATEFINAL", $data['DATEFINAL']);
            $sql->bindParam(":HOURFINAL", $data['HOURFINAL']);
            $sql->bindParam(":CANTIDAD", $data['CANTIDAD']);
            $sql->bindParam(":TOTAL", $data['TOTAL']);
            $sql->bindParam(":PAGO", $data['PAGO']);
            $sql->bindParam(":STATUSS", $data['STATUS']);
            $sql->bindParam(":OBSERVATION", $data['OBSERVATION']);
            $sql->bindParam(":IDUSER", $data['IDUSER']);
            $sql->bindParam(":IDCLIENT", $data['IDCLIENT']);
            $sql->execute();

            return $sql;
        }

        protected static function deletePrestamoModel($idDelete){
            $sql = mainModel::connection()->prepare("DELETE FROM prestamo WHERE prestamo_id=:ID");
            $sql->bindParam(":ID", $idDelete);
            $sql->execute();
            return $sql;
        }

        protected static function dataPrestamoModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM prestamo WHERE prestamo_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT prestamo_id FROM prestamo");
            }
            $sql->execute();
            return $sql;
        }

        // ACTUALIZAR PRESTAMO
        protected static function updatePrestamoModel($dataArray){
            $sql = mainModel::connection()->prepare("UPDATE prestamo SET prestamo_id=:ID,
            prestamo_codigo=:CODE, prestamo_fecha_inicio=:DATEINICIO, prestamo_hora_inicio=:HOURINICIO,
            prestamo_fecha_final=:DATEFINAL, prestamo_hora_final=:HOURFINAL, prestamo_cantidad=:CANTIDAD,
            prestamo_total=:TOTAL, prestamo_pagado=:PAGO, prestamo_estado=:STATUSS,
            prestamo_observacion=:OBSERVATION, usuario_id=:IDUSER, cliente_id=:IDCLIENT
            WHERE prestamo_id =:ID");
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->bindParam(":CODE", $dataArray['CODE']);
            $sql->bindParam(":DATEINICIO", $dataArray['DATEINICIO']);
            $sql->bindParam(":HOURINICIO", $dataArray['HOURINICIO']);
            $sql->bindParam(":DATEFINAL", $dataArray['DATEFINAL']);
            $sql->bindParam(":HOURFINAL", $dataArray['HOURFINAL']);
            $sql->bindParam(":CANTIDAD", $dataArray['CANTIDAD']);
            $sql->bindParam(":TOTAL", $dataArray['TOTAL']);
            $sql->bindParam(":PAGO", $dataArray['PAGO']);
            $sql->bindParam(":STATUSS", $dataArray['STATUS']);
            $sql->bindParam(":OBSERVATION", $dataArray['OBSERVATION']);
            $sql->bindParam(":IDUSER", $dataArray['IDUSER']);
            $sql->bindParam(":IDCLIENT", $dataArray['IDCLIENT']);
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->execute();

            return $sql;
        }
    }
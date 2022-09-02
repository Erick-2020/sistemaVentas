<?php
    // modelo principal para conexion y demas funciones
    require_once "mainModel.php";

    class prestamosModel extends mainModel{
        protected static function addPrestamoModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO prestamo(prestamo_codigo,
            prestamo_fecha_inicio, prestamo_hora_inicio, prestamo_fecha_final, prestamo_hora_final,
            prestamo_cantidad, prestamo_total, prestamo_pagado,prestamo_estado, prestamo_observacion,
            usuario_id, cliente_id)
            VALUES(:CODE, :DATEINICIO, :HOURINICIO, :DATEFINAL, :HOURFINAL, :CANTIDAD, :TOTAL,
            :PAGO, :STATUSS, :OBSERVATION, :IDUSER, :IDCLIENT)");
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
        // MODELO DE LA TABLA DETALLE
        // SE REALIZA AQUI YA QUE ESTA RELACIONADA A LA HORA DE AGREGAR EL PRESTAMO
        protected static function addDetailModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO detalle(
                detalle_cantidad, detalle_formato, detalle_tiempo,
                detalle_costo_tiempo, detalle_descripcion, prestamo_codigo,
                item_id
            )
            VALUES (:CANTIDAD, :FORMATO, :TIEMPO, :COSTO, :DESCRIPCION, :CODEPRESTAMO, :IDITEM)");

            $sql->bindParam(":CANTIDAD", $data['CANTIDAD']);
            $sql->bindParam(":FORMATO", $data['FORMATO']);
            $sql->bindParam(":TIEMPO", $data['TIEMPO']);
            $sql->bindParam(":COSTO", $data['COSTO']);
            $sql->bindParam(":DESCRIPCION", $data['DESCRIPCION']);
            $sql->bindParam(":CODEPRESTAMO", $data['CODEPRESTAMO']);
            $sql->bindParam(":IDITEM", $data['IDITEM']);
            $sql->execute();

            return $sql;
        }
        // MODELO DE LA TABLA PAGO
        // SE REALIZA AQUI YA QUE ESTA RELACIONADA A LA HORA DE AGREGAR EL PRESTAMO Y EL DETALLE
        protected static function addPagoPrestamoModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO pago(
                pago_total, pago_fecha, prestamo_codigo)
            VALUES (:TOTAL, :FECHA, :CODEPRESTAMO)");

            $sql->bindParam(":TOTAL", $data['TOTAL']);
            $sql->bindParam(":FECHA", $data['FECHA']);
            $sql->bindParam(":CODEPRESTAMO", $data['CODEPRESTAMO']);
            $sql->execute();

            return $sql;
        }

        // CON EL TIPO DEFINIMOS DE CUAL DE LAS TRES TABLAS QUEREMOS ELIMINAR LOS DATOS
        // YA SEA DE LA TABLA PRESTAMO, DETALLE O PAGO, YA QUE ESTAN RELACIANADAS
        // CON EL CODIGO ELIMINAMOS EL REGISTRO DE LA TABLA QUE ESTA RELACIONADA CON EL PRESTAMO
        protected static function deletePrestamoModel($codigo, $tipo){
            if($tipo == "Prestamo"){
                $sql = mainModel::connection()->prepare("DELETE FROM prestamo WHERE prestamo_codigo=:CODIGO");
            }elseif($tipo == "Detalle"){
                $sql = mainModel::connection()->prepare("DELETE FROM detalle WHERE prestamo_codigo=:CODIGO");
            }elseif($tipo == "Pago"){
                $sql = mainModel::connection()->prepare("DELETE FROM pago WHERE prestamo_codigo=:CODIGO");
            }
            $sql->bindParam(":CODIGO",$codigo);
            $sql->execute();
            return $sql;
        }

        // SELECCION DE DATOS DE LAS TRES TABLAS DE RELACION PRESTAMOS, DETALLE Y PAGO
        protected static function dataPrestamoModel($type, $id){
            if($type == "Unico"){
                $sql = mainModel::connection()->prepare("SELECT * FROM prestamo WHERE prestamo_id =:ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "conteoReservacion"){
                $sql = mainModel::connection()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Reservacion'");
            }elseif($type == "conteoPrestamo"){
                $sql = mainModel::connection()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Prestamo'");
            }elseif($type == "conteoFinalizado"){
                $sql = mainModel::connection()->prepare("SELECT prestamo_id FROM prestamo WHERE prestamo_estado='Finalizado'");
            }elseif($type == "Conteo"){
                $sql = mainModel::connection()->prepare("SELECT prestamo_id FROM prestamo");
            }elseif($type == "Detalle"){
                $sql = mainModel::connection()->prepare("SELECT * FROM detalle WHERE prestamo_codigo=:CODIGO");
                $sql->bindParam(":CODIGO", $id);
            }elseif($type == "Pago"){
                $sql = mainModel::connection()->prepare("SELECT * FROM pago WHERE prestamo_codigo=:CODIGO");
                $sql->bindParam(":CODIGO", $id);
            }

            $sql->execute();
            return $sql;
        }

        // ACTUALIZAR PRESTAMO
        // SOLO ACTUALIZAREMOS CIERTOS DATOS DE LA TABLA
        protected static function updatePrestamoModel($dataArray){
            if($dataArray['Tipo'] == "Pago"){
                $sql = mainModel::connection()->prepare("UPDATE prestamo SET prestamo_pagado=:MONTO
                WHERE prestamo_codigo=:CODIGO");
                $sql->bindParam(":MONTO", $dataArray['MONTO']);
            }elseif($dataArray['Tipo'] == "Prestamo"){
                $sql = mainModel::connection()->prepare("UPDATE prestamo SET prestamo_estado=:ESTADO,
                prestamo_observacion=:OBSERVACION WHERE prestamo_codigo=:CODIGO");
                $sql->bindParam(":ESTADO", $dataArray['ESTADO']);
                $sql->bindParam(":OBSERVACION", $dataArray['OBSERVACION']);
            }
            $sql->bindParam(":CODIGO", $dataArray['CODIGO']);
            $sql->execute();

            return $sql;
        }
    }
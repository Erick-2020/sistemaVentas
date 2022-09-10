<?php
    if($_SESSION['privilegio_sv']<1 || $_SESSION['privilegio_sv']>2 ){
        echo $loginController->logoutSesionController();
		exit();
    }
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PRÉSTAMO
    </h3>
    <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium quod harum vitae, fugit quo soluta. Molestias officiis voluptatum delectus doloribus at tempore, iste optio quam recusandae numquam non inventore dolor.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRÉSTAMO</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt"></i> &nbsp; RESERVACIONES</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; PRÉSTAMOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>
<?php
	require_once "./controllers/prestamosController.php";
    $insPrestamo = new prestamosController();

	$dataPrestamo = $insPrestamo->dataPrestamoController("Unico", $page[1]);
	if($dataPrestamo->rowCount() == 1){
	    $dataArray = $dataPrestamo->fetch();

        // VALIDAR QUE EL PRESTAMO ESTA CANCELADO O FINALIZADO
        if($dataArray['prestamo_estado'] == 'Finalizado'
        && $dataArray['prestamo_pagado'] == $dataArray['prestamo_total']){

?>
            <div class="alert alert-danger text-center" role="alert">
                <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                <h4 class="alert-heading">¡No podemos actualizar!</h4>
                <p class="mb-0">Lo sentimos, no podemos actualizar el prestamo ya que ya esta Cancelado
                    y Finalizado
                </p>
            </div>
<?php
        }else{
?>
<div class="container-fluid">
    <?php
        if($dataArray['prestamo_pagado'] != $dataArray['prestamo_total']){
    ?>

    <div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR NUEVO PAGO A ESTE PRÉSTAMO</p>
            <p class="text-center">Este préstamo presenta un pago pendiente por la cantidad de
                <strong>
                    <?php
                        echo MONEDA.number_format(($dataArray['prestamo_total'] - $dataArray['prestamo_pagado'] ),0,'',',');
                    ?>
                </strong>,
                puede agregar un pago a este préstamo haciendo clic en el siguiente botón.</p>
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalPago">
                    <i class="far fa-money-bill-alt"></i>
                    &nbsp; Agregar pago
                </button>
            </p>
        </div>
        <?php
            }
        ?>
        <div class="container-fluid">
            <?php
                require_once "./controllers/clientController.php";
                $insCliente = new clientController();
            
                $dataCliente = $insCliente->dataClientController("Unico",
                $loginController->encryption($dataArray['cliente_id']));

                $dataCliente = $dataCliente->fetch();
            ?>
            <div>
                <span class="roboto-medium">CLIENTE:</span>
                &nbsp; <?php echo $dataCliente['cliente_nombre']." "
                .$dataCliente['cliente_apellido'] ?>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>ITEM</th>
                            <th>CANTIDAD</th>
                            <th>TIEMPO</th>
                            <th>COSTO</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dataDetalle = $insPrestamo->dataPrestamoController("Detalle",
                            $loginController->encryption($dataArray['prestamo_codigo']));

                            $dataDetalle = $dataDetalle->fetchAll();
                            foreach($dataDetalle as $detalles){
                                $costo = $detalles['detalle_cantidad'] * $detalles['detalle_costo_tiempo']
                                * $detalles['detalle_tiempo'];
                                $costo = number_format($costo,0,'',',');
                                echo '
                                <tr class="text-center" >
                                    <td>'.$detalles['detalle_descripcion'].'</td>
                                    <td>'.$detalles['detalle_cantidad'].'</td>
                                    <td>'.$detalles['detalle_tiempo']." ".$detalles['detalle_formato'].'</td>
                                    <td>'.MONEDA.number_format($detalles['detalle_costo_tiempo'],0,'',',').'</td>
                                    <td>'.MONEDA.$costo.'</td>
                                </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamosAjax.php"
	        data-form="update" method="POST" autocomplete="off">
            <input type="hidden" name="prestamo_codigo_up"
            value="<?php echo $loginController->encryption($dataArray['prestamo_codigo']); ?>">
            <fieldset>
                <legend><i class="far fa-clock"></i> &nbsp; Fecha y hora de préstamo</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_fecha_inicio">Fecha de préstamo</label>
                                <input type="date" class="form-control" readonly="" id="prestamo_fecha_inicio"
                                value="<?php echo $dataArray['prestamo_fecha_inicio']; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_hora_inicio">Hora de préstamo</label>
                                <input type="text" class="form-control" readonly="" id="prestamo_hora_inicio"
                                value="<?php echo $dataArray['prestamo_hora_inicio']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><i class="fas fa-history"></i> &nbsp; Fecha y hora de entrega</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_fecha_final">Fecha de entrega</label>
                                <input type="date" class="form-control" readonly="" id="prestamo_fecha_final"
                                value="<?php echo $dataArray['prestamo_fecha_final']; ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_hora_final">Hora de entrega</label>
                                <input type="text" class="form-control" readonly="" id="prestamo_hora_final"
                                value="<?php echo $dataArray['prestamo_hora_final']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_estado" class="bmd-label-floating">*** Estado ***</label>
                                <select class="form-control" name="prestamo_estado_up" id="prestamo_estado">
                                    <option value="Reservacion"
                                    <?php if($dataArray['prestamo_estado'] == 'Reservacion'){
                                        echo 'selected=""';
                                    } ?>>
                                        Reservación
                                        <?php if($dataArray['prestamo_estado'] == 'Reservacion'){
                                        echo '(Actual)';
                                        } ?>
                                    </option>
                                    <option value="Prestamo"
                                    <?php if($dataArray['prestamo_estado'] == 'Prestamo'){
                                        echo 'selected=""';
                                    } ?>>
                                        Préstamo
                                        <?php if($dataArray['prestamo_estado'] == 'Prestamo'){
                                        echo '(Actual)';
                                        } ?>
                                    </option>
                                    <option value="Finalizado"
                                    <?php if($dataArray['prestamo_estado'] == 'Finalizado'){
                                        echo 'selected=""';
                                    } ?>>
                                        Finalizado
                                        <?php if($dataArray['prestamo_estado'] == 'Finalizado'){
                                        echo '(Actual)';
                                        } ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_total" class="bmd-label-floating">Total a pagar en <?php echo MONEDA;?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly=""
                                value="<?php echo $costo; ?>" id="prestamo_total" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prestamo_pagado" class="bmd-label-floating">Total depositado en <?php echo MONEDA;?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly=""
                                value="<?php echo number_format($dataArray['prestamo_pagado'],0,'',','); ?>" id="prestamo_pagado" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="prestamo_observacion" class="bmd-label-floating">*** Observación ***</label>
                                <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}"
                                class="form-control" name="prestamo_observacion_up" id="prestamo_observacion"
                                maxlength="400" value="<?php echo $dataArray['prestamo_observacion']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br><br><br>
            <p class="text-center" style="margin-top: 40px;">
                <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
            </p>
        </form>
    </div>

    <!-- MODAL PAGOS -->
    <div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-labelledby="ModalPago" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/prestamosAjax.php"
	        data-form="save" method="POST" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPago">Agregar pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" >
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr class="text-center bg-dark">
                                    <th>FECHA</th>
                                    <th>MONTO</th>
                                </tr>
                            </thead>

                            <?php
                                $datosPago = $insPrestamo->dataPrestamoController("Pago",
                                $loginController->encryption($dataArray['prestamo_codigo']));

                                // validamos si hay pagos ingresados
                                if($datosPago->rowCount()>0){
                                    $datosPago = $datosPago->fetchAll();
                                    foreach($datosPago as $pagos){
                                        echo '
                                            <tr class="text-center">
                                                <th>'.date("d-m-Y",
                                                strtotime($pagos['pago_fecha'])).'</th>
                                                <th>'.MONEDA.$pagos['pago_total'].'</th>
                                            </tr>
                                        ';
                                    }
                                }else{
                            ?>

                            <tbody>
                                <tr class="text-center">
                                    <td colspan="2">No hay pagos registrados</td>
                                </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <input type="hidden" name="pago_codigo_reg" value="<?php
                        echo $loginController->encryption($dataArray['prestamo_codigo']); ?>">
                        <div class="form-group">
                            <label for="pago_monto_reg" class="bmd-label-floating">
                                Monto en <?php echo MONEDA; ?>
                            </label>
                            <input type="text" pattern="[0-9.]{1,10}" class="form-control"
                            name="pago_monto_reg" id="pago_monto_reg" maxlength="10" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-info btn-sm" >Agregar pago</button> &nbsp;&nbsp; 
                    <button type="button" class="btn btn-raised btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
<?php
        }
    }else{
?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
    <?php
        }
    ?>
</div>
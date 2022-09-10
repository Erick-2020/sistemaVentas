<?php
	$ajaxPeticions = true;
    require_once "../config/APP.php";

	// RECIBIMOS EL ID DE LA URL QUE QUEREMOS OBTENER
	// SI EL PARAMETRO ID VIENE DEFINIDO ENTOCES HACEMOS EL CODIFO DE ? SINO HACEMOS :
	$id = (isset($_GET['id'])) ? $_GET['id'] : 0 ;

	// instaciamos el controlador prestamo
	require_once "../controllers/prestamosController.php";
    $insPrestamo = new prestamosController();

	$datosPrestamo = $insPrestamo->dataPrestamoController("Unico", $id);

	// Verificamos que el prestamo exista para mostrar la factura
	if($datosPrestamo->rowCount() == 1){
		// HACEMOS UN ARRAY CON LOS DATOS
		$datosPrestamo = $datosPrestamo->fetch();

		// VAMOS A REALIZAR lA INSTANCIA Y ARRAY DE
		// DATOS DE LOS DATOS DE EMPRESA QUE QUEREMOS MOSTRAR EN LA FACTURA
		require_once "../controllers/companyController.php";
    	$insEmpresa = new companyController();

		$datosEmpresa = $insEmpresa->dataCompanyController();
		$datosEmpresa = $datosEmpresa->fetch();

		// VAMOS A REALIZAR lA INSTANCIA Y ARRAY DE
		// DATOS DE LOS DATOS DE USUARIO QUE QUEREMOS MOSTRAR EN LA FACTURA

		//usuario o admin que creo la factura
		require_once "../controllers/usuController.php";
    	$insUsu = new usuController();

		$datosUsuario = $insUsu->dataUserController(
			"Unico",$insUsu->encryption($datosPrestamo['usuario_id'])
		);
		$datosUsuario = $datosUsuario->fetch();

		//OBTENEMOS TODOS LOS DATOS DEL CLEINTE Y HACEMOS EL ARRAY
		require_once "../controllers/clientController.php";
    	$insCliente = new clientController();

		$datosCliente = $insCliente->dataClientController(
			"Unico",$insCliente->encryption($datosPrestamo['cliente_id'])
		);
		$datosCliente = $datosCliente->fetch();

	require "./fpdf.php";

	$pdf = new FPDF('P','mm','Letter');
	$pdf->SetMargins(17,17,17);
	$pdf->AddPage();
	$pdf->Image('../views/assets/img/logo.png',10,10,30,30,'PNG');

	$pdf->SetFont('Arial','B',18);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(strtoupper($datosEmpresa['empresa_nombre'])),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(-35,10,utf8_decode('N. de factura'),'',0,'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial','',15);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(""),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(-35,10,utf8_decode($datosPrestamo['prestamo_id']),'',0,'C');

	$pdf->Ln(25);

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(36,8,utf8_decode('Fecha de emisión:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(27,8,utf8_decode(date("d/m/Y", strtotime($datosPrestamo['prestamo_fecha_inicio']))),0,0);
	$pdf->Ln(8);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(27,8,utf8_decode('Atendido por:'),"",0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(13,8,utf8_decode($datosUsuario['usuario_nombre']),0,0);

	$pdf->Ln(15);

	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,8,utf8_decode('Cliente:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(65,8,utf8_decode($datosCliente['cliente_nombre']),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(10,8,utf8_decode('DNI:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(40,8,utf8_decode($datosCliente['cliente_dni']),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(19,8,utf8_decode('Teléfono:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(35,8,utf8_decode($datosCliente['cliente_telefono']),0,0);
	$pdf->SetTextColor(33,33,33);

	$pdf->Ln(8);

	$pdf->Cell(8,8,utf8_decode('Dir:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(109,8,utf8_decode($datosCliente['cliente_direccion']),0,0);

	$pdf->Ln(15);

	$pdf->SetFillColor(38,198,208);
	$pdf->SetDrawColor(38,198,208);
	$pdf->SetTextColor(33,33,33);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,10,utf8_decode('Cant.'),1,0,'C',true);
	$pdf->Cell(90,10,utf8_decode('Descripción'),1,0,'C',true);
	$pdf->Cell(51,10,utf8_decode('Tiempo - Costo'),1,0,'C',true);
	$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

	$pdf->Ln(10);

	$pdf->SetTextColor(97,97,97);

	//detalles del prestamo
	$datosDetalle = $insPrestamo->dataPrestamoController(
		"Detalle", $insPrestamo->encryption($datosPrestamo['prestamo_codigo'])
	);
	$datosDetalle = $datosDetalle->fetchAll();

	$total = 0;
	foreach($datosDetalle as $productos){
		$subTotal = $productos['detalle_cantidad'] * $productos['detalle_costo_tiempo']
            * $productos['detalle_tiempo'];
        $subTotal = number_format($subTotal,0,'','');

		$pdf->Cell(15,10,utf8_decode($productos['detalle_cantidad']),'L',0,'C');
		$pdf->Cell(90,10,utf8_decode($productos['detalle_descripcion']),'L',0,'C');
		$pdf->Cell(51,10,utf8_decode($productos['detalle_tiempo']." ".$productos['detalle_formato']."
		(".MONEDA.number_format($productos['detalle_costo_tiempo'],0,'',',')." c/u)"),'L',0,'C');
		$pdf->Cell(25,10,utf8_decode(MONEDA.number_format($subTotal,0,'',',')),'LR',0,'C');
		$pdf->Ln(10);

		$total += $subTotal;
	}

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(90,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(51,10,utf8_decode('TOTAL'),'LTB',0,'C');
	$pdf->Cell(25,10,utf8_decode(MONEDA.number_format($total,0,'',',')),'LRTB',0,'C');

	$pdf->Ln(15);

	$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: ".$datosPrestamo['prestamo_observacion']),0,'J',false);

	$pdf->SetFont('Arial','',12);
	if($datosPrestamo['prestamo_pagado'] < $datosPrestamo['prestamo_total']){
		$pdf->Ln(12);

		$pdf->SetTextColor(97,97,97);
		$pdf->MultiCell(0,9,utf8_decode("NOTA IMPORTANTE:
		\nEsta factura presenta un saldo pendiente de pago por la cantidad de ".MONEDA.number_format((
			$datosPrestamo['prestamo_total']-$datosPrestamo['prestamo_pagado']
		),0,'',',')),0,'J',false);
	}

	$pdf->Ln(25);

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(0,6,utf8_decode($datosEmpresa['empresa_nombre']),0,0,'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,6,utf8_decode($datosEmpresa['empresa_direccion']),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Teléfono: ".$datosEmpresa['empresa_telefono']),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Correo: ".$datosEmpresa['empresa_email']),0,0,'C');


	$pdf->Output("I","Factura_".$datosPrestamo['prestamo_id'].".pdf",true);

	}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo COMPANY; ?></title>
	<?php include "../views/inc/styleLink.php"; ?>
</head>
<body>
	<div class="full-box container-404">
		<div>
			<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
			<h1 class="text-center">ERROR</h1>
			<p class="lead text-center">No hemos encontrado el prestamo seleccionado</p>
		</div>
	</div>
	<?php
		include "../views/inc/script.php";

	?>
</body>
</html>


<?php } ?>
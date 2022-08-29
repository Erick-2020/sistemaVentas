<?php
	if($_SESSION['privilegio_sv'] != 1){
		echo $loginController->logoutSesionController();
		exit();
	}
?>
	<!-- Page header -->
	<div class="full-box page-header">
		<h3 class="text-left">
			<i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO
		</h3>
		<p class="text-justify">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
		</p>
	</div>


	<div class="container-fluid">
		<ul class="full-box list-unstyled page-nav-tabs">
			<li>
				<a href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
			</li>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
			</li>
		</ul>
	</div>

	<?php
	// SI LA VARIABLE DE SESION NO ESTA DEFINIDA O NO EXISTE
	if(!isset($_SESSION['busqueda_usuario']) && empty($_SESSION['busqueda_usuario']) ){
	?>
	<!-- Content -->
	<div class="container-fluid">
	<form class="FormularioAjax form-neon" action="<?php echo SERVERURL; ?>ajax/searchAjax.php"
	method="POST" data-form="search" autocomplete="off">
			<input type="hidden" name="modul" value="usuario">
			<div class="container-fluid">
				<div class="row justify-content-md-center">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="inputSearch" class="bmd-label-floating">¿Qué usuario estas buscando?</label>
							<input type="text" class="form-control" name="busquedaInicial" id="inputSearch" maxlength="30">
						</div>
					</div>
					<div class="col-12">
						<p class="text-center" style="margin-top: 40px;">
							<button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php }else{ ?>
	<div class="container-fluid">
		<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/searchAjax.php"
	method="POST" data-form="search" autocomplete="off">
		<input type="hidden" name="modul" value="usuario">
			<input type="hidden" name="eliminarBusqueda" value="eliminar">
			<div class="container-fluid">
				<div class="row justify-content-md-center">
					<div class="col-12 col-md-6">
						<p class="text-center" style="font-size: 20px;">
							Resultados de la busqueda <strong>
								“<?php echo $_SESSION['busqueda_usuario']; ?>”
							</strong>
						</p>
					</div>
					<div class="col-12">
						<p class="text-center" style="margin-top: 20px;">
							<button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="container-fluid">
	<?php
		require_once './controllers/usuController.php';
		$insUsu = new usuController();

		// UsupaginatorController($actualPage, $registers, $privilegio, $id, $url, $busqueda){
			// los array se cuentan desde 0,
			// 0 = vista y de 1 en adelante son las paginas de los datos que se muestran
			// la busqueda no se define ya que este es el listado general
		echo $insUsu->UsupaginatorController($page[1], 5, $_SESSION['privilegio_sv'], $_SESSION['id_sv'],
		$page[0],$_SESSION['busqueda_usuario'])
	?>
	</div>
	<?php } ?>
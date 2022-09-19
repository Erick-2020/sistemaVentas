<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR PRÉSTAMOS POR FECHA
	</h3>
	<p class="text-justify">
		Realiza búsqueda de todos tus vendedores de una manera más rápida y ágil, busca por su nombre,
        cantidad de productos otorgados o nombre de los productos.
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRÉSTAMO</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-vendedores/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; PRÉSTAMOS</a>
		</li>
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>inventario-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
		</li>
	</ul>
</div>

<?php
	// SI LA VARIABLE DE SESION NO ESTA DEFINIDA O NO EXISTE
	if(!isset($_SESSION['busqueda_vendedor']) && empty($_SESSION['busqueda_vendedor']) ){
?>
<!-- Content here-->
<div class="container-fluid">
<form class="FormularioAjax form-neon" action="<?php echo SERVERURL; ?>ajax/searchAjax.php"
	method="POST" data-form="search" autocomplete="off">
	<input type="hidden" name="modul" value="vendedor">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-6">
					<div class="form-group">
						<label for="inputSearch" class="bmd-label-floating">¿Qué vendedor estas buscando?</label>
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
<form class="FormularioAjax form-neon" action="<?php echo SERVERURL; ?>ajax/searchAjax.php"
	method="POST" data-form="search" autocomplete="off">
	<input type="hidden" name="modul" value="vendedor">
		<input type="hidden" name="eliminarBusqueda" value="eliminar">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-6">
					<p class="text-center" style="font-size: 20px;">
						Resultados de la busqueda <strong>
							“<?php echo $_SESSION['busqueda_vendedor']; ?>”
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
		require_once './controllers/inventarioController.php';
		$insInventario = new inventarioController();

		// UsupaginatorController($actualPage, $registers, $privilegio, $url, $busqueda){
			// los array se cuentan desde 0,
			// 0 = vista y de 1 en adelante son las paginas de los datos que se muestran
			// la busqueda no se define ya que este es el listado general
		echo $insInventario->paginadorInventarioController($page[1], 5, $_SESSION['privilegio_sv'],
		$page[0],$_SESSION['busqueda_vendedor']);
?>
</div>
<?php } ?>
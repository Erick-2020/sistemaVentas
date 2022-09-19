<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES
	</h3>
	<p class="text-justify">
		Observa todos los clientes que tienes en el sistema, edítalos o elimínalos según tu necesidad
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
		</li>
	</ul>
</div>

<!-- Content here-->
<div class="container-fluid">
<?php
		require_once './controllers/clientController.php';
		$insClient = new clientController();

		// UsupaginatorController($actualPage, $registers, $privilegio, $url, $busqueda){
			// los array se cuentan desde 0,
			// 0 = vista y de 1 en adelante son las paginas de los datos que se muestran
			// la busqueda no se define ya que este es el listado general
		echo $insClient->clientPaginatorController($page[1], 5, $_SESSION['privilegio_sv'],
		$page[0],"");
	?>
</div>
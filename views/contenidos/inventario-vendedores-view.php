<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; INVENTARIO VENDEDORES
	</h3>
	<p class="text-justify">
		Aquí puedes ver todos los inventarios que se le fueron otrogados a los diferentes vendedores
		para trabajar
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR AL INVENTARIO</a>
		</li>
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>inventario-vendedores/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; INVENTARIO VENDEDORES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
		</li>
	</ul>
</div>

<div class="container-fluid">
	<?php
        require_once "./controllers/inventarioController.php";

        $insInventario = new inventarioController();

        echo $insInventario->paginadorInventarioController($page[1], 5, $_SESSION['privilegio_sv'],
        $page[0],"")
    ?>
</div>
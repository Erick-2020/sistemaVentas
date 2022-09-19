<!-- Nav lateral -->
<section class="full-box nav-lateral">
    <div class="full-box nav-lateral-bg show-nav-lateral"></div>
	<div class="full-box nav-lateral-content">
		<figure class="full-box nav-lateral-avatar">
			<i class="far fa-times-circle show-nav-lateral"></i>
			<img src="<?php echo SERVERURL; ?>views/assets/avatar/Avatar.png" class="img-fluid" alt="Avatar">
			<figcaption class="roboto-medium text-center">
				<?php echo $_SESSION['nombre_sv']." ".$_SESSION['apellido_sv']; ?> <br><small class="roboto-condensed-light"><?php echo $_SESSION['nombre_sv']; ?></small>
			</figcaption>
		</figure>
		<div class="full-box full-box-one nav-lateral-bar"></div>
			<nav class="full-box nav-lateral-menu">
				<ul>
					<li>
						<a href="<?php echo SERVERURL; ?>home/"><i class="fab fa-dashcube fa-fw"></i> &nbsp; INICIO</a>
                    </li>
					<li>
						<a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; CLIENTES <i class="fas fa-chevron-down"></i></a>
						<ul>
							<li>
							    <a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Cliente</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</a>
							</li>
						</ul>
					</li>

					<?php if($_SESSION['privilegio_sv'] == 1){  ?>
                    <li>
						<a href="#" class="nav-btn-submenu"><i class="fas fa-archive"></i> &nbsp; PRODUCTOS <i class="fas fa-chevron-down"></i></a>
						<ul>
							<li>
								<a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar producto</a>
							</li>
					        <li>
					            <a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de productos</a>
					        </li>
					
                            <li>
						        <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar PRODUCTOS</a>
					        </li>
						</ul>
    				</li>
					<?php } ?>
					<li>
						<a href="#" class="nav-btn-submenu"><i class="fas fa-archive"></i> &nbsp; INVENTARIO VENDEDOR <i class="fas fa-chevron-down"></i></a>
						<ul>
							<li>
								<a href="<?php echo SERVERURL; ?>inventario-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar a vendedor</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>inventario-vendedores/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Inventarios</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>inventario-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar por fecha</a>
                            </li>
						</ul>
					</li>
                    <li>
						<a href="#" class="nav-btn-submenu"><i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp; PRÉSTAMOS <i class="fas fa-chevron-down"></i></a>
						<ul>
							<li>
								<a href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo préstamo</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt fa-fw"></i> &nbsp; Reservaciones</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Préstamos</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Finalizados</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar por fecha</a>
                            </li>
						</ul>
					</li>
					<?php if($_SESSION['privilegio_sv'] == 1){  ?>
					<li>
						<a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp; USUARIOS <i class="fas fa-chevron-down"></i></a>
						<ul>
							<li>
								<a href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
							</li>
						    <li>
                                <a href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de usuarios</a>
							</li>
							<li>
								<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar usuario</a>
							</li>
						</ul>
					</li>
					<?php } ?>

					<?php if($_SESSION['privilegio_sv'] == 1){  ?>
                    <li>
						<a href="<?php echo SERVERURL; ?>company/"><i class="fas fa-store-alt fa-fw"></i> &nbsp; EMPRESA</a>
					</li>
					<?php } ?>
				</ul>
			</nav>
	</div>
</section>
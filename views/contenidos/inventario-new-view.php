<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR AL INVENTARIO
    </h3>
    <p class="text-justify">
        Administrador, crea el inventario de tus vendedores, asigna productos a tus vendedores de manera independiente
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>inventario-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR AL INVENTARIO</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-vendedores/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; INVENTARIO VENDEDORES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>inventario-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
		</li>
	</ul>
</div>

<div class="container-fluid">
    <div class="container-fluid form-neon">
            <div class="container-fluid">
                <p class="text-center roboto-medium">AGREGAR VENDEDORES O PRODUCTOS</p>
                <p class="text-center">
                    <!-- COMPROVAR SI LA VARIABLE DE SESION VIENE VACIA  -->
                    <?php if(empty($_SESSION['datos_usuario'])){ ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalVendedor">
                        <i class="fas fa-user-plus"></i> &nbsp; Agregar vendedor
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem">
                        <i class="fas fa-box-open"></i> &nbsp; Agregar producto
                    </button>
                </p>
                <div>
                    <span class="roboto-medium">VENDEDOR:</span>
                    <?php if(empty($_SESSION['datos_usuario'])){ ?>
                    <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un vendedor</span>
                    <?php }else{ ?>
                    <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/inventarioAjax.php"
                    style="display: inline-block !important;" method="POST" data-form="loans">

                        <input type="hidden" name="id_eliminar_vendedor" value="<?php echo $_SESSION['datos_usuario']['ID']; ?>">
                        <?php echo $_SESSION['datos_usuario']['NAME']." ".$_SESSION['datos_usuario']['LASTNAME']; ?>

                        <!-- btn cierre de sesion -->
                        <button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                    </form>
                    <?php } ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                                <th>VENDEDOR</th>
                                <th>ITEM</th>
                                <th>CANTIDAD</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if( isset($_SESSION['datos_item']) && count($_SESSION['datos_item'])>=1 ){

                                    $_SESSION['prestamo_total']=0;
                                    $_SESSION['prestamo_item']=0;

                                    foreach($_SESSION['datos_item'] as $items){

                            ?>
                            <tr class="text-center" >
                                <td><?php echo $_SESSION['datos_usuario']['NAME']; ?></td>
                                <td><?php echo $items['NAME']; ?></td>
                                <td><?php echo $items['AMOUNT']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="popover"
                                    data-trigger="hover" title="<?php echo $items['NAME']; ?>"
                                    data-content="<?php echo $items['DETAIL']; ?>">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                                <td>
                                    <form class="FormularioAjax" action="<?php echo SERVERURL;
                                    ?>ajax/inventarioAjax.php" method="POST" data-form="loans" >
                                    <input type="hidden" name="id_eliminar_item" value="<?php
                                    echo $items['ID'] ?>">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $_SESSION['prestamo_item'] += $items['AMOUNT'];
                                }
                            ?>
                            <?php
                                }else{
                                    $_SESSION['prestamo_total']=0;
                                    $_SESSION['prestamo_item']=0;
                            ?>
                            <tr class="text-center" >
                                <td colspan="4" >No has seleccionado productos</td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/inventarioAjax.php"
                method="POST" data-form="save" autocomplete="off">
                    <input type="hidden" name="add_inventario" id="add_inventario" value="<?php $_SESSION['datos_usuario']['NAME']; ?>">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
                        &nbsp; &nbsp;
                        <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
                    </p>
                </form>
            </div>
    </div>
</div>


<!-- MODAL VENDEDOR -->
<div class="modal fade" id="ModalVendedor" tabindex="-1" role="dialog" aria-labelledby="ModalVendedor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalVendedor">Agregar vendedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_vendedor" class="bmd-label-floating">DNI, Nombre, Apellido, Telefono</label>
                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_vendedor" id="input_vendedor" maxlength="30">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_vendedores">
<!-- AQUI VA EL CODIGO DEL CONTROLADOR DE LA TABLA EN PRESTAMOS CONTROLLER -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="searchVendedor()" ><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL ITEM -->
<div class="modal fade" id="ModalItem" tabindex="-1" role="dialog" aria-labelledby="ModalItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalItem">Agregar item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_item" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control"
                        name="input_item" id="input_item" maxlength="30">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_items">
                <!-- AQUI VA EL CODIGO DEL CONTROLADOR DE LA TABLA EN PRESTAMOS CONTROLLER -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="searchItem()">
                    <i class="fas fa-search fa-fw"></i> &nbsp; Buscar
                </button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL AGREGAR ITEM -->
<div class="modal fade" id="ModalAgregarItem" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/inventarioAjax.php"
        method="POST" data-form="save" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarItem">Selecciona la cantidad de productos para el inventario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_item" id="id_agregar_item">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de items</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick="modalSearchItem()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<?php
include_once "./views/inc/reservation.php";
?>
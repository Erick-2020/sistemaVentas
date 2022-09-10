<?php
    session_start(['name'=>'SV']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo COMPANY; ?></title>
    <!-- Style links -->
    <?php include "./views/inc/styleLink.php"; ?>

</head>
<body>
    <?php
        $ajaxPeticions = false;
        // PERMITE MOSTRAR LAS VISTAS
        require_once "./controllers/viewsController.php";
        // INSTANCIAR EL CONTROLADOR DE LAS VISTAS
        $instanceViews = new viewsController();

        $views = $instanceViews->obtener_views_controller();

        // condicional para decetar la respuesta del modelo y mostrar el archivo correspondiente
        if($views == "login" || $views == "404"){
            require_once "./views/contenidos/".$views."-view.php";
        }else{

            // VARIABLE GLOBAL PARA EL USER LIST VIEW
            // PARA TENER TODOS LOS PARAMETROS DE LA URL SEPARADOS POR EL "/"
            $page=explode("/", $_GET['views']);

            require_once "./controllers/loginController.php";
            $loginController = new loginController();

            if(!isset($_SESSION['token_sv']) || !isset($_SESSION['usuario_sv']) ||
            !isset($_SESSION['privilegio_sv']) || !isset($_SESSION['id_sv'])){
                echo $loginController->logoutSesionController();
                exit();
            }
    ?>
	<!-- Main container -->
	<main class="full-box main-container">
        <!-- Nav Lateral -->
        <?php include "./views/inc/navLateral.php"; ?>
        <!-- Page content -->
        <section class="full-box page-content">
            <!-- Barra de navegacion -->
            <?php
                include "./views/inc/navBarra.php";
                include $views;
            ?>
        </section>
	</main>

    <!-- cierre del else, para mostrar el resto de la estructura -->
    <!-- SCRIPT -->
    <?php
        include "./views/inc/logOut.php";
        }
        include "./views/inc/script.php";

    ?>
</body>
</html>
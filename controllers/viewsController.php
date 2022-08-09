<?php
    require_once "./models/viewsModel.php";

    class viewsController extends viewsModel{
        // Controlador para obtener las plantillas
        public function obtener_plantilla_controller(){
            return require_once "./views/plantilla.php";
        }
        // Controlador para obtener las vistas
        public function obtener_views_controller(){
            if(isset($_GET['views'])){
                $ruta = explode("/", $_GET['views']);
                $response = viewsModel::obtener_views_model($ruta[0]);
            }else{
                $response = "login";
            }
            return $response;
        }
    }
<?php
    class viewsModel{
        // Modelo para obtener las vistas
        protected static function obtener_views_model($views){
            $whiteList = ["home", "client-list", "client-new", "client-search", "client-update", "company",
            "inventario-new", "inventario-search", "inventario-vendedores", "item-list", "item-new",
            "item-search", "item-update", "reservation-list", "reservation-new", "reservation-pending",
            "reservation-reservation", "reservation-search", "reservation-update", "user-list", "user-new",
            "user-search", "user-update"];
            if(in_array($views, $whiteList)){
                if(is_file("./views/contenidos/".$views."-view.php")){
                    $contenido = "./views/contenidos/".$views."-view.php";
                }else{
                    $contenido = "404";
                }
            }elseif($views=="login" || $views=="index"){
                $contenido = "login";
            }else{
                $contenido = "404";
            }
            return $contenido;
        }
    }
?>
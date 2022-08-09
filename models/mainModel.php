<?php
    // detectar peticiones ajax
    if($ajaxPeticions){
        //si es una peticion que los archivos se incluyan en el ajax
        require_once "../config/SERVER.php";
    }else{
        //intentar incluirlo cuando no sea un peticion
        require_once "./config/SERVER.php";
    }

    // conexion bd, inyecciones SQL, etc
    class mainModel{

        protected static function connection(){
        // $mbd = new PDO('mysql:host=localhost;dbname=prueba', $usuario, $contraseña);

        $connections = new PDO(SGDB, USER, PASSWORD);
        $connections->exec("SET CHARACTER SET utf8");

        return $connections;
        }

        // CONSULTAS SQL SIMPLES
        protected static function sqlConsult_Simple($consult){
            //almacenar peticion, utilizando la funcion de conexion con el metodo self(referenciar metodos)
            $sqlPetition=self::connection()->prepare($consult);
            $sqlPetition->execute();

            return $sqlPetition;
        }

        // ENCRIPTAR POR MEDIO DEL HASH
        public function encryption($string){
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		}

        // DESEENCRIPTAR POR MEDIO DEL HASH
		public static function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}

        // GENERACION DE CODIGOS ALEATORIOS
        // PALABRA EJEMPLO = E159-1
        protected static function codigGenerate($letra, $long, $number){
            for($i=1; $i<=$long; $i++){
                //METODO ELIGE NUMERO ALEATORIO CON EL METODO RAND(ESCOGE NUMERO DESDE 0 A 9)
                $Aleatorynumber= rand(0,9);
                // SE CONCATENA LA LETRA CON EL NUMERO A LA SAR PARA CADA CARACRER DE LA PALABRA
                $letra.= $Aleatorynumber;
            }
            return $letra."-".$number;
        }

        protected static function stringClear($cadena){
            // ELIMINAR ESPACIOS
            $cadena = trim($cadena);
            // ELIMINAR BARRAS INVERTIDAS
            $cadena = stripslashes($cadena);
            // ELIMINAR CIERTAS SENTENCIAS Y CARACTERES DE CODIGO POR SEGURIDAD
            $cadena = str_ireplace("<script>", "", $cadena);
            $cadena = str_ireplace("</script>", "", $cadena);
            $cadena = str_ireplace("<script src", "", $cadena);
            $cadena = str_ireplace("<script type =", "", $cadena);
            $cadena = str_ireplace("SELECT * FROM", "", $cadena);
            $cadena = str_ireplace("DELETE FROM", "", $cadena);
            $cadena = str_ireplace("INSERT INTO", "", $cadena);
            $cadena = str_ireplace("DROP TABLE", "", $cadena);
            $cadena = str_ireplace("DROP DATABASE", "", $cadena);
            $cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
            $cadena = str_ireplace("SHOW TABLES", "", $cadena);
            $cadena = str_ireplace("SHOW DATABASES", "", $cadena);
            $cadena = str_ireplace("<?php", "", $cadena);
            $cadena = str_ireplace("?>", "", $cadena);
            $cadena = str_ireplace("--", "", $cadena);
            $cadena = str_ireplace(">", "", $cadena);
            $cadena = str_ireplace("<", "", $cadena);
            $cadena = str_ireplace("[", "", $cadena);
            $cadena = str_ireplace("]", "", $cadena);
            $cadena = str_ireplace("^", "", $cadena);
            $cadena = str_ireplace("==", "", $cadena);
            $cadena = str_ireplace(";", "", $cadena);
            $cadena = str_ireplace("::", "", $cadena);
            $cadena = str_ireplace("{", "", $cadena);
            $cadena = str_ireplace("}", "", $cadena);
            $cadena = str_ireplace("<script>", "", $cadena);
            $cadena = stripslashes($cadena);
            $cadena = trim($cadena);

            return $cadena;
        }

        // VALIDACIONES DEL FORMULARIO CON EL FORMATO ESPECIFICADO DE EXPRESIONES REGULARES
        protected static function validationData($filtro, $cadena){
            //funcion que realiza una comparacion con una expresion regular
            if(preg_match("/^".$filtro."$/", $cadena)){
                return false;
            }else{
                return true;
            }
        }

        //VALIDACION FECHA
        protected static function validationDate($date){
            $result=explode('-', $date);
            if(count($result) == 3 && checkdate($result[1],result[2],$result[0])){
                return false;
            }else{
                return true;
            }
        }

        // PAGINADOR TABLAS
        protected static function paginador($pages, $pagesNumber, $url, $buttons){
            $table = '<nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">';

            if($pages==1){
                $table.='<li class="page-item disabled">
				<a class="page-link"><i class="fa-thin fa-angles-left"></i></a></li>';
            }else{
                $table.='
                <li class="page-item">
				<a class="page-link" href="'.$url.'1/"><i class="fa-thin fa-angles-left"></i></a></li>
                <li class="page-item">
				<a class="page-link" href="'.$url.($pages-1).'/">Anterior</a></li>';
            }

            $cicle=0;
            for($i=$pages; $i<=$pagesNumber; $i++){
                if($cicle>=$buttons){
                    break;
                }
                if($pages == $i){
                    $table.='<li class="page-item">
                    <a class="page-link" active href="'.$url.$i.'/">'.$i.'</a></li>';
                }else{
                    $table.='<li class="page-item">
                    <a class="page-link" href="'.$url.$i.'/">'.$i.'</a></li>';
                }
                $cicle++;
            }

            if($pages=$pagesNumber){
                $table.='<li class="page-item disabled">
				<a class="page-link"><i class="fa-thin fa-angles-right"></i></a></li>';
            }else{
                $table.='
                <li class="page-item">
				<a class="page-link" href="'.$url.($pages+1).'/">Siguiente</a></li>
                <li class="page-item">
				<a class="page-link" href="'.$url.$pagesNumber.'/"><i class="fa-thin fa-angles-right"></i></a></li>';
            }

            $table.= '</ul></nav>';

            return $table;
        }

    }
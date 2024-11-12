<?php
include_once "conexion.php";

class ControlFormulario{

    function crear($conecta) {
        $insertar = "INSERT INTO ";
    }

    function leer($conecta,$tabla) {
        $leer = "SELECT * FROM $tabla";
        $resultado_leer = $conecta->prepare($leer);
        $resultado_leer->execute([]);
        $leer_vista = $resultado_leer->fetch(PDO::FETCH_ASSOC);
            return $leer_vista;
        
    }
    
    function insertar_datos($conecta,$tabla,$datos){
            $columnas = implode(", ", array_keys($datos));
            $valores = ":" . implode(", :", array_keys($datos));
            
            $insertar = "INSERT INTO `$tabla` ($columnas) VALUES ($valores)";
            $sql = $conecta->prepare($insertar);
            foreach ($datos as $clave => $valor) {
                $sql->bindValue(":$clave", $valor);
            }
            $sql->execute();
    }
    
    function busqueda($conecta){
    }
}
/*
    cosas utilizadas:
    https://www.php.net/manual/es/control-structures.foreach.php
    https://stackoverflow.com/questions/10827242/understanding-the-post-redirect-get-pattern
    https://stackoverflow.com/questions/37890694/create-a-dynamic-insert-statement-php-mysql
*/
?>
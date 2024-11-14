<?php
include_once "conexion.php";

class ControlFormulario{

    function insertar_datos($conecta,$tabla,$datos){
        try{
            $columnas = implode(", ", array_keys($datos));
            $valores = ":" . implode(", :", array_keys($datos));
            $insertar = "INSERT INTO `$tabla` ($columnas) VALUES ($valores)";
            echo $insertar;
            $sql = $conecta->prepare($insertar);
            foreach ($datos as $clave => $valor) {
                $sql->bindValue(":$clave", $valor);
            }
            $sql->execute();
        }catch(Exception $e){
            echo "Ocurrió un error al registrar los datos: " . $e->getMessage();
        }
    }
    function leer($conecta,$tabla,$where = '1'){
        $lectura = "SELECT * FROM `$tabla` WHERE $where";
        echo $lectura;
        $resultado_leer = $conecta->prepare($lectura);
        $resultado_leer->execute([]);
        return $resultado_leer->fetchAll(PDO::FETCH_ASSOC);
    }
    function actualizar($conecta,$tabla,$datos,$where){
        try{
        $actualizar = [];
        foreach ($datos as $clave => $valor) {
            $actualizar[] = "`$clave` = :$clave";
        }
        $columna = implode(", ", $actualizar);
        $insertar = "UPDATE `$tabla` SET $columna WHERE $where";
        $sql = $conecta->prepare($insertar);

        foreach ($datos as $clave => $valor) {
            $sql->bindValue(":$clave", $valor);
        }

        $sql->execute();
    }catch(Exception $e){
        echo "Ocurrió un error al registrar los datos: " . $e->getMessage();
    }
    }
    function borrar($conecta,$tabla,$where){
        try{
            $borrar = "DELETE FROM `$tabla` WHERE $where";
            echo $borrar;   
            $sql = $conecta->prepare($borrar);
            $sql->execute();
        }catch(Exception $e){
            echo "Ocurrió un error al borrar los datos: " . $e->getMessage();
        }
    }
}
/*
    cosas utilizadas:
    https://www.php.net/manual/es/control-structures.foreach.php
    https://www.php.net/manual/es/language.types.array.php
    https://icodemag.com/prg-pattern-in-php-what-why-and-how/
    https://stackoverflow.com/questions/10827242/understanding-the-post-redirect-get-pattern
    https://stackoverflow.com/questions/37890694/create-a-dynamic-insert-statement-php-mysql
*/
?>
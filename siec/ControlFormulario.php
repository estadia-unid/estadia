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
    
    function actualizar(){
    }
    function nuevo_servidor($conecta,$marca,$modelo,$numeroserie,$procesador,$velocidad,$ram,$ip,$activo,$inventario,$observaciones){
        $nuevoServidor = "INSERT INTO `servidores`(`marca`, `modelo`, `num_serie`, `procesador`, `velocidad`, `ram`, `ip`, `activo_fijo`, `inventario`, `observaciones`)
        VALUES (:marca,:modelo,:num_serie,:procesador,:velocidad,:ram,:ip,:activo_fijo,:inventario,:observaciones)";
                    $sql = $conecta->prepare($nuevoServidor);
                    $sql->bindParam(':marca',$marca);
                    $sql->bindParam(':modelo',$modelo);
                    $sql->bindParam(':num_serie',$numeroserie);
                    $sql->bindParam(':procesador',$procesador);
                    $sql->bindParam(':velocidad',$velocidad);
                    $sql->bindParam(':ram',$ram);
                    $sql->bindParam(':ip',$ip);
                    $sql->bindParam(':activo_fijo',$activo);
                    $sql->bindParam(':inventario',$inventario);
                    $sql->bindParam(':observaciones',$observaciones);
                    $sql->execute();
    }
    
    function busqueda($conecta){
    }
}
?>
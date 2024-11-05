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
        $leer_vista = $resultado_leer->fetch(PDO::FETCH_ASSOC)
            return $leer_vista;
        
    }
    
    function actualizar(){
        $actualizar = "UPDATE `computadoras` SET `id_computadora`='[value-1]',`oficial`='[value-2]',`no_oficial`='[value-3]',`departamento`='[value-4]',`puesto`='[value-5]',`usuario_responsable`='[value-6]',`rpe`='[value-7]',`tipo_de_equipo`='[value-8]',`activo_fijo`='[value-9]',`inventario`='[value-10]',`numero_de_serie`='[value-11]',`marca`='[value-12]',`modelo`='[value-13]',`mac_wifi`='[value-14]',`mac_ethernet`='[value-15]',`memoria`='[value-16]',`disco_duro`='[value-17]',`dominio`='[value-18]',`resg`='[value-19]',`d_activo`='[value-20]',`antivirus`='[value-21]',`observaciones`='[value-22]' WHERE 1";
        $resultado_actualizar = $conecta->prepare($actualizar);
        $resultado_sesion->execute([
            ':usuario' => $this->usuario,
            ':clave' => $this->contraseña
        ]);
    }
    function borrar(){

    }
    
    function busqueda($conecta){
    }
}
$visual = new ControlFormulario('');
$visual->leer($conecta,"departamentos");
?>
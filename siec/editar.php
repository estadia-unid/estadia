<?php
    class control_computadoras{
        public $color;
        function registrar(){
            $insertar = "INSERT INTO `computadoras`(`id_computadora`, `oficial`, `no_oficial`, `departamento`, `puesto`, `usuario_responsable`, `rpe`, `tipo_de_equipo`, `activo_fijo`, `inventario`, `numero_de_serie`, `marca`, `modelo`, `mac_wifi`, `mac_ethernet`, `memoria`, `disco_duro`, `dominio`, `resg`, `d_activo`, `antivirus`, `observaciones`) VALUES ('[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]','[]')
        }
        function editar(){

        }
        function eliminar(){

        }
    }
$azul = new control_computadoras();
$azul->color("verde");
echo $azul->color;
?>
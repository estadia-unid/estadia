<?php
include_once "conexion.php";
include "autoloader.php";

$sesion = new ControlSesiones('9B9M7', 'hola123');
$sesion->iniciar_sesion($conecta);
?>
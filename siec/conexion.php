<?php
session_start();
$contraseña = "ctpalm2113";
$usuario = "skyper";
$nombre_base_de_datos = "siec";
try{
	$conecta = new PDO('mysql:host=localhost;dbname=siec', $usuario, $contraseña);
}catch(Exception $e){
    die("Ocurrió algo con la base de datos: " . $e->getMessage());
}
?> 

<?php
session_start();
$contraseña = "ctpalm2113";
$usuario = "skyper";
$nombre_base_de_datos = "estadiaunid";
try{
	$conecta = new PDO('mysql:host=localhost;dbname=estadiaunid', $usuario, $contraseña);
}catch(Exception $e){
    die("Ocurrió algo con la base de datos: " . $e->getMessage());
}
?> 

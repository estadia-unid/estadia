<?php
session_start();
$contraseña = "ctpalm2113";
$usuario = "siec";
$nombre_base_de_datos = "siec";
try{
	$conecta = new PDO('mysql:host=localhost;dbname=siec', $usuario, $contraseña);
	echo "Conexión exitosa";
}catch(Exception $e){
	echo "Ocurrió algo con la base de datos: " . $e->getMessage();
}
?> 
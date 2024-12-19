<?php
// Iniciar sesión
session_start();

// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City');

// Conectar a la base de datos 'estadiaunid'
$conecta = mysqli_connect('localhost', 'skyper', 'ctpalm2113', 'estadiaunid');

// Conectar a la base de datos 'checador'
$conecta2 = mysqli_connect('localhost', 'skyper', 'ctpalm2113', 'checador'); // Nueva conexión

// Verificar la conexión a 'estadiaunid'
if (!$conecta) {
    die('Error de conexión a estadiaunid: ' . mysqli_connect_error());
}

// Verificar la conexión a 'checador'
if (!$conecta2) {  // Verificar la nueva conexión
    die('Error de conexión a checador: ' . mysqli_connect_error());
}

// Establecer el conjunto de caracteres a UTF-8 para 'estadiaunid'
if (!mysqli_set_charset($conecta, 'utf8')) {
    die('Error al establecer el conjunto de caracteres UTF-8 para estadiaunid: ' . mysqli_error($conecta));
}

// Establecer el conjunto de caracteres a UTF-8 para 'checador'
if (!mysqli_set_charset($conecta2, 'utf8')) {  // Establecer el conjunto de caracteres para la nueva conexión
    die('Error al establecer el conjunto de caracteres UTF-8 para checador: ' . mysqli_error($conecta2));
}
?>

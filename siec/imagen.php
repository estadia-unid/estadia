<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "ctpalm2113";
$dbname = "rescatar";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ID de la imagen a recuperar
$id = "9AVB3"; // Cambia esto por el ID de la imagen que deseas recuperar

// Consulta para obtener la imagen
$sql = "SELECT imagen FROM `imagenes` WHERE `idImagen` = `9AVB3`";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagen_binaria);
$stmt->fetch();
$stmt->close();
$conn->close();

// Comprobar si se recuperó la imagen
if ($imagen_binaria) {
    // Configurar el encabezado para mostrar la imagen
    header("Content-Type: image/jpg");
    echo $imagen_binaria;
} else {
    // En caso de no encontrar la imagen, puedes manejarlo aquí
    header("HTTP/1.0 404 Not Found");
}
?>

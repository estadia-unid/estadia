<?php
// Conectar a la base de datos
$host = 'localhost'; // Cambia según tu configuración
$user = 'skyper'; // Cambia según tu usuario
$password = 'ctpalm2113'; // Cambia si tienes una contraseña
$database = 'estadiaunid'; // Cambia por el nombre de tu base de datos

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Obtener la búsqueda del usuario (si existe)
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Consultar empleados por RPE
$query = "SELECT rpe, nombre, a_paterno, a_materno FROM empleados WHERE rpe LIKE '%$searchTerm%' LIMIT 10";
$resultado = $conexion->query($query);

// Crear un array para almacenar los resultados
$empleados = array();

while ($row = $resultado->fetch_assoc()) {
    $nombre_completo = $row['nombre'] . ' ' . $row['a_paterno'] . ' ' . $row['a_materno'];
    $empleados[] = array(
        "id" => $row['rpe'], // Aquí usaremos el RPE como el ID
        "text" => $row['rpe'] . ' - ' . $nombre_completo // Mostrar RPE y nombre completo
    );
}

// Devolver los datos en formato JSON
echo json_encode($empleados);

// Cerrar la conexión
$conexion->close();
?>
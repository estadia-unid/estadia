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
$query = "SELECT id, rpe, nombre FROM empleados WHERE rpe LIKE '%$searchTerm%' LIMIT 10";
$resultado = $conexion->query($query);

// Crear un array para almacenar los resultados
$empleados = array();

while ($row = $resultado->fetch_assoc()) {
    $empleados[] = array(
        "id" => $row['id'],
        "text" => $row['rpe'] . ' - ' . $row['nombre'] // Mostrar RPE y nombre juntos
    );
}

// Devolver los datos en formato JSON
echo json_encode($empleados);

// Cerrar la conexión
$conexion->close();
?>

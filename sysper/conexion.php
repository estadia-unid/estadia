<?php
// Conectar a la base de datos
$host = 'localhost'; // Cambia según tu configuración
$user = 'root'; // Cambia según tu usuario
$password = ''; // Cambia si tienes una contraseña
$database = 'mi_base_de_datos'; // Cambia por el nombre de tu base de datos

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Obtener la búsqueda del usuario (si existe)
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Consultar empleados con un término de búsqueda
$query = "SELECT id, nombre FROM empleados WHERE nombre LIKE '%$searchTerm%' LIMIT 10";
$resultado = $conexion->query($query);

// Crear un array para almacenar los resultados
$empleados = array();

while ($row = $resultado->fetch_assoc()) {
    $empleados[] = array(
        "id" => $row['id'],
        "text" => $row['nombre']
    );
}

// Devolver los datos en formato JSON
echo json_encode($empleados);

// Cerrar la conexión
$conexion->close();
?>

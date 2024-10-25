<?php
// Procesar el formulario

// Conectar a la base de datos
$servername = "localhost";
$username = "skyper";
$password = "ctpalm2113";
$dbname = "estadiaunid";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$fecha = $_POST['fecha'];
$numero_orden = $_POST['numero_orden'][0]; // Suponiendo que hay un solo número de orden
$actividades = $_POST['actividades'];
$justificacion = $_POST['justificacion'];
$om = $_POST['om'][0]; // Suponiendo que hay un solo OM
$hora_inicio = $_POST['hora_inicio'][0]; // Suponiendo que hay una sola hora de inicio
$hora_termino = $_POST['hora_termino'][0]; // Suponiendo que hay una sola hora de término

// Preparar y ejecutar la consulta para la solicitud
$sql = "INSERT INTO solicitudes_extraordinarias (fecha, numero_orden, actividades, justificacion, om, hora_inicio, hora_termino) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $fecha, $numero_orden, $actividades, $justificacion, $om, $hora_inicio, $hora_termino);

if ($stmt->execute()) {
    $solicitud_id = $stmt->insert_id; // Obtener el ID de la solicitud recién insertada

    // Guardar cada equipo
    if (!empty($_POST['empleados'])) {
        foreach ($_POST['empleados'] as $empleado) {
            $sqlEquipo = "INSERT INTO equipos (solicitud_id, empleado) VALUES (?, ?)";
            $stmtEquipo = $conn->prepare($sqlEquipo);
            $stmtEquipo->bind_param("is", $solicitud_id, $empleado);
            $stmtEquipo->execute();
            $stmtEquipo->close();
        }
    }
    echo "Registro guardado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

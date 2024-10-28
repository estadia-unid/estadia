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

// Obtener datos comunes
$fecha = $_POST['fecha'];

// Guardar cada equipo en la base de datos
foreach ($_POST['numero_orden'] as $index => $numero_orden) {
    // Obtenemos los valores del equipo actual
    $actividades = $_POST['actividades'][$index];
    $justificacion = $_POST['justificacion'][$index];
    $om = $_POST['om'][$index];
    $hora_inicio = $_POST['hora_inicio'][$index];
    $hora_termino = $_POST['hora_termino'][$index];
    $empleados = $_POST['empleados'][$index] ?? [];

    // Insertar solicitud en la tabla solicitudes_extraordinarias
    $sql = "INSERT INTO solicitudes_extraordinarias (fecha, numero_orden, actividades, justificacion, om, hora_inicio, hora_termino) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fecha, $numero_orden, $actividades, $justificacion, $om, $hora_inicio, $hora_termino);

    if ($stmt->execute()) {
        $solicitud_id = $stmt->insert_id; // Obtener el ID de la solicitud recién insertada

        // Guardar cada empleado del equipo en la tabla equipos
        foreach ($empleados as $empleado) {
            $sqlEquipo = "INSERT INTO equipos (solicitud_id, empleado) VALUES (?, ?)";
            $stmtEquipo = $conn->prepare($sqlEquipo);
            $stmtEquipo->bind_param("is", $solicitud_id, $empleado);
            $stmtEquipo->execute();
            $stmtEquipo->close();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
echo "Registro guardado correctamente.";
?>

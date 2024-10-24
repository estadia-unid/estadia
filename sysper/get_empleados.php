<?php
include('../conexion.php'); // Conexión a la base de datos

$sql = "SELECT rpe, nombre, a_paterno, a_materno FROM empleados";
$result = mysqli_query($conecta, $sql);

$empleados = [];

while ($row = mysqli_fetch_assoc($result)) {
    $empleados[] = [
        'id' => $row['rpe'],  // RPE será el valor del empleado
        'text' => $row['nombre'] . ' ' . $row['a_paterno'] . ' ' . $row['a_materno']  // Texto para mostrar en el select
    ];
}

echo json_encode($empleados);
?>

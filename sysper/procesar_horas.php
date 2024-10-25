<?php
// Conectar a la base de datos
$conecta = mysqli_connect('localhost', 'skyper', 'ctpalm2113', 'estadiaunid');

if (!$conecta) {
    die("Connection failed: " . mysqli_connect_error());
}

// Capturar los datos del formulario
$fecha = $_POST['fecha'];
$numero_orden = $_POST['numero_orden'][0]; // Captura el primero, puedes ajustarlo según necesites
$actividades = $_POST['actividades'];
$justificacion = $_POST['justificacion'];
$om = $_POST['om'][0]; // Captura el primero, puedes ajustarlo según necesites
$hora_inicio = $_POST['hora_inicio'][0]; // Captura el primero, puedes ajustarlo según necesites
$hora_termino = $_POST['hora_termino'][0]; // Captura el primero, puedes ajustarlo según necesites
$empleados = implode(',', $_POST['empleados']); // Convierte el array de empleados en una cadena separada por comas

// Insertar los datos en la tabla
$sql = "INSERT INTO tiempo_extraordinario (fecha, numero_orden, actividades, justificacion, om, hora_inicio, hora_termino, empleados)
VALUES ('$fecha', $numero_orden, '$actividades', '$justificacion', $om, '$hora_inicio', '$hora_termino', '$empleados')";

if (mysqli_query($conecta, $sql)) {
    echo "Registro guardado correctamente.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conecta);
}

// Cerrar conexión
mysqli_close($conecta);
?>

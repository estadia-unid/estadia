<?php
require_once 'seguridad.php';

// Configurar la zona horaria
date_default_timezone_set('America/Mexico_City');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "skyper", "ctpalm2113", "estadiaunid");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
// Consulta para contar el número de empleados
$consulta = "SELECT COUNT(*) AS total_empleados FROM empleados";
$resultado = $conexion->query($consulta);
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// Obtener el total de empleados
$total_empleados = 0;
if ($resultado && $fila = $resultado->fetch_assoc()) {
    $total_empleados = $fila['total_empleados'];
}
// Consulta para contar los registros realizados hoy
$consulta = "SELECT COUNT(*) AS nuevos_registros FROM registros WHERE DATE(fecha_registro) = '$fecha_hoy'";
$resultado = $conexion->query($consulta);
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

$nuevos_registros = 1;
if ($resultado && $fila = $resultado->fetch_assoc()) {
    $nuevos_registros = $fila['nuevos_registros'];
}
// Nueva consulta para obtener los detalles de cada empleado y los id_registro de la tabla registros
$consulta_detalle = "
    SELECT 
        ea.rpe,
        e.categ,
        e.nombre,
        e.a_paterno,
        e.a_materno,
        r.id AS id_registro,  -- Este es el id_registro que deseas
        r.fecha_registro,
        r.hora_inicio,
        r.hora_termino,
        r.horas_extra,
        r.actividades,
        don.numero_orden,
        r.justificacion
    FROM empleados_asignados ea
    INNER JOIN empleados e ON ea.rpe = e.rpe
    INNER JOIN registros r ON ea.id_registro = r.id
    INNER JOIN detalles_orden don ON r.id = don.id_registro
    WHERE ea.rpe IS NOT NULL
    ORDER BY r.id; 
";
$datos = $conexion->query($consulta_detalle);
if (!$datos) {
    die("Error en la consulta de detalles: " . $conexion->error);
}
$datos = $datos->fetch_all(MYSQLI_ASSOC);

// Cerrar la conexión
$conexion->close();

// Agrupar los registros por id_registro
$registros_por_id = [];

// Agrupar los registros por el id_registro
foreach ($datos as $registro) {
    $registros_por_id[$registro['id_registro']][] = $registro;
}

// Obtener la fecha actual en el formato deseado
$fecha_hoy_formateada = date("d") . " de " . strftime("%B") . " de " . date("Y");
?>

<!-- Mostrar las tablas por id_registro -->
<?php foreach ($registros_por_id as $id_registro => $registros): ?>
    <h3>Registros con ID de Registro: <?php echo htmlspecialchars($id_registro); ?></h3>
    
    <!-- Botón para generar PDF -->
    <form action="../reports/sate.php" method="post" target="_blank">
        <input type="hidden" name="id_registro" value="<?php echo htmlspecialchars($id_registro); ?>">
        <button type="submit" class="btn-generar">Generar PDF</button>
    </form>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID REGISTRO</th>
                <th>CATEGORIA</th>
                <th>NOMBRE DEL TRABAJADOR</th>
                <th>RPE</th>
                <th>FECHA</th>
                <th>INICIO</th>
                <th>TERMINO</th>
                <th>No. HORAS</th>
                <th>ACTIVIDADES REALIZADAS</th>
                <th>No. ORDEN</th>
                <th>JUSTIFICACION TECNICA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?php echo htmlspecialchars($registro['id_registro']); ?></td>
                    <td><?php echo htmlspecialchars($registro['categ']); ?></td>
                    <td><?php echo htmlspecialchars($registro['nombre'] . ' ' . $registro['a_paterno'] . ' ' . $registro['a_materno']); ?></td>
                    <td><?php echo htmlspecialchars($registro['rpe']); ?></td>
                    <td><?php echo htmlspecialchars($registro['fecha_registro']); ?></td>
                    <td><?php echo htmlspecialchars($registro['hora_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($registro['hora_termino']); ?></td>
                    <td><?php echo htmlspecialchars($registro['horas_extra']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($registro['actividades'])); ?></td>
                    <td><?php echo htmlspecialchars($registro['numero_orden']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($registro['justificacion'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>
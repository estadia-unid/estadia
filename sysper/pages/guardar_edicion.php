<?php
require_once 'seguridad.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "skyper", "ctpalm2113", "estadiaunid");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Captura los datos enviados desde el formulario
$id_registro = $_POST['id_registro'] ?? null;
$fecha_registro = $_POST['fecha_registro'] ?? null;
$hora_inicio = $_POST['hora_inicio'] ?? null;
$hora_termino = $_POST['hora_termino'] ?? null;
$horas_extra = $_POST['horas_extra'] ?? null;
$actividades = $_POST['actividades'] ?? null;
$justificacion = $_POST['justificacion'] ?? null;
$id_departamento = $_POST['departamento'] ?? null;
$id_jefe_departamento = $_POST['jefe_departamento'] ?? null;
$numero_orden = $_POST['numero_orden'] ?? null;
$om = $_POST['om'] ?? null;
$empleados_asignados = $_POST['empleados'] ?? [];

// Inicia una transacción para asegurar la integridad
$conexion->begin_transaction();

try {
    // Actualiza los datos del registro
    $consulta_registro = "
        UPDATE registros 
        SET 
            fecha_registro = ?, 
            hora_inicio = ?, 
            hora_termino = ?, 
            horas_extra = ?, 
            actividades = ?, 
            justificacion = ?
        WHERE id = ?";
    $stmt_registro = $conexion->prepare($consulta_registro);
    $stmt_registro->bind_param(
        "sssissi",
        $fecha_registro,
        $hora_inicio,
        $hora_termino,
        $horas_extra,
        $actividades,
        $justificacion,
        $id_registro
    );
    $stmt_registro->execute();

    // Actualiza los detalles de la orden
    $consulta_detalles = "
        UPDATE detalles_orden 
        SET 
            id_departamento = ?, 
            numero_orden = ?, 
            om = ? 
        WHERE id_registro = ?";
    $stmt_detalles = $conexion->prepare($consulta_detalles);
    $stmt_detalles->bind_param("issi", $id_departamento, $numero_orden, $om, $id_registro);
    $stmt_detalles->execute();

    // Elimina empleados previamente asignados al registro
    $consulta_eliminar_empleados = "
        DELETE FROM empleados_asignados 
        WHERE id_registro = ?";
    $stmt_eliminar_empleados = $conexion->prepare($consulta_eliminar_empleados);
    $stmt_eliminar_empleados->bind_param("i", $id_registro);
    $stmt_eliminar_empleados->execute();

    // Asigna los nuevos empleados al registro
    $consulta_insertar_empleado = "
        INSERT INTO empleados_asignados (id_registro, rpe) 
        VALUES (?, ?)";
    $stmt_insertar_empleado = $conexion->prepare($consulta_insertar_empleado);

    foreach ($empleados_asignados as $rpe) {
        $stmt_insertar_empleado->bind_param("is", $id_registro, $rpe);
        $stmt_insertar_empleado->execute();
    }

    // Confirma la transacción
    $conexion->commit();

    // Redirecciona o muestra un mensaje de éxito
    header("Location: vista_registro.php?id_registro=$id_registro&mensaje=Registro actualizado correctamente");
    exit;
} catch (Exception $e) {
    // Deshace la transacción en caso de error
    $conexion->rollback();
    die("Error al guardar los cambios: " . $e->getMessage());
} finally {
    // Cierra las conexiones
    $stmt_registro->close();
    $stmt_detalles->close();
    $stmt_eliminar_empleados->close();
    $stmt_insertar_empleado->close();
    $conexion->close();
}
?>

<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'skyper', 'ctpalm2113', 'estadiaunid');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Validar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos de la solicitud
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);

    // Insertar la solicitud en sysper_solicitudes_extraordinarias
    $querySolicitud = "INSERT INTO sysper_solicitudes_extraordinarias (sysper_fecha) VALUES ('$fecha')";
    if ($conexion->query($querySolicitud) === TRUE) {
        $solicitudId = $conexion->insert_id; // Obtener el ID de la solicitud insertada

        // Procesar cada equipo ingresado
        foreach ($_POST['equipos'] as $equipo) {
            $categ = mysqli_real_escape_string($conexion, $equipo['categ']);
            $nombre = mysqli_real_escape_string($conexion, $equipo['nombre']);
            $rpe = mysqli_real_escape_string($conexion, $equipo['rpe']);
            $inicio = mysqli_real_escape_string($conexion, $equipo['inicio']);
            $termino = mysqli_real_escape_string($conexion, $equipo['termino']);
            $noHoras = mysqli_real_escape_string($conexion, $equipo['no_horas']);

            // Insertar datos en la tabla sysper_equipos
            $queryEquipo = "INSERT INTO sysper_equipos (sysper_solicitud_id, sysper_categ, sysper_nombre, sysper_rpe, sysper_inicio, sysper_termino, sysper_no_horas) 
                            VALUES ('$solicitudId', '$categ', '$nombre', '$rpe', '$inicio', '$termino', '$noHoras')";

            if ($conexion->query($queryEquipo) === TRUE) {
                $equipoId = $conexion->insert_id; // Obtener el ID del equipo insertado

                // Procesar cada detalle del equipo ingresado
                foreach ($equipo['detalles'] as $detalle) {
                    $actReal = mysqli_real_escape_string($conexion, $detalle['act_real']);
                    $noOrden = mysqli_real_escape_string($conexion, $detalle['no_orden']);
                    $justTec = mysqli_real_escape_string($conexion, $detalle['just_tec']);
                    $om = mysqli_real_escape_string($conexion, $detalle['om']);

                    // Insertar detalles en la tabla sysper_detalles_equipo
                    $queryDetalle = "INSERT INTO sysper_detalles_equipo (sysper_equipo_id, sysper_act_real, sysper_no_orden, sysper_just_tec, sysper_om) 
                                     VALUES ('$equipoId', '$actReal', '$noOrden', '$justTec', '$om')";

                    if (!$conexion->query($queryDetalle)) {
                        echo "Error al insertar detalles del equipo: " . $conexion->error;
                    }
                }
            } else {
                echo "Error al insertar equipo: " . $conexion->error;
            }
        }
    } else {
        echo "Error al insertar la solicitud: " . $conexion->error;
    }
}
$conexion->close();
?>

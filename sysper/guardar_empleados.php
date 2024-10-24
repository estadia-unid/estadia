<?php
include('conexion.php'); // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empleadosSeleccionados = $_POST['empleados']; // Array de RPEs seleccionados

    foreach ($empleadosSeleccionados as $rpe) {
        // Obtener los datos de los empleados seleccionados
        $sql = "SELECT nombre, categ FROM empleados WHERE rpe = '$rpe'";
        $result = mysqli_query($conecta, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $nombre = $row['nombre'];
            $categ = $row['categ'];

            // Insertar en la tabla sysper_sate
            $sqlInsert = "INSERT INTO sysper_sate (nombre, categ, rpe) VALUES ('$nombre', '$categ', '$rpe')";
            mysqli_query($conecta, $sqlInsert);
        }
    }

    echo "Empleados guardados correctamente.";
}
?>

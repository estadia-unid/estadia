<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['empleados'])) {
        $empleados_seleccionados = $_POST['empleados']; // Este será un array con los RPEs seleccionados
        foreach ($empleados_seleccionados as $rpe) {
            // Realiza las operaciones necesarias con cada RPE
            echo "Empleado seleccionado: " . htmlspecialchars($rpe) . "<br>"; // Muestra el RPE de cada empleado seleccionado
        }
    } else {
        echo "No se seleccionaron empleados.";
    }
} else {
    echo "Método de solicitud no permitido.";
}
?>

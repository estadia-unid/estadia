<?php

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_act = $_POST["fecha_act"];
    $nombre_departamento = $_POST["departamento"];

    // Validar formato de fecha
    if (DateTime::createFromFormat('Y-m-d', $fecha_act) === false) {
        $fecha_act = date('Y-m-d', strtotime($fecha_act));
    }

    // Consulta SQL con JOIN
    $sql = "SELECT rc.rpe, rc.h_inicio, rc.h_termino, e.nombre, e.a_paterno, e.a_materno, e.categ 
            FROM r_check rc
            INNER JOIN departamentos d ON rc.id_dep = d.id_departamento
            INNER JOIN empleados e ON rc.rpe = e.rpe
            WHERE d.departamento = '$nombre_departamento' AND rc.fecha = '$fecha_act'";
    $result = $conecta2->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-sm'>
                <thead>
                <tr>
                    <th>RPE</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            $rpe = $row["rpe"];
            $hora_entrada = $row["h_inicio"];
            $hora_salida = $row["h_termino"];

            echo "<tr>
                    <td>" . $rpe . "</td>
                    <td>" . $row["nombre"] . " " . $row["a_paterno"] . " " . $row["a_materno"] . "</td>
                    <td>" . $row["categ"] . "</td>
                    <td>" . $hora_entrada . "</td>
                    <td>" . $hora_salida . "</td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No se encontraron resultados.";
    }

    $conecta->close();
    $conecta2->close();
}
?>
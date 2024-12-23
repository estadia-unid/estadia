<?php
include 'conexion.php';

// Consulta para obtener los departamentos
$sql = "SELECT id_departamento, departamento FROM departamentos";
$result = $conecta->query($sql);

$departamentos = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $departamentos[$row["id_departamento"]] = $row["departamento"];
  }
}

// Devolver los departamentos en formato JSON
echo json_encode($departamentos);

$conecta->close();
?>

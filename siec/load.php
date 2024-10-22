<?php

/**
 * Script para cargar datos de lado del servidor con PHP y MySQL
 *
 * @author mroblesdev
 * @link https://github.com/mroblesdev/server-side-php
 * @license: MIT
 */

require "../conexion.php";

// Columnas a mostrar en la tabla
$columns = ['id_computadora', 'departamento', 'puesto', 'usuario_responsable', 'rpe', 'tipo_de_equipo', 'activo_fijo', 'inventario', 'numero_de_serie', 'marca', 'modelo', 'mac_wifi', 'mac_ethernet', 'memoria', 'disco_duro', 'dominio', 'resg', 'd_activo', 'antivirus', 'observaciones'];

// Nombre de la tabla
$table = "computadoras";

// Clave principal de la tabla
$id = 'id_computadora';

// Campo a buscar
$campo = isset($_POST['campo']) ? $conecta->real_escape_string($_POST['campo']) : null;

// Filtrado
$where = '';

if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

// Limites
$limit = isset($_POST['registros']) ? $conecta->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conecta->real_escape_string($_POST['pagina']) : 0;

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}

$sLimit = "LIMIT $inicio , $limit";

// Ordenamiento

$sOrder = "";
if (isset($_POST['orderCol'])) {
    $orderCol = $_POST['orderCol'];
    $oderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

    $sOrder = "ORDER BY " . $columns[intval($orderCol)] . ' ' . $oderType;
}

// Consulta
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
$where
$sOrder
$sLimit";
$resultado = $conecta->query($sql);
$num_rows = $resultado->num_rows;

// Consulta para total de registro filtrados
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conecta->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_array();
$totalFiltro = $row_filtro[0];

// Consulta para total de registro
$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conecta->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];

// Mostrado resultados
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['departamento'] . '</td>';
        $output['data'] .= '<td>' . $row['puesto'] . '</td>';
        $output['data'] .= '<td>' . $row['usuario_responsable'] . '</td>';
        $output['data'] .= '<td>' . $row['rpe'] . '</td>';
        $output['data'] .= '<td>' . $row['tipo_de_equipo'] . '</td>';
        $output['data'] .= '<td>' . $row['activo_fijo'] . '</td>';
        $output['data'] .= '<td>' . $row['inventario'] . '</td>';
        $output['data'] .= '<td>' . $row['numero_de_serie'] . '</td>';
        $output['data'] .= '<td>' . $row['marca'] . '</td>';
        $output['data'] .= '<td>' . $row['modelo'] . '</td>';
        $output['data'] .= '<td>' . $row['mac_wifi'] . '</td>';
        $output['data'] .= '<td>' . $row['mac_ethernet'] . '</td>';
        $output['data'] .= '<td>' . $row['memoria'] . '</td>';
        $output['data'] .= '<td>' . $row['disco_duro'] . '</td>';
        $output['data'] .= '<td>' . $row['dominio'] . '</td>';
        $output['data'] .= '<td>' . $row['resg'] . '</td>';
        $output['data'] .= '<td>' . $row['d_activo'] . '</td>';
        $output['data'] .= '<td>' . $row['antivirus'] . '</td>';
        $output['data'] .= '<td>' . $row['observaciones'] . '</td>';
        $output['data'] .= '<td><a href="editar.php?id=' . $row['id_computadora'] . '">Editar</a></td>';
        $output['data'] .= '<td><a href="elimiar.php?id=' . $row['id_computadora'] . '">Eliminar</a></td>';
        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7">Sin resultados</td>';
    $output['data'] .= '</tr>';
}

// Paginación
if ($totalRegistros > 0) {
    $totalPaginas = ceil($totalFiltro / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $numeroInicio = max(1, $pagina - 4);
    $numeroFin = min($totalPaginas, $numeroInicio + 9);

    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {
        $output['paginacion'] .= '<li class="page-item' . ($pagina == $i ? ' active' : '') . '">';
        $output['paginacion'] .= '<a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a>';
        $output['paginacion'] .= '</li>';
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

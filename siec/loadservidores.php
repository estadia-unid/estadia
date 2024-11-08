<?php

/**
 * Script para cargar datos de lado del servidor con PHP y MySQL
 *
 * @author mroblesdev
 * @link https://github.com/mroblesdev/server-side-php
 * @license: MIT
 */

 session_start();
    date_default_timezone_set('America/Mexico_City');
      $conecta =  mysqli_connect('localhost', 'siec', 'ctpalm2113', 'siec');
      if(!$conecta){
          die('no pudo conectarse:' . mysqli_connect_error());
       }
    if (!mysqli_set_charset($conecta,'utf8')) {
     die('No pudo conectarse: ' . mysqli_error($conecta));
     }

// Columnas a mostrar en la tabla
$columns = ['id_servidor', 'marca', 'modelo', 'num_serie', 'procesador', 'velocidad', 'ram', 'ip', 'activo_fijo', 'inventario', 'observaciones'];

// Nombre de la tabla
$table = "servidores";

// Clave principal de la tabla
$id = 'id_servidor';

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
        $output['data'] .= '<td>' . $row['marca'] . '</td>';
        $output['data'] .= '<td>' . $row['modelo'] . '</td>';
        $output['data'] .= '<td>' . $row['num_serie'] . '</td>';
        $output['data'] .= '<td>' . $row['procesador'] . '</td>';
        $output['data'] .= '<td>' . $row['velocidad'] . '</td>';
        $output['data'] .= '<td>' . $row['ram'] . '</td>';
        $output['data'] .= '<td>' . $row['ip'] . '</td>';
        $output['data'] .= '<td>' . $row['activo_fijo'] . '</td>';
        $output['data'] .= '<td>' . $row['inventario'] . '</td>';
        $output['data'] .= '<td>' . $row['observaciones'] . '</td>';
        $output['data'] .= '<td><div class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Opciones </button>' . 
        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"> ' . 
        '<li><a class="dropdown-item rounded-2" href="editar.php?id=' . $row['id_servidor'] . '">Editar</a></li>' .
        '<li><a class="dropdown-item rounded-2" href="elimiar.php?id=' . $row['id_servidor'] . '">Eliminar</a></li>' . 
        '</ul></div></td>';
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

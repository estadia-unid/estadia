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
$columns = ['id_computadora', 'departamento', 'nombre', 'a_paterno', 'escritorio_remoto', 'a_materno', 'computadoras.rpe', 'activo_fijo', 'inventario', 'numero_de_serie', 'marca', 'modelo', 'mac_wifi', 'mac_ethernet', 'memoria', 'disco_duro', 'dominio', 'resg', 'd_activo', 'antivirus', 'observaciones'];

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
INNER JOIN empleados ON computadoras.rpe = empleados.rpe
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
        $output['data'] .= '<td>' . $row['nombre'] . '  ' . $row['a_paterno'] . '  ' . $row['a_materno'] . '<div class="dropdown">' . '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">' . $row['rpe'] . '</button>' . 
        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"> ' . 
        '<li><a class="dropdown-item rounded-2">' .$row['rpe'] . '</a></li>' .
        '<li><a class="dropdown-item rounded-2">' .$row['rpe'] . '</a></li>' . 
        '<li><a class="dropdown-item rounded-2">' .$row['rpe'] . '</a></li>' . 
        '<li><a class="dropdown-item rounded-2">' .$row['rpe'] . '</a></li>' . 
        '</ul>' . '</div>' .  
        '</td>';
        $output['data'] .= '<td>' . $row['activo_fijo'] . '</td>';
        $output['data'] .= '<td>' . $row['inventario'] . '</td>';
        $output['data'] .= '<td>' . $row['numero_de_serie'] . '</td>';
        $output['data'] .= '<td>' . $row['marca'] . '' . $row['modelo'] . '</td>';
        $output['data'] .= '<td>' . $row['mac_wifi'] . '</td>';
        $output['data'] .= '<td>' . $row['mac_ethernet'] . '</td>';
        $output['data'] .= '<td>' . $row['memoria'] . '</td>';
        $output['data'] .= '<td>' . $row['disco_duro'] . '</td>';
        $output['data'] .= '<td>' . $row['dominio'] . '</td>';
        $output['data'] .= '<td><div class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Opciones </button>' . 
        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"> ' . 
        '<li><a class="dropdown-item rounded-2"> Resguardo: ' . $row['resg'] . '</a></li>' .
        '<li><a class="dropdown-item rounded-2"> Directorio activo: ' . $row['d_activo'] . '</a></li>' .
        '<li><a class="dropdown-item rounded-2"> Antivirus: ' . $row['antivirus'] . '</a></li>' . 
        '<li><a class="dropdown-item rounded-2"> Escritorio Remoto: ' . $row['escritorio_remoto'] . '</a></li>' . 
        '</ul></div></td>';
        $output['data'] .= '<td>' . $row['observaciones'] . '</td>';
        $output['data'] .= '<td><div class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> Opciones </button>' . 
        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"> ' . 
        '<li><a class="dropdown-item rounded-2" href="editar.php?id=' . $row['id_computadora'] . '">Editar</a></li>' .
        '<li><a class="dropdown-item rounded-2" href="elimiar.php?id=' . $row['id_computadora'] . '">Eliminar</a></li>' . 
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

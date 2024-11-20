<?php
// Conexión a la base de datos usando PDO
$contraseña = "ctpalm2113";
$usuario = "siec";
$nombre_base_de_datos = "siec";
try {
    $conecta = new PDO('mysql:host=localhost;dbname=' . $nombre_base_de_datos, $usuario, $contraseña);
    $conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conecta->exec("SET NAMES utf8");
} catch (Exception $e) {
    echo json_encode(['error' => 'Ocurrió algo con la base de datos: ' . $e->getMessage()]);
    exit;
}

// Validar parámetros dinámicos
$table = isset($_POST['table']) ? htmlspecialchars($_POST['table']) : null;
$columns = isset($_POST['columns']) ? explode(',', htmlspecialchars($_POST['columns'])) : [];
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : 'id';

if (!$table || empty($columns)) {
    echo json_encode(['error' => 'Tabla o columnas no especificadas']);
    exit;
}

// Campo de búsqueda
$campo = isset($_POST['campo']) ? htmlspecialchars($_POST['campo']) : null;

// Filtrado
$where = '';
if ($campo) {
    $filters = [];
    foreach ($columns as $column) {
        $filters[] = "$column LIKE :campo";
    }
    $where = 'WHERE ' . implode(' OR ', $filters);
}

// Límite y paginación
$limit = isset($_POST['registros']) ? intval($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
$inicio = ($pagina - 1) * $limit;

// Orden
$orderCol = isset($_POST['orderCol']) ? intval($_POST['orderCol']) : 0;
$orderType = isset($_POST['orderType']) && strtolower($_POST['orderType']) === 'desc' ? 'DESC' : 'ASC';
$order = "ORDER BY {$columns[$orderCol]} $orderType";

// Consultar datos dinámicamente
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(', ', $columns) . " FROM $table $where $order LIMIT :inicio, :limit";
$stmt = $conecta->prepare($sql);
if ($campo) {
    $stmt->bindValue(':campo', "%$campo%", PDO::PARAM_STR);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros filtrados
$sqlFiltro = "SELECT FOUND_ROWS()";
$totalFiltro = $conecta->query($sqlFiltro)->fetchColumn();

// Total de registros
$sqlTotal = "SELECT COUNT($id) FROM $table";
$totalRegistros = $conecta->query($sqlTotal)->fetchColumn();

// Generar salida
$output = [
    'totalRegistros' => $totalRegistros,
    'totalFiltro' => $totalFiltro,
    'data' => '',
    'paginacion' => ''
];

$editFile = isset($_POST['editFile']) ? htmlspecialchars($_POST['editFile']) : null;
$deleteFile = isset($_POST['deleteFile']) ? htmlspecialchars($_POST['deleteFile']) : null;

if (!empty($data)) {
    foreach ($data as $row) {
        $output['data'] .= '<tr>';
        foreach ($columns as $column) {
            $output['data'] .= '<td>' . htmlspecialchars($row[$column]) . '</td>';
        }

        // Menú desplegable con archivos dinámicos
        $output['data'] .= '<td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item rounded-2" href="' . $editFile . '?editar=' . $row[$id] . '">Editar</a></li>
                    <li><a class="dropdown-item rounded-2" href="' . $deleteFile . '?borrar=' . $row[$id] . '">Eliminar</a></li>
                </ul>
            </div>
        </td>';

        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr><td colspan="' . (count($columns) + 1) . '">Sin resultados</td></tr>';
}

// Paginación
$totalPaginas = ceil($totalFiltro / $limit);
$output['paginacion'] .= '<nav><ul class="pagination">';
for ($i = 1; $i <= $totalPaginas; $i++) {
    $active = ($i == $pagina) ? 'active' : '';
    $output['paginacion'] .= "<li class='page-item $active'><a class='page-link' href='#' onclick='nextPage($i)'>$i</a></li>";
}
$output['paginacion'] .= '</ul></nav>';

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>

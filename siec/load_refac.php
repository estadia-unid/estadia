<?php
include_once 'conexion.php';
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null; // ID de la tabla (clave primaria)
$table = isset($_POST['table']) ? htmlspecialchars($_POST['table']) : null;
$columns = isset($_POST['columns']) ? explode(',', htmlspecialchars($_POST['columns'])) : [];

// Tablas específicas para mostrar el botón "Enviar a refaccionamiento"
$tablasConRefaccionamiento = ['refac_computadoras', 'refac_switches','refac_servidores','refac_laptops','refac_aps']; // Cambia estos nombres a los de tus tablas

// Validar parámetros
if (!$table || !$columns || !$id) {
    echo json_encode(['error' => 'Faltan parámetros necesarios']);
    exit;
}

// Campo de búsqueda
$campo = isset($_POST['campo']) ? htmlspecialchars($_POST['campo']) : null;

// Construir consulta SQL
$selectColumns = implode(', ', $columns) . ", $id"; // Agregar el ID a la consulta sin mostrarlo en la tabla
$where = '';
if ($campo) {
    $filters = [];
    foreach ($columns as $column) {
        $filters[] = "$column LIKE :campo";
    }
    $where = 'WHERE ' . implode(' OR ', $filters);
}

// Orden y paginación
$limit = isset($_POST['registros']) ? intval($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
$inicio = ($pagina - 1) * $limit;
$orderCol = isset($_POST['orderCol']) ? intval($_POST['orderCol']) : 0;
$orderType = isset($_POST['orderType']) && strtolower($_POST['orderType']) === 'desc' ? 'DESC' : 'ASC';
$order = "ORDER BY {$columns[$orderCol]} $orderType";

// Consultar datos
$sql = "SELECT SQL_CALC_FOUND_ROWS $selectColumns FROM $table $where $order LIMIT :inicio, :limit";
$stmt = $conecta->prepare($sql);
if ($campo) {
    $stmt->bindValue(':campo', "%$campo%", PDO::PARAM_STR);
}
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros filtrados
$totalFiltro = $conecta->query("SELECT FOUND_ROWS()")->fetchColumn();

// Total de registros
$totalRegistros = $conecta->query("SELECT COUNT($id) FROM $table")->fetchColumn();

// Generar salida
$output = [
    'totalRegistros' => $totalRegistros,
    'totalFiltro' => $totalFiltro,
    'data' => '',
    'paginacion' => ''
];

if ($data) {
    foreach ($data as $row) {
        $output['data'] .= '<tr>';
        foreach ($columns as $column) {
            $output['data'] .= '<td>' . htmlspecialchars($row[$column]) . '</td>';
        }

        // Agregar acciones de edición, eliminación y "Enviar a refaccionamiento"
        $output['data'] .= '<td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="' . htmlspecialchars($_POST['deleteFile']) . '?borrar=' . $row[$id] . '">Eliminar</a></li>';

        // Mostrar botón "Enviar a refaccionamiento" solo para tablas específicas
        if (in_array($table, $tablasConRefaccionamiento)) {
            $output['data'] .= '<li><a class="dropdown-item" href="' . htmlspecialchars($_POST['deleteFile']) . '?refaccionamiento=' . $row[$id] . '">Reactivar</a></li>';
        }

        $output['data'] .= '</ul>
            </div>
        </td>';

        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr><td colspan="' . count($columns) . '">Sin resultados</td></tr>';
}
// Paginación
$totalPaginas = ceil($totalFiltro / $limit);
$output['paginacion'] = '<nav><ul class="pagination">';
for ($i = 1; $i <= $totalPaginas; $i++) {
    $active = ($i == $pagina) ? 'active' : '';
    $output['paginacion'] .= "<li class='page-item $active'><a class='page-link' href='#' onclick='nextPage($i)'>$i</a></li>";
}
$output['paginacion'] .= '</ul></nav>';

// Enviar respuesta como JSON
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>

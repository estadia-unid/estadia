<?php
include_once "conexion.php";

class ControlFormulario{

    function __construct($conecta) {
    }

    function crear($conecta) {
        $insertar = "INSERT INTO ";
    }

    function leer($conecta) {
        $leer = "SELECT * FROM `computadoras`";
        $resultado_leer = $conecta->prepare($leer);
        $resultado_leer->execute([]);
        while ($leer_vista = $resultado_leer->fetch(PDO::FETCH_ASSOC)){
            echo $leer_vista['rpe'] . "<br>";
            
        }
        
    }
    
    function actualizar(){
        $actualizar = "UPDATE `computadoras` SET `id_computadora`='[value-1]',`oficial`='[value-2]',`no_oficial`='[value-3]',`departamento`='[value-4]',`puesto`='[value-5]',`usuario_responsable`='[value-6]',`rpe`='[value-7]',`tipo_de_equipo`='[value-8]',`activo_fijo`='[value-9]',`inventario`='[value-10]',`numero_de_serie`='[value-11]',`marca`='[value-12]',`modelo`='[value-13]',`mac_wifi`='[value-14]',`mac_ethernet`='[value-15]',`memoria`='[value-16]',`disco_duro`='[value-17]',`dominio`='[value-18]',`resg`='[value-19]',`d_activo`='[value-20]',`antivirus`='[value-21]',`observaciones`='[value-22]' WHERE 1";
        $resultado_actualizar = $conecta->prepare($actualizar);
        $resultado_sesion->execute([
            ':usuario' => $this->usuario,
            ':clave' => $this->contraseña
        ]);
    }
    function borrar(){

    }
    
    function busqueda($conecta){
    // Columnas a mostrar en la tabla
    $columns = "DESCRIBE computadoras";
    
    // Nombre de la tabla
    $table = "computadoras";
    
    // Clave principal de la tabla
    $id = 'id_computadora';
    
    // Campo a buscar
    $campo = isset($_POST['campo']) ? $_POST['campo'] : null;
    
    // Filtrado
    $where = '';
    
    if ($campo != null) {
        $where = "WHERE (";
    
        $cont = count($columns[0]);
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
    $resultado = $conecta->prepare($sql);
    $num_rows = $resultado->num_rows;
    
    // Consulta para total de registro filtrados
    $Filtro = $conecta->query("SELECT FOUND_ROWS()");
    $row_filtro = $Filtro->fetch_array();
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
            $output['data'] .= '<td>' . $row['rpe'] . '</td>';
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
    
    }
}
$visual = new ControlFormularios('');
$visual->($conecta);
?>
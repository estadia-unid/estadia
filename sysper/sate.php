<?php
require('fpdf.php');

if (isset($_POST['id_registro'])) {
    $id_registro = $_POST['id_registro'];

    // Conexión a la base de datos
    $servidor = "localhost";
    $usuario = "skyper";
    $password = "ctpalm2113";
    $bd = "estadiaunid";

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta principal para obtener los datos
        $stmt = $pdo->prepare("
            SELECT r.id AS id_registro, e.categ AS categoria, 
                   CONCAT(e.nombre, ' ', e.a_paterno, ' ', e.a_materno) AS nombre_trabajador,
                   e.rpe, r.fecha_registro AS fecha, r.hora_inicio AS inicio, 
                   r.hora_termino AS termino, r.horas_extra AS horas, r.actividades AS actividades,
                   d.numero_orden AS no_orden, r.justificacion AS justificacion
            FROM registros r
            JOIN empleados_asignados ea ON r.id = ea.id_registro
            JOIN empleados e ON ea.rpe = e.rpe
            JOIN detalles_orden d ON r.id = d.id_registro
            WHERE r.id = ?
        ");
        $stmt->execute([$id_registro]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if (empty($registros)) {
        die("No se encontraron registros con el ID proporcionado.");
    }
    class PDF extends FPDF {
        // Encabezado
        function Header() {
            global $pdo, $id_registro;
            $stmt = $pdo->prepare("
                SELECT d.departamento 
                FROM departamentos d
                JOIN detalles_orden o ON d.id_departamento = o.id_departamento
                WHERE o.id_registro = ?;
            ");
            $stmt->execute([$id_registro]);
            $depa = $stmt->fetchColumn() ?: "No disponible";

            $this->Image('../imagenes/svg/cfe_icon.png', 10, 8, 50);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(300, 5, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'C');
            $this->Cell(300, 5, 'SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO', 0, 1, 'C');
            $this->Cell(300, 5, 'DEPARTAMENTO: ' . htmlspecialchars($depa), 0, 1, 'C');
            $this->Ln(5);
        }

        // Pie de página
        function Footer() {
            global $pdo, $id_registro;

            $stmt = $pdo->prepare("
                SELECT d.departamento 
                FROM departamentos d
                JOIN detalles_orden o ON d.id_departamento = o.id_departamento
                WHERE o.id_registro = ?;
            ");
            $stmt->execute([$id_registro]);
            $depa = $stmt->fetchColumn() ?: "No disponible";

            $stmt_jefe = $pdo->prepare("
                SELECT j.nombre_jefe 
                FROM jefes_dpto j
                INNER JOIN departamentos d ON j.id_departamento = d.id_departamento
                JOIN detalles_orden o ON d.id_departamento = o.id_departamento
                WHERE o.id_registro = ?;
            ");
            $stmt_jefe->execute([$id_registro]);
            $jefe = $stmt_jefe->fetchColumn() ?: "Sin jefe asignado";

            $this->SetY(-30);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
            $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
            $this->Cell(90, 5, 'AUTORIZA', 0, 1, 'C');
            $this->Cell(90, 0, '', 'T', 0, 'C');
            $this->Cell(90, 0, '', 'T', 0, 'C');
            $this->Cell(90, 0, '', 'T', 1, 'C');
            $this->Ln(2);
            $this->Cell(90, 5, 'ING. ' . htmlspecialchars($jefe), 0, 0, 'C');
            $this->Cell(90, 5, 'ING. CESAR IVAN CRUZ CHAVEZ', 0, 0, 'C');
            $this->Cell(90, 5, 'ING. MIGUEL ANGEL MARQUEZ DOMINGUEZ', 0, 1, 'C');
            $this->Cell(90, 5, 'JEFE DE DEPARTAMENTO: ' . htmlspecialchars($depa), 0, 0, 'C');
            $this->Cell(90, 5, 'SUPERINTENDENTE DE MANTENIMIENTO', 0, 0, 'C');
            $this->Cell(90, 5, 'SUPERINTENDENTE GENERAL', 0, 0, 'C');
        }

       // Cuerpo del reporte
       function ReportBody($registros) {
        $this->SetFont('Arial', 'B', 6);

        // Encabezados de tabla
        $headers = ['CATEGORIA', 'NOMBRE DEL TRABAJADOR', 'RPE', 'FECHA', 'INICIO', 'TERMINO', 'No. HORAS', 'ACTIVIDADES REALIZADAS', 'No. ORDEN', 'JUSTIFICACION TECNICA', 'OM'];
        $widths = [25, 50, 20, 20, 15, 15, 15, 40, 20, 50, 15];
        $height = 10; // Altura de cada fila

        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i], $height, $header, 1, 0, 'C');
        }
        $this->Ln();

      // Datos de la tabla
$this->SetFont('Arial', '', 6);
foreach ($registros as $registro) {
    $x = $this->GetX();
    $y = $this->GetY();
    $rowHeights = [];

    // Calcular las alturas requeridas para cada celda
    $rowHeights[] = $this->GetMultiCellHeight($widths[0], $height, $registro['categoria']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[1], $height, $registro['nombre_trabajador']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[2], $height, $registro['rpe']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[3], $height, $registro['fecha']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[4], $height, $registro['inicio']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[5], $height, $registro['termino']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[6], $height, $registro['horas']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[7], $height / 2, $registro['actividades']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[8], $height, $registro['no_orden']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[9], $height / 2, $registro['justificacion']);
    $rowHeights[] = $this->GetMultiCellHeight($widths[10], $height, $registro['om']);

    // Altura máxima de la fila
    $maxHeight = max($rowHeights);

    // Dibujar cada celda con la misma altura
    $this->MultiCell($widths[0], $maxHeight, $registro['categoria'], 1, 'C');
    $this->SetXY($x + $widths[0], $y);

    $this->MultiCell($widths[1], $maxHeight, $registro['nombre_trabajador'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 2)), $y);

    $this->MultiCell($widths[2], $maxHeight, $registro['rpe'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 3)), $y);

    $this->MultiCell($widths[3], $maxHeight, $registro['fecha'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 4)), $y);

    $this->MultiCell($widths[4], $maxHeight, $registro['inicio'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 5)), $y);

    $this->MultiCell($widths[5], $maxHeight, $registro['termino'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 6)), $y);

    $this->MultiCell($widths[6], $maxHeight, $registro['horas'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 7)), $y);

    $this->MultiCell($widths[7], $maxHeight, $registro['actividades'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 8)), $y);

    $this->MultiCell($widths[8], $maxHeight, $registro['no_orden'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 9)), $y);

    $this->MultiCell($widths[9], $maxHeight, $registro['justificacion'], 1, 'C');
    $this->SetXY($x + array_sum(array_slice($widths, 0, 10)), $y);

    $this->MultiCell($widths[10], $maxHeight, $registro['om'], 1, 'C');
    $this->Ln($maxHeight);
}

$pdf = new PDF('L');
$pdf->AddPage();
$pdf->ReportBody($registros);
$pdf->Output();
} else {
echo "No se recibió un ID de registro.";
}
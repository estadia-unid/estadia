<?php
require('fpdf.php');

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "skyper";
$password = "ctpalm2113";
$bd = "estadiaunid";

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta de los registros principales
    $stmt = $pdo->prepare("SELECT * FROM registros");
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

class PDF extends FPDF {
    function Header() {
        global $depa;  // Variable global para 'DEPARTAMENTO'
        $this->Image('../imagenes/svg/cfe_icon.png', 10, 8, 50);  // Añade la imagen en la parte superior izquierda
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(300, 5, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'C');
        $this->Cell(300, 5, 'SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO', 0, 1, 'C');
        $this->Cell(300, 5, 'DEPARTAMENTO PROGRAMACION Y CONTROL' . $depa, 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-30); // Posiciona el pie de página 30 unidades desde el final de la página
        $this->SetFont('Arial', 'B', 8);

        // Agregar las secciones en tres columnas
        $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
        $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
        $this->Cell(90, 5, 'AUTORIZA', 0, 1, 'C');

        // Línea debajo de los títulos
        $this->Cell(90, 0, '', 'T', 0, 'C');
        $this->Cell(90, 0, '', 'T', 0, 'C');
        $this->Cell(90, 0, '', 'T', 1, 'C');

        // Espacio entre línea y texto
        $this->Ln(2);

        // Nombres y posiciones
        $this->Cell(90, 5, 'ING. ALAN JAVIER VELAZQUEZ SALAZAR', 0, 0, 'C');
        $this->Cell(90, 5, 'ING. CESAR IVAN CRUZ CHAVEZ', 0, 0, 'C');
        $this->Cell(90, 5, 'ING. APOLINAR ORTIZ VALENCIA', 0, 1, 'C');

        $this->Cell(90, 5, 'JEFE DE DEPARTAMENTO ELÉCTRICO', 0, 0, 'C');
        $this->Cell(90, 5, 'SUPERINTENDENTE DE MANTENIMIENTO', 0, 0, 'C');
        $this->Cell(90, 5, 'SUPERINTENDENTE GENERAL', 0, 0, 'C');
    }

    function ReportBody($registros, $pdo) {
        $this->SetFont('Arial', 'B', 7);
        
        // Definir anchos de columna
        $w1 = 25;  // CATEGORIA
        $w2 = 50;  // NOMBRE
        $w3 = 20;  // RPE
        $w4 = 20;  // FECHA
        $w5 = 15;  // INICIO
        $w6 = 15;  // TERMINO
        $w7 = 15;  // No. HORAS
        $w8 = 40;  // ACTIVIDADES
        $w9 = 20;  // No. ORDEN
        $w10 = 50; // JUSTIFICACION
        $w11 = 15; // O.M.
        
        $h = 8; // Altura estándar

        // Cabeceras
        $this->Cell($w1, $h, 'CATEGORIA', 1, 0, 'C');
        $this->Cell($w2, $h, 'NOMBRE DEL TRABAJADOR', 1, 0, 'C');
        $this->Cell($w3, $h, 'RPE', 1, 0, 'C');
        $this->Cell($w4, $h, 'FECHA', 1, 0, 'C');
        $this->Cell($w5, $h, 'INICIO', 1, 0, 'C');
        $this->Cell($w6, $h, 'TERMINO', 1, 0, 'C');
        $this->Cell($w7, $h, 'No. HORAS', 1, 0, 'C');
        $this->Cell($w8, $h, 'ACTIVIDADES REALIZADAS', 1, 0, 'C');
        $this->Cell($w9, $h, 'No. ORDEN', 1, 0, 'C');
        $this->Cell($w10, $h, 'JUSTIFICACION TECNICA', 1, 0, 'C');
        $this->Cell($w11, $h, 'O.M.', 1, 1, 'C');  // Última celda en la fila
        
        // Contenido
        $this->SetFont('Arial', '', 7);
        foreach ($registros as $registro) {
            // Obtener empleados relacionados con el registro
            $stmt = $pdo->prepare("
                SELECT e.categ, e.nombre, e.a_paterno, e.a_materno, ea.rpe
                FROM empleados_asignados ea
                JOIN empleados e ON e.rpe = ea.rpe
                WHERE ea.id_registro = ?
            ");
            $stmt->execute([$registro['id']]);
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($empleados as $empleado) {
                $this->CheckPageBreak($h);  // Verifica si es necesario un salto de página
                $nombre_completo = $empleado['nombre'] . ' ' . $empleado['a_paterno'] . ' ' . $empleado['a_materno'];
                
                // Crear celdas de contenido
                $this->Cell($w1, $h, $empleado['categ'], 1, 0, 'C');
                $this->Cell($w2, $h, $nombre_completo, 1, 0, 'C');
                $this->Cell($w3, $h, $empleado['rpe'], 1, 0, 'C');
                $this->Cell($w4, $h, $registro['fecha_registro'], 1, 0, 'C');
                $this->Cell($w5, $h, $registro['hora_inicio'], 1, 0, 'C');
                $this->Cell($w6, $h, $registro['hora_termino'], 1, 0, 'C');
                $this->Cell($w7, $h, $registro['horas_extra'], 1, 0, 'C');

                // MultiCell para actividades (ajustando posición)
                $x = $this->GetX();
                $y = $this->GetY();
                $this->MultiCell($w8, $h, $registro['actividades'], 1, 'C');
                $this->SetXY($x + $w8, $y);

                // Obtener y mostrar órdenes en una sola celda
                $stmt = $pdo->prepare("SELECT numero_orden FROM detalles_orden WHERE id_registro = ?");
                $stmt->execute([$registro['id']]);
                $ordenes = implode(', ', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'numero_orden'));
                $this->Cell($w9, $h, $ordenes, 1, 0, 'C');

                // Justificación técnica 
                $this->Cell($w10, $h, $registro['justificacion'], 1, 0, 'C');

                // Obtener y mostrar OM en una sola celda
                $stmt = $pdo->prepare("SELECT om FROM detalles_orden WHERE id_registro = ?");
                $stmt->execute([$registro['id']]);
                $ordenes = implode(', ', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'om'));
                $this->Cell($w11, $h, $ordenes, 1, 0, 'C');
                $this->Ln();
            }
        }
    }
    

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }
}

$pdf = new PDF('L');
$pdf->AddPage();
$pdf->ReportBody($registros, $pdo);
$pdf->Output();
?>

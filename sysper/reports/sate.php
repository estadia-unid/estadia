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

        // Consulta principal para obtener el registro específico
        $stmt = $pdo->prepare("SELECT * FROM registros WHERE id = ?");
        $stmt->execute([$id_registro]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    if (empty($registros)) {
        die("No se encontraron registros con el ID proporcionado.");
    }
     

    class PDF extends FPDF {

            function Header() {
                global $pdo, $id_registro; // Asegúrate de que $pdo y $id_registro estén disponibles globalmente
                // Consulta para obtener el departamento
                $stmt = $pdo->prepare("
                SELECT d.departamento 
                FROM departamentos d
                JOIN detalles_orden o ON d.id_departamento = o.id_departamento
                WHERE o.id_registro = ?;
                ");
                $stmt->execute([$id_registro]);
                $depa = $stmt->fetchColumn(); // Obtiene el departamento (o NULL si no encuentra datos)
                if (!$depa) {
                    $depa = "No disponible"; // Valor por defecto si no se encuentra el departamento
                }
            
                $this->Image('../imagenes/svg/cfe_icon.png', 10, 8, 50);  // Imagen en la parte superior izquierda
                $this->SetFont('Arial', 'B', 8);
                $this->Cell(300, 5, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'C');
                $this->Cell(300, 5, 'SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO', 0, 1, 'C');
                $this->Cell(300, 5, 'DEPARTAMENTO: ' . htmlspecialchars($depa), 0, 1, 'C'); // Muestra el departamento
                $this->Ln(5);
            }
            

        function Footer() {
            global $pdo, $id_registro; // Asegúrate de que $pdo y $id_registro estén disponibles globalmente
               // Consulta para obtener el departamento
               $stmt = $pdo->prepare("
               SELECT d.departamento 
               FROM departamentos d
               JOIN detalles_orden o ON d.id_departamento = o.id_departamento
               WHERE o.id_registro = ?;
               ");
               $stmt->execute([$id_registro]);
               $depa = $stmt->fetchColumn(); // Obtiene el departamento (o NULL si no encuentra datos)
               if (!$depa) {
                   $depa = "No disponible"; // Valor por defecto si no se encuentra el departamento
               }

            // Consulta para obtener el departamento
            $stmt_jefe = $pdo->prepare("
            SELECT j.nombre_jefe 
            FROM jefes_dpto j
            INNER JOIN departamentos d ON j.id_departamento = d.id_departamento
            JOIN detalles_orden o ON d.id_departamento = o.id_departamento
            WHERE o.id_registro = ?;
        ");
        $stmt_jefe->execute([$id_registro]);
        $jefe = $stmt_jefe->fetchColumn() ?: "Sin jefe asignado";
            $this->SetY(-30); // Posiciona el pie de página
            $this->SetFont('Arial', 'B', 8);

            $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
            $this->Cell(90, 5, 'Vo.Bo.', 0, 0, 'C');
            $this->Cell(90, 5, 'AUTORIZA', 0, 1, 'C');

            $this->Cell(90, 0, '', 'T', 0, 'C');
            $this->Cell(90, 0, '', 'T', 0, 'C');
            $this->Cell(90, 0, '', 'T', 1, 'C');

            $this->Ln(2);

            $this->Cell(90, 5, 'ING.' . htmlspecialchars($jefe), 0, 0, 'C');
            $this->Cell(90, 5, 'ING. CESAR IVAN CRUZ CHAVEZ', 0, 0, 'C');
            $this->Cell(90, 5, 'ING. APOLINAR ORTIZ VALENCIA', 0, 1, 'C');

            $this->Cell(90, 5, 'JEFE DE DEPARTAMENTO:' . htmlspecialchars($depa) , 0, 0, 'C');
            $this->Cell(90, 5, 'SUPERINTENDENTE DE MANTENIMIENTO', 0, 0, 'C');
            $this->Cell(90, 5, 'SUPERINTENDENTE GENERAL', 0, 0, 'C');
        }

        function ReportBody($registros, $pdo) {
            $this->SetFont('Arial', 'B', 6);

            // Definir anchos de columna
            $w1 = 25;
            $w2 = 50;
            $w3 = 20;
            $w4 = 20;
            $w5 = 15;
            $w6 = 15;
            $w7 = 15;
            $w8 = 40;
            $w9 = 20;
            $w10 = 50;
            $w11 = 15;
            $h = 8;

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
            $this->Cell($w11, $h, 'O.M.', 1, 1, 'C');

            $this->SetFont('Arial', '', 7);
            foreach ($registros as $registro) {
                $stmt = $pdo->prepare("
                    SELECT e.categ, e.nombre, e.a_paterno, e.a_materno, ea.rpe
                    FROM empleados_asignados ea
                    JOIN empleados e ON e.rpe = ea.rpe
                    WHERE ea.id_registro = ?
                ");
                $stmt->execute([$registro['id']]);
                $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($empleados as $empleado) {
                    $this->Cell($w1, $h, $empleado['categ'], 1, 0, 'C');
                    $nombre_completo = $empleado['nombre'] . ' ' . $empleado['a_paterno'] . ' ' . $empleado['a_materno'];
                    $this->Cell($w2, $h, $nombre_completo, 1, 0, 'C');
                    $this->Cell($w3, $h, $empleado['rpe'], 1, 0, 'C');
                    $this->Cell($w4, $h, $registro['fecha_registro'], 1, 0, 'C');
                    $this->Cell($w5, $h, $registro['hora_inicio'], 1, 0, 'C');
                    $this->Cell($w6, $h, $registro['hora_termino'], 1, 0, 'C');
                    $this->Cell($w7, $h, $registro['horas_extra'], 1, 0, 'C');
                    $x = $this->GetX(); 
                    $y = $this->GetY();
                    $this->MultiCell($w8, $h, $registro['actividades'], 1, 'C');
                    $this->SetXY($x + $w8, $y);

                    
                
                    // Número de orden
                    $stmt = $pdo->prepare("SELECT numero_orden FROM detalles_orden WHERE id_registro = ?");
                    $stmt->execute([$registro['id']]);
                    $ordenes = implode(', ', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'numero_orden'));
                    $this->Cell($w9, $h, $ordenes, 1, 0, 'C');
                
                    // Justificación técnica
                    $this->Cell($w10, $h, $registro['justificacion'], 1, 0, 'C');
                
                    // OM
                    $stmt = $pdo->prepare("SELECT om FROM detalles_orden WHERE id_registro = ?");
                    $stmt->execute([$registro['id']]);
                    $oms = implode(', ', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'om'));
                    $this->Cell($w11, $h, $oms, 1, 1, 'C'); // Nota: Cerramos la fila con un salto de línea (1, 1)
                
                
                }
            }
        }
    }

    $pdf = new PDF('L');
    $pdf->AddPage();
    $pdf->ReportBody($registros, $pdo);
    $pdf->Output();
} else {
    echo "No se recibió un ID de registro.";
}

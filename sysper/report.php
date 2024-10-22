<?php
require('fpdf.php');

class PDF extends FPDF {
    // Encabezado
    function Header() {
        // Logo CFE
        $this->Image('../imagenes/cfe_icon.png',10,8,50); // Coloca tu logo
        // Título centrado
        $this->SetFont('Arial', 'B', 8);
        // Establece el color verde CFE (RGB: 0, 153, 51)
        $this->SetTextColor(0, 153, 51);
        $this->Cell(190, 7, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'R');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial','B',8.5);
        // Título centrado
        $this->Cell(200,10,'REPORTE DE TIEMPO EXTRA',0,0,'C');
        // C.PAGO alineado a la derecha
        $this->Cell(1,10,'C.PAGO_______________',0,1,'R');
        // Salto de línea
        $this->Ln(5);
    }

    // Función para obtener la fecha del lunes más cercano
    function getClosestMonday($date) {
        $timestamp = strtotime($date);
        $dayOfWeek = date('w', $timestamp); // 0 (domingo) a 6 (sábado)

        // Si es domingo (0), retrocedemos 6 días. Si no, retrocedemos el número de días desde el lunes (1).
        if ($dayOfWeek == 0) {
            $closestMonday = strtotime('-6 days', $timestamp);
        } else {
            $closestMonday = strtotime('-' . ($dayOfWeek - 1) . ' days', $timestamp);
        }

        return date('Y-m-d', $closestMonday);
    }

    // Función para obtener una catorcena a partir de un lunes
    function getCatorcena($startDate) {
        $catorcena = [];
        for ($i = 0; $i < 14; $i++) {
            $date = strtotime("+$i day", strtotime($startDate));
            $catorcena[] = date('Y-m-d', $date);
        }
        return $catorcena;
    }

    // Cuerpo del reporte
    function ReportBody() {
        $this->SetFont('Arial','B',9);
        
        // Obtener la fecha actual
        $currentDate = date('Y-m-d'); // Fecha actual
        $closestMonday = $this->getClosestMonday($currentDate); // Obtener el lunes más cercano
        $catorcena = $this->getCatorcena($closestMonday); // Obtener la catorcena

        // Días de la semana en mayúsculas
        $daysOfWeek = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];
        $daysOfWeek = array_merge($daysOfWeek, $daysOfWeek); // Repetir los días para la catorcena

        // Información principal (nombre, categoría, etc.)
        $this->Cell(25,7,'NOMBRE:',0,0);
        $this->Cell(70,7,'__________________________',0,0);
        $this->Cell(15,7,'R.P.E:',0,0);
        $this->Cell(30,7,'__________',0,0);
        $this->Cell(15,7,'FECHA:',0,0);
        $this->Cell(30,7,'__________',0,1);

        $this->Cell(25,7,'CATEGORIA:',0,0);
        $this->Cell(70,7,'__________________________',0,0);
        $this->Cell(15,7,'SALARIO:',0,0);
        $this->Cell(30,7,'__________',0,1);
        $this->Ln(5);

        // Encabezado de la tabla
        $this->SetFont('Arial','B',6);
        $this->Cell(20,6,'FECHA',1,0,'C');
        $this->Cell(25,6,'HORA ENTRADA',1,0,'C');
        $this->Cell(25,6,'HORA SALIDA',1,0,'C');
        $this->Cell(25,6,'HORAS EXTRAS',1,0,'C');
        $this->Cell(50,6,'JUSTIFICACION',1,0,'C');
        $this->Cell(10,6,'DES',1,0,'C');
        $this->Cell(10,6,'COM',1,0,'C');
        $this->Cell(10,6,'CENA',1,0,'C');
        $this->Cell(10,6,'P.D.',1,0,'C');
        $this->Cell(10,6,'O.M.',1,1,'C');

        // Cuerpo de la tabla (14 días de la catorcena)
        for ($i = 0; $i < 14; $i++) {
            $dayName = $daysOfWeek[$i]; // Día de la semana
            $date = $catorcena[$i]; // Fecha correspondiente de la catorcena

            // Guardar la posición de inicio de la fila
            $x = $this->GetX();
            $y = $this->GetY();
            
            // Imprimir el día y la fecha en dos líneas
            $this->MultiCell(20, 6, $dayName . "\n" . date('d/m', strtotime($date)), 1, 'C');
            
            // Establecer la posición para las siguientes celdas de la fila
            $this->SetXY($x + 20, $y);

            // Imprimir el resto de las celdas en la misma fila
            $this->Cell(25, 12, '_________', 1, 0, 'C'); // Altura ajustada a 12 (6x2 líneas)
            $this->Cell(25, 12, '_________', 1, 0, 'C');
            $this->Cell(25, 12, '_________', 1, 0, 'C');
            $this->Cell(50, 12, '____________________', 1, 0, 'C');
            $this->Cell(10, 12, '', 1, 0);
            $this->Cell(10, 12, '', 1, 0);
            $this->Cell(10, 12, '', 1, 0);
            $this->Cell(10, 12, '', 1, 0);
            $this->Cell(10, 12, '', 1, 1);
        }

        // Total de horas autorizadas
        $this->SetFont('Arial','B',7);
        $this->Cell(20,7,'TOTAL HORAS:',1);
        $this->Cell(180,7,'________________________',1,1);
        $this->Ln(5);

        // Firmas y autorización
        $this->SetFont('Arial', '', 6);
        $this->MultiCell(90, 5, "ELABORO: ______________________________", 0, 'L');
        
        // Mover la posición vertical hacia abajo para el segundo renglón
        $this->SetX(25); // Ajusta la posición horizontal si es necesario
        $this->Cell(90, 5, "FIRMA DEL TRABAJADOR", 0, 0, 'L'); // Alineación centrada
        
        $this->Cell(90,5,'REVISION: ______________________________',0,0,'L');
        $this->Cell(100,5,'JEFE DEPARTAMENTO: ____________________________',0,1,'L');

        $this->Cell(90,5,'VISTO BUENO: ______________________________',0,0,'L');
        $this->Cell(100,5,'SUP. MANTTO: ____________________________',0,1,'L');

        $this->Cell(90,5,'AUTORIZO: ______________________________',0,0,'L');
        $this->Cell(100,5,'SUP. GENERAL: ____________________________',0,1,'L');
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Trabajos de emergencia entre las 20:00 y 7:00 hrs.',0,0,'R');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->ReportBody();
$pdf->Output();
?>
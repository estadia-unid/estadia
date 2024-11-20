<?php
require('fpdf.php');

class PDF extends FPDF {
    // Encabezado
    function Header() {
        $this->Image('../imagenes/svg/cfe_icon.png',10,8,50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0, 153, 51);
        $this->Cell(190, 7, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'R');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial','B',8.5);
        $this->Cell(200,10,'REPORTE DE TIEMPO EXTRA',0,0,'C');
        $this->Cell(1,10,'C.PAGO_______________',0,1,'R');
        $this->Ln(5);
    }

    // Función para obtener la fecha del lunes más cercano
    function getClosestMonday($date) {
        $timestamp = strtotime($date);
        $dayOfWeek = date('w', $timestamp); 
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
        $currentDate = date('Y-m-d'); 
        $closestMonday = $this->getClosestMonday($currentDate);
        $catorcena = $this->getCatorcena($closestMonday);

        // Días de la semana en mayúsculas
        $daysOfWeek = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];
        $daysOfWeek = array_merge($daysOfWeek, $daysOfWeek);

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
               // Encabezado de la tabla
$this->SetFont('Arial','B',6);

// Primera celda de "FECHA"
$this->Cell(20, 6, 'FECHA', 1, 0, 'C');  // Altura más grande para que coincida con las celdas MultiCell

// Posición inicial para celdas con MultiCell
$x = $this->GetX();
$y = $this->GetY();

// MultiCell para "HORA ENTRADA"
$this->MultiCell(15, 3, "HORA\nENTRADA", 1, 'C');

// Volver a la posición para la siguiente celda de "HORA SALIDA"
$this->SetXY($x + 15, $y);
$this->MultiCell(15, 3, "HORA\nSALIDA", 1, 'C');

// Volver a la posición para la siguiente celda de "HORAS EXTRA"
$this->SetXY($x + 30, $y);
$this->MultiCell(15, 3, "HORAS\nEXTRA", 1, 'C');

// Celdas restantes sin saltos de línea
$this->SetXY($x + 45, $y);  // Ajustar la posición para continuar con las celdas restantes
$this->Cell(80, 6, 'JUSTIFICACION', 1, 0, 'C');  // Altura más grande para alinearse con las celdas MultiCell
$this->Cell(10, 6, 'DES', 1, 0, 'C');
$this->Cell(10, 6, 'COM', 1, 0, 'C');
$this->Cell(10, 6, 'CENA', 1, 0, 'C');
$this->Cell(10, 6, 'P.D.', 1, 0, 'C');
$this->Cell(10, 6, 'O.M.', 1, 1, 'C');  // Final de la línea

        // Cuerpo de la tabla (14 días de la catorcena)
        for ($i = 0; $i < 14; $i++) {
            $dayName = $daysOfWeek[$i];
            $date = $catorcena[$i];
            
            // Imprimir el día y la fecha en dos líneas
            $this->Cell(20, 10, $dayName . "\n" . date('d/m/y', strtotime($date)), 1, 0, 'C');
            $this->Cell(15, 10, '_________', 1, 0, 'C');
            $this->Cell(15, 10, '_________', 1, 0, 'C');
            $this->Cell(15, 10, '_________', 1, 0, 'C');
            $this->Cell(80, 10, '____________________', 1, 0, 'C');
            $this->Cell(10, 10, '', 1, 0);
            $this->Cell(10, 10, '', 1, 0);
            $this->Cell(10, 10, '', 1, 0);
            $this->Cell(10, 10, '', 1, 0);
            $this->Cell(10, 10, '', 1, 1);
            
        }

        // Total de horas autorizadas
        $this->SetFont('Arial','B',6);
        $this->Cell(50,7,'TOTAL HORAS AUTORIZADAS',1);
        $this->Cell(15,7,'',1,1);
      
        $this->Ln(5);

        // Firmas y autorización
        $this->SetFont('Arial', '', 6);
        $this->MultiCell(90, 5, "ELABORO: ______________________________", 0, 'L');
        $this->SetX(25);
        $this->Cell(90, 5, "FIRMA DEL TRABAJADOR", 0, 0, 'L');
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

$pdf = new PDF();
$pdf->AddPage();
$pdf->ReportBody();
$pdf->Output();
?>
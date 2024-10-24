<?php
require('fpdf.php');

class PDF extends FPDF {
    // Encabezado
    function Header() {
        $this->Image('../imagenes/svg/cfe_icon.png',10,8,50);  // Añade la imagen en la parte superior izquierda
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0, 0, 0);  // Cambia el color del texto
        $this->Cell(300, 5, 'CENTRAL TERMOELECTRICA PRESIDENTE ADOLFO LOPEZ MATEOS', 0, 1, 'C');  // Título en la esquina superior derecha
        $this->Cell(300, 5, 'SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO', 0, 1, 'C'); 
        $this->Cell(300, 5, 'DEPARTAMENTO ' . $depa, 0, 1, 'C');
        $this->Ln(5);  // Salto de línea
    }

   // Esta tabla debe de ir en horizontal, pero se regresa a vertical cuando llega al No. HORAS
function ReportBody() {
    $this->SetFont('Arial', 'B', 6);
    $this->Cell(25, 6, 'CATEGORIA', 1, 0, 'C');
    $this->Cell(50, 6, 'NOMBRE DEL TRABAJADOR', 1, 0, 'C');  
    $this->Cell(20, 6, 'RPE', 1, 0, 'C');
    $this->Cell(20, 6, 'FECHA', 1, 0, 'C');
    $this->Cell(15, 6, 'INICIO', 1, 0, 'C'); // Se debe de enlazar con el reloj checador en la fase II
    $this->Cell(15, 6, 'TERMINO', 1, 0, 'C'); // Se debe de enlazar con el reloj checador en la fase II
   // Posición inicial para celdas con MultiCell
    $x = $this->GetX();
    $y = $this->GetY();
    $this->MultiCell(15, 3, "No.\nHORAS", 1, 'C');
    // Ajustar la posición después de MultiCell
    $this->SetXY($x + 15, $y); 
    $this->Cell(40, 6, 'ACTIVIDADES REALIZADAS', 1, 0, 'C');
      // Posición inicial para celdas con MultiCell
      $x = $this->GetX();
      $y = $this->GetY();
      $this->MultiCell(20, 3, "No.\nORDEN", 1, 'C');
      // Ajustar la posición después de MultiCell
      $this->SetXY($x + 20, $y); 
    $this->Cell(50, 6, 'JUSTIFICACION TECNICA', 1, 0, 'C');
    $this->Cell(15, 6, 'O.M.', 1, 1, 'C'); // Salto de línea después de la última celda
    }
}

// Crea una instancia de la clase PDF y añade una página
$pdf = new PDF('L');  // 'L' indica orientación landscape (horizontal)
$pdf->AddPage();  // Añade una página
$pdf->ReportBody();  // Llama al método para generar el cuerpo del reporte
$pdf->Output();  // Muestra o descarga el PDF
?>
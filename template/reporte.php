<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Título
        $this->Cell(0, 10, 'Tabla de Ejemplo', 0, 1, 'C');
        // Salto de línea
        $this->Ln(10);
    }

    function Footer() {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function Tabla($header) {
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(200, 220, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(50, 60, 100);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        // Cabecera
        foreach ($header as $col) {
            $this->Cell(18, 10, $col, 1, 0, 'C', true);
        }
        $this->Ln();

        // Restaurar colores y fuentes
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Datos
        for ($i = 0; $i < 15; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $this->Cell(18, 10, "Fila $i Col $j", 1, 0, 'C', false);
            }
            $this->Ln();
        }
    }
}

// Crear instancia de la clase PDF
$pdf = new PDF();
$pdf->AddPage();
$header = array('FECHA', 'HORA ENTRADA', 'HORA SALIDA', 'HORAS EXTRA', 'JUSTIFICACION', 'DES', 'COM', 'CENA', 'P.D.', 'O.M.');
$pdf->Tabla($header);
$pdf->Output();
?>
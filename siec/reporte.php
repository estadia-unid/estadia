<?php
require("../fpdf186/fpdf.php");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Hola, Mundo!');
$this->Image('../imagenes/empleados/9AVB3.jpg',10,10,-300);
$pdf->Output();
?>

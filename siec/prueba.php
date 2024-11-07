<?php
require("fpdf186/fpdf.php");
include_once "conexion.php";

class PDF extends FPDF
{

}

$pdf = new FPDF(); 
$pdf->addPage("P", "A4");
$pdf->GetPageWidth();
print  $pdf->GetPageWidth(); // Width of Current Page
$pdf->GetPageHeight();
print  '    '. $pdf->GetPageHeight(); // Height of Current Page
$pdf->Output();
//https://www.php.net/manual/es/function.count.php
?>

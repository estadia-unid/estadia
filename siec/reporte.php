<?php
require("../fpdf186/fpdf.php");
   date_default_timezone_set('America/Mexico_City');
     $conecta =  mysqli_connect('localhost', 'root', 'ctpalm2113', 'estadiaunid');
     if(!$conecta){
         die('no pudo conectarse:' . mysqli_connect_error());
      }
   if (!mysqli_set_charset($conecta,'utf8')) {
    die('No pudo conectarse: ' . mysqli_error($conecta));
    }
class PDF extends FPDF
{
function Header()
{
    // Logo
    $this->Image('../imagenes/svg/cfelogo.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(30,10,'Reporte computadoras',1,0,'C');
    // Salto de línea
    $this->Ln(20);
}
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,);
$consulta = mysqli_query($conecta, "SELECT * FROM `computadoras`");
while ($dato=mysqli_fetch_array($consulta)) {
    $pdf->Cell(0,10,$dato[5],0,1);
    $pdf->Cell(0,10,$dato[11],0,1);
}
    $pdf->Output();
?>

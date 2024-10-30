<?php
require("fpdf186/fpdf.php");
date_default_timezone_set('America/Mexico_City');
$conecta =  mysqli_connect('localhost', 'siec', 'ctpalm2113', 'siec');
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
    $this->Image('imagenes/svg/cfe_icon.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(30,10,'Reporte computadoras',1,0,'C');
    // Salto de línea
    $this->Ln(20);
}
function BasicTable($conecta){
    $header = array('hola')
    $this->SetFillColor(255,0,0);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    $w = array(40, 35, 45, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    $consulta = mysqli_query($conecta, "SELECT * FROM `computadoras`");
    while ($dato=mysqli_fetch_array($consulta)) {
        $this->Cell(40,6,$dato[5],1);
        $this->Ln();
        $this->Cell(40,6,$dato[3],1);
        $this->Ln();
    }
}
}
$pdf = new PDF();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($conecta);
$pdf->AddPage();
$pdf->Output();
?>

<?php
include_once("../fpdf186/fpdf.php");
   date_default_timezone_set('America/Mexico_City');
     $conecta =  mysqli_connect('localhost', 'root', 'ctpalm2113', 'estadiaunid');
     if(!$conecta){
         die('no pudo conectarse:' . mysqli_connect_error());
      }
   if (!mysqli_set_charset($conecta,'utf8')) {
    die('No pudo conectarse: ' . mysqli_error($conecta));
    }
    $consulta = mysqli_query($conecta,"SELECT * FROM `computadoras`");
        while ($exp=mysqli_fetch_array($consulta)) {
            $GLOBALS['id']=$exp['Departamento'];
            $GLOBALS['fechareg']=$exp['Puesto'];
            $GLOBALS['horareg']=$exp['Usuario responsable'];
            $GLOBALS['nombreusuario']=$exp['RPE'];
            }
        mysqli_close($conecta);

class PDF_MC_Table extends FPDF
{
function Footer(){
        $this->SetFont('Arial','B',11);
		$this->SetXY(88,240);
		$this->Cell(40,5,utf8_decode('A T E N T A M E N T E'),0,0,'C');
		$this->SetXY(78,258);
		$this->Cell(60,5,utf8_decode('Ruben Dario Chavarria Ramos'),'T',0,'C');
		$this->SetXY(78,262);
		$this->Cell(60,5,utf8_decode('Contralor del H. Ayuntamiento de Tuxpan'),0,0,'C');
		$this->SetXY(18,246);
		$this->Cell(25,4,utf8_decode($GLOBALS['fechareg']),0,0,'L');
	    }
 function Header(){
         	
		$this->SetXY(82,15);
		$this->Cell(40,5,utf8_decode('Informe del Folio:' ),0,0,'C');
		$this->SetXY(82,22);
		$this->Cell(40,5,utf8_decode($GLOBALS['folio']),0,0,'C');
		//maximo x 210
		$this->Line(20,50,190,50);
		
		$this->SetFont('Arial','B',14);
		$this->SetXY(160,37);
		$this->Cell(30,5,$GLOBALS['clave'],0,0,'L');
		$this->SetFont('Arial','B',14);
		$this->SetXY(72,37);
		$this->Cell(70,5,utf8_decode('Sistema de Denuncias Ciudadanas'),0,0,'C');
		$this->Ln(22);
	
    }	
}

$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B', 12);

//De aqui en adelante se colocan distintos métodos
//para diseñar el formato.
$pdf->SetFont('Arial','B', 12);
//fecha de registro
$pdf->SetXY(50,60);
$pdf->Cell(120,0,'fecha de registro:',0,0,'R');
$pdf->SetXY(50,60);
$pdf->Cell(145,0,$GLOBALS['fechareg'],0,0,'R');
//hora de registro
$pdf->SetXY(50,60);
$pdf->Cell(120,10,'hora de registro:',0,0,'R');
$pdf->SetXY(50,60);
$pdf->Cell(140,10,$GLOBALS['horareg'],0,0,'R');
//
$pdf->SetXY(50,60);
$pdf->Cell(10,5,'Denunciante:',0,0,'R');
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(59,60);
$pdf->Cell(30,5,utf8_decode($GLOBALS['denunciante']),0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->SetXY(50,67);
$pdf->Cell(10,5,utf8_decode('Teléfono:'),0,0,'R');
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(59,67);
$pdf->Cell(30,5,utf8_decode($GLOBALS['telefono']),0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->SetXY(50,74);
$pdf->Cell(10,5,'Correo:',0,0,'R');
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(59,74);
$pdf->Cell(40,5,utf8_decode($GLOBALS['mail']),0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->SetXY(50,80);
$pdf->Cell(10,5,utf8_decode('Denuncia:'),0,0,'R');
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(59, 80);
$pdf->MultiCell(135,5,utf8_decode($GLOBALS['denuncia']), 0, 'J');

$pdf->SetFont('Arial','B', 12);
$pdf->SetXY(50,170);
$pdf->Cell(10,5,utf8_decode('Registro:'),0,0,'R');
$pdf->SetFont('Arial','B', 10);
$pdf->SetXY(60,170);
$pdf->Cell(100,5,utf8_decode($GLOBALS['fechor']),0,0,'L');


$pdf->Output(); //Salida al navegador
 
?>
<?php
session_start();
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

$pdf->Output(); //Salida al navegador
 
?>
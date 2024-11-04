<?php
require("fpdf186/fpdf.php");
include_once "conexion.php";

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('imagenes/svg/cfelogo.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'SUBDIRECCION DE GENERACION', 0, 1, 'C');
        $this->Cell(0, 10, 'Sistema Integral de Gestion', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'INFORME DE PC POR AREA', 0, 1, 'C');
        $this->Ln(10);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    public function TableHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(192, 192, 192);
        $this->Cell(30, 7, 'Marca', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Modelo', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Serie', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Procesador', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Velocidad', 1, 0, 'C', true);
        $this->Cell(20, 7, 'RAM', 1, 0, 'C', true);
        $this->Cell(30, 7, 'IP del Equipo', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Observaciones', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Activo Fijo', 1, 0, 'C', true);
        $this->Cell(30, 7, 'Inventario', 1, 1, 'C', true);
    }

    function LoadData($conecta)
    {
        $leer = "SELECT * FROM `computadoras` INNER JOIN `empleados` ON computadoras.rpe = empleados.rpe ORDER BY `departamento`";
        $resultado_leer = $conecta->prepare($leer);
        $resultado_leer->execute([]);
        return $resultado_leer;
        
    }
    public function tabluwu($datos){
        $this->Cell(30, 6, $datos['departamento'], 1);
        $this->Cell(30, 6, $datos['numero_de_serie'], 1);
        $this->Cell(30, 6, $datos['modelo'], 1);
        $this->Cell(30, 6, $datos['a_materno'], 1);
        $this->Cell(30, 6, $datos['rpe'], 1);
        $this->Cell(20, 6, $datos['memoria'], 1);
        $this->Cell(30, 6, $datos['ip'], 1);
        $this->Cell(30, 6, $datos['rpe'], 1);
        $this->Cell(30, 6, $datos['activo_fijo'], 1);
        $this->Cell(30, 6, $datos['inventario'], 1);
        $this->Ln();
    }
    function TableBody($data)
    {
        $this->SetFont('Arial', '', 9);
            while($datos = $data->fetch(PDO::FETCH_ASSOC)){
                $this->Cell(30, 6, $datos['departamento'], 1);
                $this->Cell(30, 6, $datos['numero_de_serie'], 1);
                $this->Cell(30, 6, $datos['modelo'], 1);
                $this->Cell(30, 6, $datos['a_materno'], 1);
                $this->Cell(30, 6, $datos['rpe'], 1);
                $this->Cell(20, 6, $datos['memoria'], 1);
                $this->Cell(30, 6, $datos['ip'], 1);
                $this->Cell(30, 6, $datos['rpe'], 1);
                $this->Cell(30, 6, $datos['activo_fijo'], 1);
                $this->Cell(30, 6, $datos['inventario'], 1);
                $this->Ln();
                }
        }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->TableHeader();
$data = $pdf->LoadData($conecta);
$pdf->TableBody($data);
$pdf->Output();
?>

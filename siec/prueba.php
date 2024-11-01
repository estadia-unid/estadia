<?php
require("fpdf186/fpdf.php");

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
    function TableHeader()
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

    function LoadData()
    {
        return [
            ['HP COMPAQ', 'DC580 SFF', 'G094AC', 'AMD Phenom', '2.3 GHz', '2 GB', '10.41.24.70', 'WIN7 A 64', '45001189', 'C-00594479'],
            ['HP COMPAQ', 'D530 SFF', 'MJX434043F', 'Core i3', '2.5 GHz', '4 GB', '10.41.24.71', 'WIN7 ETNA MONTALVO', '43000191', 'C-00353930'],
        ];
    }
    function TableBody($data)
    {
        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            $this->Cell(30, 6, $row[0], 1);
            $this->Cell(30, 6, $row[1], 1);
            $this->Cell(30, 6, $row[2], 1);
            $this->Cell(30, 6, $row[3], 1);
            $this->Cell(30, 6, $row[4], 1);
            $this->Cell(20, 6, $row[5], 1);
            $this->Cell(30, 6, $row[6], 1);
            $this->Cell(30, 6, $row[7], 1);
            $this->Cell(30, 6, $row[8], 1);
            $this->Cell(30, 6, $row[9], 1);
            $this->Ln();
        }
    }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->TableHeader();
$data = $pdf->LoadData();
$pdf->TableBody($data);
$pdf->Output();
?>

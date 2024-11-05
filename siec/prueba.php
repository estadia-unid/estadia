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
        $DateAndTime = date('d-m-Y', time());
        $this->Cell(0, 15, 'Fecha ' . $DateAndTime, 0, 0, 'R');
    }

    function LoadData($conecta)
    {
        $leer = "SELECT * FROM `computadoras` 
                 INNER JOIN `empleados` ON computadoras.rpe = empleados.rpe 
                 ORDER BY `departamento`, empleados.rpe";
        $resultado_leer = $conecta->prepare($leer);
        $resultado_leer->execute([]);
        return $resultado_leer->fetchAll(PDO::FETCH_ASSOC);
    }

    function TableHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(253, 60, 60);
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

    function TableBody($data)
    {
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(253, 253, 60);
        $currentDepartment = '';
        $currentEmployee = '';

        foreach ($data as $row) {
            // Verificar si el departamento cambió
            if ($row['departamento'] != $currentDepartment) {
                $currentDepartment = $row['departamento'];
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(0, 10, 'Departamento: ' . $currentDepartment, 1, 1, 'L', 1);
                $this->Ln(2);
                $this->SetFont('Arial', '', 9);
            }

            // Verificar si el empleado cambió
            if ($row['rpe'] != $currentEmployee) {
                $currentEmployee = $row['rpe'];
                // Imprimir información del empleado una sola vez
                $this->SetFont('Arial', 'I', 9);
                $this->Cell(0, 6, 'Empleado: ' . utf8_decode($row['nombre']) . ' ' . utf8_decode($row['a_paterno']) . ' ' . utf8_decode($row['a_materno']) . ' - RPE: ' . $row['rpe'] . '    ' . 'Puesto: ' . utf8_decode($row['categ']), 0, 1, 'L');
                $this->Ln(1);
                $this->SetFont('Arial', '', 9);
            }

            // Imprimir información de la computadora
            $this->Cell(30, 6, $row['marca'], 1,0,'L',0);
            $this->Cell(30, 6, $row['modelo'], 1);
            $this->Cell(30, 6, $row['numero_de_serie'], 1);
            $this->Cell(30, 6, $row['rpe'], 1);
            $this->Cell(30, 6, $row['nombre'], 1);
            $this->Cell(20, 6, $row['rpe'], 1);
            $this->Cell(30, 6, $row['ip'], 1);
            $this->Cell(30, 6, $row['observaciones'], 1);
            $this->Cell(30, 6, $row['activo_fijo'], 1);
            $this->Cell(30, 6, $row['inventario'], 1);
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

<?php
require("fpdf186/fpdf.php");
include_once "conexion.php";

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('imagenes/svg/cfelogo.png', 10, 10, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0,142,90);
        $this->Cell(0, 8, 'SUBDIRECCION DE GENERACION', 0, 1, 'C');
        $this->Cell(0, 5, 'Sistema de Inventario de Equipos de Computo', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 5, 'INFORME DE EMPLEADO', 0, 1, 'C');
        $this->Ln(5);
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
        $leer = "SELECT * FROM `computadoras` INNER JOIN `empleados` ON computadoras.rpe = empleados.rpe ORDER BY `departamento`, empleados.rpe";
        $resultado_leer = $conecta->prepare($leer);
        $resultado_leer->execute([]);
        return $resultado_leer->fetchAll(PDO::FETCH_ASSOC);
    }

    function TableHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(0,142,90);
        $this->Cell(20, 7, 'Marca', 1, 0, 'C', true);
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

    function TableBody($datos)
    {
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(253, 253, 60);
        //$departamenteActual = '';
        $empleadoActual = '';
        $total = 0;
        foreach ($datos as $row) {
           /*
            if ($row['departamento'] != $departamenteActual) {
                if ($departamenteActual != '') {
                    $this->SetFont('Arial', 'B', 10);
                    $this->Cell(40, 10, 'Total ' . $total, 0, 1, 'L', 0);
                    $this->Ln(2);
                }
                $departamenteActual = $row['departamento'];
                
                $this->Cell(0, 10, 'Departamento: ' . $departamenteActual, 1, 1, 'L', 1);
                $this->Ln(2);
                $this->SetFont('Arial', '', 9);
            }
            */
            if ($row['rpe'] != $empleadoActual) {
                $this->SetFont('Arial', 'B', 10);
                if($empleadoActual != ''){
                    $this->Cell(0, 10, 'Total ' . $total, 0, 1, 'L', 0);
                }
                $empleadoActual = $row['rpe'];
                $this->Cell(0, 6, 'Empleado: ' . utf8_decode($row['nombre']) . ' ' . utf8_decode($row['a_paterno']) . ' ' . utf8_decode($row['a_materno']) . ' - RPE: ' . $row['rpe'], 1, 1, 'L',1);
                $this->Cell(30, 10, 'Puesto', 1,0,'L',0);
                $this->Cell(60, 10, $row['categ'], 1,1,'L',0);
                $this->Cell(30, 10, 'Departamento', 1,0,'L',0);
                $this->Cell(60, 10, $row['departamento'], 1,1,'L',0);
                $this->Cell(30, 10, 'Telefono', 1,0,'L',0);
                $this->Cell(60, 10, $row['numero_de_serie'], 1,1,'L',0);
                $this->Cell(30, 10, 'Correo', 1,0,'L',0);
                $this->Cell(60, 10, $row['correo'], 1,1,'L',0);
                $this->Cell(30, 10, 'Extencion', 1,0,'L',0);
                $this->Cell(60, 10, $row['rpe'], 1,1,'L',0);
                $this->Image('imagenes/svg/cfelogo.png', 200, 290, 30, 30);
                $total = 0;
                $this->Ln(1);
                $this->SetFont('Arial', '', 9);
            }
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
            $total++;
            // https://www.php.net/manual/es/function.end.php
            if($row == end($datos)){
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(40, 10, 'Total ' . $total, 0, 1, 'L', 0);
                $this->Ln(2);
            }
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->TableHeader();
$datos = $pdf->LoadData($conecta);
$pdf->TableBody($datos);
$pdf->Output();
//https://www.php.net/manual/es/function.count.php
?>

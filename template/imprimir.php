<?php
require_once("seguridad.php");
require_once("fpdf.php");
date_default_timezone_set('America/Mexico_City');

// Conexión a la base de datos
$conecta = mysqli_connect('localhost', 'unidsyst_jozet', 'unidsyst_jozet', 'unidsyst_cjimago');
if (!mysqli_set_charset($conecta, 'utf8')) {
    die('No se pudo conectar: ' . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM cibermedios WHERE id='$id'";
    $result = mysqli_query($conecta, $sql);

    if ($row = mysqli_fetch_array($result)) {
        $fecha = $row['fecha'];
        $hora = $row['hora'];
        $user = $row['user'];
        $cibermedio = $row['cibermedio'];
        $gpo_edit = $row['gpo_edit'];
        $url = $row['url'];
        $pais = $row['pais'];
        $idioma = $row['idioma'];
        $categoria = $row['categorizacion'];

        class PDF_MC_Table extends FPDF
        {
            function Header()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 10, utf8_decode('Reporte de producto'), 0, 1, 'C');
                $this->Image('logo.png', 10, 10, 25);
                $this->Ln(15);
            }

            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }

        $pdf = new PDF_MC_Table();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetMargins(10, 10, 10);

        // Título y ID del reporte
        $pdf->Cell(0, 8, utf8_decode('ID del reporte: ') . $id, 0, 1, 'L');
        $pdf->Ln(5);

        // Introducción
        $pdf->MultiCell(0, 8, utf8_decode("Estimado(a) " . $user . ",\n\nEl presente informe ha sido generado automáticamente con base en la solicitud del reporte con el ID mencionado anteriormente. A continuación, se detalla la información relevante del producto solicitado:"), 0, 'J');
        $pdf->Ln(5);

        // Detalles del cibermedio
        $pdf->Cell(30, 8, utf8_decode('Producto:'), 0, 0, 'L');
        $pdf->Cell(0, 8, utf8_decode($cibermedio), 0, 1, 'L');

        $pdf->Cell(30, 8, utf8_decode('Stock:'), 0, 0, 'L');
        $pdf->Cell(0, 8, utf8_decode($idioma), 0, 1, 'L');

        $pdf->Cell(30, 8, utf8_decode('Categoría:'), 0, 0, 'L');
        $pdf->Cell(0, 8, utf8_decode($categoria), 0, 1, 'L');

        $pdf->Cell(30, 8, utf8_decode('Enlace:'), 0, 0, 'L');
        $pdf->Cell(0, 8, utf8_decode($url), 0, 1, 'L');
        $pdf->Ln(8);

        // Cierre
        $pdf->MultiCell(0, 8, utf8_decode("Este reporte ha sido elaborado gracias a la colaboración con la empresa CYBERMED, dedicada a proporcionar información actualizada y precisa. Si requiere información adicional, puede visitar el sitio web del producto original a través del siguiente enlace:\n" . $url), 0, 'J');
        $pdf->Ln(8);
        $pdf->MultiCell(0, 8, utf8_decode("Quedamos a su disposición para cualquier consulta adicional.\n\nAtentamente,\n\nJozet Ramírez\nPresidente a cargo de CYBERMED"), 0, 'J');
        $pdf->Ln(8);

        // Despedida
        $pdf->Cell(0, 8, utf8_decode('Le deseamos un excelente día y esperamos poder asistirle nuevamente en el futuro.'), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->Cell(0, 8, utf8_decode('CYBERMED - Innovación y Precisión'), 0, 1, 'C');

        $pdf->Output();
    } else {
        echo utf8_decode("No se encontró ningún producto con el ID proporcionado. Regresa a <a href='index.php'>Inicio</a> para buscar otro ID.");
    }
} else {
    echo utf8_decode("No se proporcionó ningún ID. Regresa a <a href='index.php'>Inicio</a> para buscar el ID.");
}
?>

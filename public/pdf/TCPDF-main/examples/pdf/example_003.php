<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte Por unidad ');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' de unidad cuatrimestral ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Agregar una página
$pdf->AddPage();

// Obtener los datos de tutulos, subtitulos y fechas 
$tituloReporte = "Reporte de unidad ";
$fechaReporte = "Enero - Agosto 2023"; 

// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);

// Imprimir el título del reporte
$pdf->Cell(0, 10, $tituloReporte, 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir la fecha del reporte
$pdf->Cell(0, 10, "Periodo de registro: $fechaReporte", 0, 1, 'C');
$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, 'Datos solicitante', 0, 1, 'A');

// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 10);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(40, 5, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Tipo_Perfil', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'ID del Empleado', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Fecha', 1, 1, 'C', 1);

// Datos del empleado (simulados)
$nombreEmpleado = "Juan Pérez";
$posicionEmpleado = "Encargado_Taller";
$idEmpleado = "12345";
$fechaEmpleado = "2023-09-21";

$pdf->Cell(40, 5, $nombreEmpleado, 1);
$pdf->Cell(40, 5, $posicionEmpleado, 1);
$pdf->Cell(40, 5, $idEmpleado, 1);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);


$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->Cell(0, 10, "Datos unidad", 0, 1, 'A');

// Crear la tabla de registros
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(25, 5, 'Matricula', 1, 0, 'C', 1);
$pdf->Cell(25, 5, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(20, 5, 'Año', 1, 0, 'C', 1);
$pdf->Cell(30, 5, 'Marca', 1, 0, 'C', 1);
$pdf->Cell(20, 5, 'Estado', 1, 1, 'C', 1);

$datosUnidad = [
    ["Mk-230-TY", "Autobus","2010","Ford","Activo"],
];
foreach ($datosUnidad as $datos) {
    $pdf->Cell(25, 5, $datos[0], 1);
    $pdf->Cell(25, 5, $datos[1], 1);
    $pdf->Cell(20, 5, $datos[2], 1);
    $pdf->Cell(30, 5, $datos[3], 1);
    $pdf->Cell(20, 5, $datos[4], 1, 1); 
	    
}
$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, "Registro mantenimientos", 0, 1, 'A');

// Crear la tabla 
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Kilometraje ', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Encargado', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Notas', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Descripcion', 1, 1, 'C', 1);

$datosEncargado = [
//
    ["2023-01-21", "155000","Javier Chavez","Preventivo","Cambio de aceite"],
	["2023-02-15", "156000","Emilio Vega","Preventivo","Liquido de frenos"],
	["2023-03-16", "158234","Javier Cavez","Correctivo","Cambio de faros"],
	["2023-04-26", "173845","Salvador Quintana","Correctivo","Cambio de llantas"],
	["2023-05-12", "203845","Saul Duran","Prventivo","Cambio de aceite"],
	["2023-07-25", "219384","Raul Velazquez","Preventivo","Limpieza de motor"],
	["2023-08-1", "239489","Domingo Lopez","Correctivo","Cambio de calavera"]

];
foreach ($datosEncargado as $datosE) {
    $pdf->Cell(20, 7, $datosE[0], 1);
    $pdf->Cell(20, 7, $datosE[1], 1);
    $pdf->Cell(40, 7, $datosE[2], 1);
    $pdf->Cell(20, 7, $datosE[3], 1);
    $pdf->Cell(50, 7, $datosE[4], 1, 1); 	    
}

// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');

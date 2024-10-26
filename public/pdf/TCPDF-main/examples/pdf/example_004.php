<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte por encargado ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' de solicitud por encargado ', PDF_HEADER_STRING);

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
$tituloReporte = "Reporte de tickets por encargado ";
$fechaReporte = "Enero - Diciembre 2023"; 
$subtitulo_empleado = "Datos Solicitante";

// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, $tituloReporte, 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
// Imprimir la fecha del reporte
$pdf->Cell(0, 10, "Periodo de registro: $fechaReporte", 0, 1, 'C');

$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, $subtitulo_empleado, 0, 1, 'A');
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
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Datos encargado', 0, 1, 'A');

// Crear la tabla de encargados
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'ID_Usuario', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Rol', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Solicitudes', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Aprobadas', 1, 1, 'C', 1);

$datosEncargado = [

    ["2342", "Raul","Supervisor","4","2"]

];
foreach ($datosEncargado as $datosE) {
    $pdf->Cell(20, 7, $datosE[0], 1);
    $pdf->Cell(20, 7, $datosE[1], 1);
    $pdf->Cell(20, 7, $datosE[2], 1);
    $pdf->Cell(20, 7, $datosE[3], 1);
    $pdf->Cell(20, 7, $datosE[4], 1, 1); 
	    
}

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Solicitudes', 0, 1, 'A');

// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_Solicitud', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Estatus', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'Descripcion', 1, 1, 'C', 1);

$datosSolicitud = [

    ["2034", "2023-01-04", "Aprobada", "UNJ-202-G", "Cambio de bujias"],
	["2945", "2023-01-04", "Rechazada", "DTH-364-D", "Cambio de bateria"],
	["0956", "2023-01-04", "Aprobada", "RJG-345-F", "Alineacion"],
	["2096", "2023-01-04", "Rechazada", "UDF-346-H", "Ajuste de frenos"],
	["2098", "2023-01-04", "En espera", "DHT-364-A", "Cambio de llanatas"],
	["2089", "2023-01-04", "Rechazada", "DSD-765-R", "Ajuste de amortiguadores"],
	["2990", "2023-01-04", "Aprobada", "HSD-745-S", "Cambio de faros"],
	["1001", "2023-01-04", "Aprobada", "SDD-845-W", "Ajuste de motor"],
	["2101", "2023-01-04", "Aprobada", "SDD-457-R", "Ajuste de frenos"],
	["2103", "2023-01-04", "Aprobada", "SDR-564-U", "Cambio de parabrisas"]
];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosSolicitud as $soli) {
    $pdf->Cell(20, 7, $soli[0], 1);
    $pdf->Cell(25, 7, $soli[1], 1);
    $pdf->Cell(25, 7, $soli[2], 1);
    $pdf->Cell(25, 7, $soli[3], 1);
	$pdf->Cell(60, 7, $soli[4], 1, 1);
	  
}

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Salidas', 0, 1, 'A');

// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_Salida', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'Refaccion', 1, 1, 'C', 1);

$datosRefaccion = [

    ["2034", "2023-01-04", "3", "UNJ-202-G", "bujias"],
	["2945", "2023-03-03", "3", "DTH-364-D", "bateria"],
	["2096", "2023-03-03", "5", "UDF-346-H", "frenos"],
	["2098", "2023-02-08", "4", "DHT-364-A", "llantas"],
	["2089", "2023-04-05", "3", "DSD-765-R", "amortiguadores"]
];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosRefaccion as $refaccion) {
    $pdf->Cell(20, 7, $refaccion[0], 1);
    $pdf->Cell(25, 7, $refaccion[1], 1);
    $pdf->Cell(25, 7, $refaccion[2], 1);
    $pdf->Cell(25, 7, $refaccion[3], 1);
	$pdf->Cell(60, 7, $refaccion[4], 1, 1);
	  
}


// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');

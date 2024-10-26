<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte general ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' General de gastos anual ', PDF_HEADER_STRING);

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
$tituloReporte = "Reporte Mensual de gastos por unidad ";
$fechaReporte = "Septiembre 2023"; 
$subtitulo_empleado = "Datos Solicitante";
$subtitulo_unidad = "Datos unidad";
$Subtitulo_registros = "Registro de compra";

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
$pdf->SetFont('helvetica', 'A', 11);

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

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, $subtitulo_unidad, 0, 1, 'A');
$pdf->SetFont('helvetica', 'A', 11);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(30, 5, 'Matricula', 1, 0, 'C', 1);
$pdf->Cell(25, 5, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(25, 5, 'Estado', 1, 0, 'C', 1);
$pdf->Cell(25, 5, 'Año', 1, 0, 'C', 1);
$pdf->Cell(25, 5, 'Marca', 1, 1, 'C', 1);

// Datos del empleado (simulados)
$id_unidad = "MK-4093-98";
$tipo_unidad = "Autobus";
$estado = "Activo";
$año = "2002";
$marca = "mercedes";

$pdf->Cell(30, 7, $id_unidad, 1);
$pdf->Cell(25, 7, $tipo_unidad, 1);
$pdf->Cell(25, 7, $estado, 1);
$pdf->Cell(25, 7, $año, 1, 0, 'C');
$pdf->Cell(25, 7, $marca, 1, 1, 'C');


$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo 
$pdf->Cell(0, 10, $Subtitulo_registros, 0, 1, 'A');
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(15, 10, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(55, 10, 'Descripción', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Factura', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Monto', 1, 1, 'C', 1);

$datosGastos = [

    ["2023-09-01", "Discos","5","Discos de frenado","93021F", 250.00],
    ["2023-09-05", "Faros", "2","Faro izquierdo","23535G", 100.00],
    ["2023-09-10", "Alineacion", "1","Mantenimiento","34543T", 800.00],
    // ... Agrega más filas de gastos según sea necesario
];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosGastos as $gasto) {
    $pdf->Cell(30, 10, $gasto[0], 1);
    $pdf->Cell(30, 10, $gasto[1], 1);
    $pdf->Cell(15, 10, $gasto[2], 1);
    $pdf->Cell(55, 10, $gasto[3], 1);
    $pdf->Cell(30, 10, $gasto[4], 1);    
    $pdf->Cell(30, 10, '$' . number_format($gasto[5], 2), 1, 1, 'R');
}

// Calcular el total de gastos
$totalGastos = array_sum(array_column($datosGastos, 5));

// Imprimir el total de gastos
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(160, 10, 'Total de Gastos:', 1);
$pdf->Cell(30, 10, '$' . number_format($totalGastos, 2), 1, 1, 'R');

// Agregar gráficos, comentarios, conclusiones, recomendaciones, etc., según sea necesario


// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');

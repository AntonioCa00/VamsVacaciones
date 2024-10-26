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
$tituloReporte = "Reporte general de gastos anual ";
$fechaReporte = "Enero - Diciembre 2023"; 
$subtitulo_empleado = "Datos Solicitante";
$subtitulo_unidad = "Datos unidades";
$Subtitulo_registros = "Registro de compras";
$Subtitulo_Encargado = "Lista de encargados";
$Subtitulo_refacciones = "Refacciones en stock";

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

// Imprimir el subtutitulo 
$pdf->Cell(0, 10, $Subtitulo_registros, 0, 1, 'A');
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(18, 7, 'Id Compra', 1, 0, 'C', 1);
$pdf->Cell(22, 7, 'Administrador', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Descripción', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Refaccion', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Factura', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Costo', 1, 1, 'C', 1);
$datosGastos = [

    ["2345","Gonzalo","2023-05-01", "Mk-230-TY","Rectificacion de discos ","Discos","9","01234J",2500.00],
    ["3459","Erick","2023-03-05", "SD-455-TI","Cambio de faros","Faros","2","01234J", 1000.00],
    ["4557","Raul","2023-06-10", "DF-474-FG", " Alineacion y balanceo","Alineacion","3","01234J", 8000.00],
    ["4576","Saul","2023-03-03", "FG-455-FJ", "Ajuste de bujias ",  "Bujías","1", "01234J", 300.00],
	["5684","Fernando","2023-04-07", "ER-789-CH", "Cambo de fltro", "Filtro de Aire","2", "01234J", 150.00],
	["5686","Eduardo","2023-08-12", "CV-878-GD", "cambio de bateria", " Batería","3", "01234J", 800.00],
	["4578","Fermin","2023-04-18", "XG-877-SF","Cambio de aceite","Aceite de Motor", "4","01234J", 250.00]

];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosGastos as $gasto) {
    $pdf->Cell(18, 7, $gasto[0], 1);
    $pdf->Cell(22, 7, $gasto[1], 1);
    $pdf->Cell(20, 7, $gasto[2], 1);
    $pdf->Cell(20, 7, $gasto[3], 1);
    $pdf->Cell(35, 7, $gasto[4], 1); 
	$pdf->Cell(25, 7, $gasto[5], 1);
	$pdf->Cell(15, 7, $gasto[6], 1);
	$pdf->Cell(15, 7, $gasto[7], 1);     
    $pdf->Cell(20, 7, '$' . number_format($gasto[8], 2), 1, 1, 'R');
}

// Calcular el total de gastos
$totalGastos = array_sum(array_column($datosGastos, 8));

// Imprimir el total de gastos
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(165, 7, 'Total de Gastos:', 1);
$pdf->Cell(25, 7, '$' . number_format($totalGastos, 2), 1, 1, 'R');

// Agregar gráficos, comentarios, conclusiones, recomendaciones, etc., según sea necesario

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->Cell(0, 10, $subtitulo_unidad, 0, 1, 'A');

// Crear la tabla de gastos

$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(25, 7, 'Matricula', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Año', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'Marca', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Estado', 1, 1, 'C', 1);

$datosUnidades = [

    ["Mk-230-TY", "Autobus","2010","Ford","Activo"],
	["DH-364-TY", "Van","2012","volkswagen","Activo"],
	["GD-346-TY", "Particular","2020","Nissan","Inactivo"],
	["FD-745-TY", "Particular","2016","volkswagen","Activo"],
	["HT-785-TY", "Van","2017","Ford","Servicio"],
	["JT-689-TY", "Autobus","2013","volkswagen","Activo"],
	["NT-679-TY", "Autobus","2016","Ford","Inactivo"]

];
foreach ($datosUnidades as $datos) {
    $pdf->Cell(25, 7, $datos[0], 1);
    $pdf->Cell(25, 7, $datos[1], 1);
    $pdf->Cell(20, 7, $datos[2], 1);
    $pdf->Cell(30, 7, $datos[3], 1);
    $pdf->Cell(20, 7, $datos[4], 1, 1); 
	    
}
$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, $Subtitulo_Encargado, 0, 1, 'A');

// Crear la tabla de encargados
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'ID_Usuario', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Rol', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Solicitudes', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Aprobadas', 1, 1, 'C', 1);

$datosEncargado = [

    ["2342", "Raul","Supervisor","4","2"],
	["4644", "Rafael","Encargado","3","1"],
	["3346", "Carlos","Mecanico","10","5"],
	["7345", "Fatima","Ayudante","12","7"],
	["3785", "Raquel","Encargado","14","8"],
	["6289", "Ernesto","Encargado","11","8"],
	["6379", "Saul","Mecanico","8","7"]

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
$pdf->Cell(0, 10, $Subtitulo_refacciones, 0, 1, 'A');
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_refaccion', 1, 0, 'C', 1);
$pdf->Cell(38, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Modelo', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Año', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Marca', 1, 0, 'C', 1);
$pdf->Cell(10, 7, 'Motor', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'Descripcion', 1, 0, 'C', 1);
$pdf->Cell(10, 7, 'Stock ', 1, 1, 'C', 1);

$datosStock = [

    ["20934", "Batería", "Sport", "2023", "LTH", "2.4", "Batería de gel", "4"],
	["20945", "Parabrisas", "Autobus", "2022", "LHD", "2.0", "Parabrisas superior", "2"],
	["20956", "Filtro de aceite", "Sedán", "2021", "ACDelco", "1.8", "Filtro de aceite de alta calidad", "10"],
	["20967", "Pastillas de freno", "Camioneta", "2020", "Brembo", "3.0", "Pastillas de freno de cerámica", "6"],
	["20978", "Llanta", "Deportivo", "2023", "Michelin", "2.5", "Llanta deportiva de alto rendimiento", "8"],
	["20989", "Alternador", "SUV", "2022", "Bosch", "2.2", "Alternador de alta potencia", "3"],
	["20990", "Amortiguador", "Compacto", "2021", "KYB", "1.6", "Amortiguador trasero", "12"],
	["21001", "Radiador", "Crossover", "2020", "Denso", "2.0", "Radiador de aluminio", "5"],
	["21012", "Bujías", "Pickup", "2022", "NGK", "4.0", "Bujías de platino", "20"],
	["21023", "Correa de distribución", "Sedán", "2021", "Gates", "1.8", "Correa de distribución reforzada", "7"]
];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosStock as $gastoR) {
    $pdf->Cell(20, 7, $gastoR[0], 1);
    $pdf->Cell(38, 7, $gastoR[1], 1);
    $pdf->Cell(20, 7, $gastoR[2], 1);
    $pdf->Cell(15, 7, $gastoR[3], 1);
    $pdf->Cell(15, 7, $gastoR[4], 1); 
	$pdf->Cell(10, 7, $gastoR[5], 1);
	$pdf->Cell(60, 7, $gastoR[6], 1); 
	$pdf->Cell(10, 7, $gastoR[7], 1, 1);
	  
}


// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');

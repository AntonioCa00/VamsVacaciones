<?php
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Diego Cruz Alvarez');
$pdf->setTitle('Formato Multiple');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' multiple ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFont('helvetica', '', 12); // Define the font, size, and style

$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Agregar una página
$pdf->AddPage();

// set margins
// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, "Formato multiple ", 0, 1,'C',0);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFont('helvetica', 'A', 11);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla

// MultiCell para el número de trabajador
$pdf->MultiCell(30, 5, 'NUMERO DE TRABAJADOR', 1, 'C', 1, 0);

// Ajustar X para mover el puntero de escritura a la derecha
$pdf->SetX($pdf->GetX() + 0); // Ajusta si quieres más espacio entre las celdas

// Centrar el contenido de la MultiCell horizontal y verticalmente
$pdf->MultiCell(30, 10, $datosEmpleado['empleadoN'], 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

$pdf->Ln(13); // Salto de línea antes de la tabla
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);

// Configuración del color de fondo de la cabecera
$pdf->SetFillColor(240, 240, 240);

// Primera fila
$pdf->SetFont('helvetica', 'B', 12); // Negritas para el texto antes de los ":"
$pdf->Cell(20, 6, 'Nombre:', 1, 0, 'L', 1); // Negritas solo en "Nombre:"

$pdf->SetFont('helvetica', '', 9); // Normal para el texto después de ":"
$pdf->Cell(105, 6, $datosEmpleado['nombres'].' '.$datosEmpleado['apellidoP']. ' '.$datosEmpleado['apellidoM'], 1, 0, 'L', 0); // Texto normal

$pdf->SetFont('helvetica', 'B', 12); // Negritas para "Fecha:"
$pdf->Cell(16, 6, 'Fecha:', 1, 0, 'L', 1); // Negritas solo en "Fecha:"

$pdf->SetFont('helvetica', '', 9); // Normal para la fecha
$pdf->Cell(39, 6, $datosEmpleado['fecha'], 1, 1, 'C', 0); // Texto normal

// Segunda fila
$pdf->SetFont('helvetica', 'B', 12); // Negritas para "Puesto:"
$pdf->Cell(20, 6, 'Puesto:', 1, 0, 'L', 1); // Negritas solo en "Puesto:"

$pdf->SetFont('helvetica', '', 9); // Normal para el puesto
$pdf->Cell(87, 6, $datosEmpleado['puesto'], 1, 0, 'L', 0); // Texto normal

$pdf->SetFont('helvetica', 'B', 12); // Negritas para "Fecha:"
$pdf->Cell(13, 6, 'Area:', 1, 0, 'L', 1); // Negritas solo en "Fecha:"

$pdf->SetFont('helvetica', '', 9); // Normal para la fecha
$pdf->Cell(60, 6, $datosEmpleado['area'], 1, 1, 'C', 0); // Texto normal

$pdf->ln(2);
// Establecer color de relleno para la línea (gris claro)
$pdf->SetFillColor(180, 180, 180); // Color gris más oscuro

// Dibujar una línea gris (rectángulo de 1px de alto)
$pdf->Cell(0, 1, '', 0, 1, 'C', true); // Rectángulo vacío con altura de 1 (ancho de la página)

$pdf->Ln(1); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo
$pdf->Cell(0, 10, "PARA AUSENTARSE DURANTE LA JORNADA DE TRABAJO", 0, 1,0);

// Crear la tabla
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(13, 6, 'Fecha:', 1, 0, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(22, 6, ($permiso === "Durante") ? $data['fecha'] : '', 1, 0, 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(40, 6, ' Estará ausente desde:', 1, 0, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(22, 6, ($permiso === "Durante") ? $data['inicio'] : '', 1, 0, 1);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(16, 6, ' Hasta:', 1, 0, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(22, 6, ($permiso === "Durante") ? $data['fin'] : '', 1, 0, 1);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(20, 6, 'N° horas:', 1, 0, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(25, 6, ($permiso === "Durante") ? $data['duracion'] : '', 1, 1, 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(180, 6, 'MOTIVO DE SU SALIDA:', 1, 1, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->MultiCell(180, 10, ($permiso === "Durante") ? $data['motivo'] : '', 1, 1, 0);

$pdf->ln(2);
// Establecer color de relleno para la línea (gris claro)
$pdf->SetFillColor(180, 180, 180); // Color gris más oscuro

// Dibujar una línea gris (rectángulo de 1px de alto)
$pdf->Cell(0, 1, '', 0, 1, 'C', true); // Rectángulo vacío con altura de 1 (ancho de la página)

$pdf->Ln(1); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo
$pdf->Cell(0, 10, "PARA AUSENTARSE DE SUS LABORES", 0, 1,0);

// Ancho y alto del cuadro
$cuadroAncho = 6;
$cuadroAlto = 5;

// Crear la tabla
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(45, 6, 'Permiso del día:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, ($permiso === "Ausentarse") ? $data['inicio'] : '', 1, 0,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(45, 6, 'Al día:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, ($permiso === "Ausentarse") ? $data['fin'] : '', 1, 1,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(25, 6, ' Con goce: ', 1, 0, 'L', 1);
// Definir el color de relleno (RGB)
$pdf->SetFillColor(2, 12, 162); // Gris claro, puedes ajustar los valores
// Repite el proceso para más opciones si es necesario
$pdf->Cell(13, 6, '', 1, 0, 'C');
// Dibujar el cuadro con color
$pdf->Rect($pdf->GetX() - 10, $pdf->GetY() + 0.6, $cuadroAncho, $cuadroAlto,($permiso === "Ausentarse" && $data['tipo'] === "Con") ? 'F' : ''); // 'F' para llenar

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(25, 6, ' Sin goce: ', 1, 0,'L', 1);

// Definir el color de relleno (RGB)
$pdf->SetFillColor(2, 12, 162); // Gris claro, puedes ajustar los valores
// Repite el proceso para más opciones si es necesario
$pdf->Cell(13, 6, '', 1, 0, 'C');
// Dibujar el cuadro con color
$pdf->Rect($pdf->GetX() - 10, $pdf->GetY() + 0.6, $cuadroAncho, $cuadroAlto,($permiso === "Ausentarse" && $data['tipo'] === "Sin") ? 'F' : ''); // 'F' para llenar

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(35, 6, ' Tiempo x Tiempo: ', 1, 0, 'L', 1);
$pdf->SetFillColor(2, 12, 162); // Gris claro, puedes ajustar los valores
// Repite el proceso para más opciones si es necesario
$pdf->Cell(13, 6, '', 1, 0, 'C');
// Dibujar el cuadro con color
$pdf->Rect($pdf->GetX() - 10, $pdf->GetY() + 0.6, $cuadroAncho, $cuadroAlto,($permiso === "Ausentarse" && $data['tipo'] === "Tiempo") ? 'F' : ''); // 'F' para llenar
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(32, 6, ' Numero de días: ', 1, 0, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(24, 6, ($permiso === "Ausentarse") ? $data['duracion'] : '', 1, 1,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(180, 6, 'MOTIVO DE SU SALIDA:', 1, 1,'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->MultiCell(180, 10, ($permiso === "Ausentarse") ? $data['motivo'] : '', 1, 1, 0);

$pdf->ln(2);
// Establecer color de relleno para la línea (gris claro)
$pdf->SetFillColor(180, 180, 180); // Color gris más oscuro

// Dibujar una línea gris (rectángulo de 1px de alto)
$pdf->Cell(0, 1, '', 0, 1, 'C', true); // Rectángulo vacío con altura de 1 (ancho de la página)

$pdf->Ln(1); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo
$pdf->Cell(0, 10, "VACACIONES", 0, 1,0);

// Crear la tabla
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(55, 6, 'Número de días solicitados:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(35, 6, ($permiso === "Vacaciones") ? $data['duracion'] : '', 1, 0,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(45, 6, 'Días restantes:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, ($permiso === "Vacaciones") ? $data['restante'] : '', 1, 1,'C', 0);
$pdf->SetFont('helvetica', 'B', 11); // Negritas para "Fecha:"
$pdf->Cell(180, 6, 'Fechas solicitadas', 1, 1, 'C', 1);
$pdf->Cell(45, 6, 'Periodo del día:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, ($permiso === "Vacaciones") ? $data['inicio'] : '', 1, 0,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(45, 6, 'Al día:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, ($permiso === "Vacaciones") ? $data['fin'] : '', 1, 1,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(180, 6, 'COMENTARIOS Y OBSERVACIONES:', 1, 1, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->MultiCell(180, 10, ($permiso === "Vacaciones") ? $data['motivo'] : '', 1, 1, 0);

$pdf->ln(2);
// Establecer color de relleno para la línea (gris claro)
$pdf->SetFillColor(180, 180, 180); // Color gris más oscuro

// Dibujar una línea gris (rectángulo de 1px de alto)
$pdf->Cell(0, 1, '', 0, 1, 'C', true); // Rectángulo vacío con altura de 1 (ancho de la página)

$pdf->Ln(3); // Salto de línea antes de la tabla

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(55, 6, 'Aceptado:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(35, 6, '', 1, 0,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(45, 6, 'Rechazado:', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->Cell(45, 6, '', 1, 1,'C', 0);
$pdf->SetFont('helvetica', 'B', 10); // Negritas para "Fecha:"
$pdf->Cell(180, 6, 'COMENTARIOS Y OBSERVACIONES:', 1, 1, 'L', 1);
$pdf->SetFont('helvetica', '', 10); // Normal para la fecha
$pdf->MultiCell(180, 10, ' ', 1, 1, 0);

$pdf->SetY(262); // Ajusta la posición Y según tus necesidades
// Dibujar una línea

// Coordenadas iniciales y finales para los tres segmentos
$x1 = 10;
$x2 = 70;
$x3 = 130;

$y = $pdf->GetY(); // Obtener la posición Y actual

// Dibujar el primer segmento de la línea
$pdf->Line(17, $y, 60, $y);

// Dibujar el segundo segmento de la línea
$pdf->Line(82, $y, 130, $y);

// Dibujar el tercer segmento de la línea
$pdf->Line(147, $y, 190, $y);

$pdf->SetFont('helvetica', '', 11,);
$pdf->Cell(0, 10, '              Empleado                                         Jefe inmediato                               Recursos Humanos ', 0, 1, 'A', 0);


if($permiso === 'Vacaciones'){
    $nombreArchivo = 'vacaciones_' . $idcorresponde . '.pdf';

    $rutaDescarga = 'C:/wamp64/www/VamsVacaciones/public/vacaciones/' . $nombreArchivo;
    //$rutaDescarga = 'C:/laragon/www/VamsVacaciones/public/vacaciones/'. $nombreArchivo;

    $pdf->Output($rutaDescarga, 'F');
} else {
    $pdf->Output('Solicitud de permiso', 'I');
}

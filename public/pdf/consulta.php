<?php
    require("conexion.php");
    $SQL = "SELECT * FROM compra /* WHERE fecha_compra >= DATE_SUB(NOW(), INTERVAL 1 MONTH)*/";
    $stmt = $conexion->prepare($SQL);
    $result = $stmt->execute(); 
    $rows = $stmt->fetchALL(\PDO::FETCH_ASSOC);
    foreach($rows as $row){
        print $row["id_compra"].";".$row["fecha_compra"].";".$row["unidad_id"].";".$row["admin_id"].";".$row["costo"].$row["refaccion_id"].";".$row["factura"].";"."/n";

    }
<?php
$conexion = null;
$servidor = 'localhost';
$bd= 'vams_bd';
$user = 'root';
$pass = '';
try{
    $conexion = new PDO('mysql:host='.$servidor. ';bdname='.$bd,$user, $pass);
}catch(PDOException $e){
    echo "Error de conexion!";
    exit;
}
return $conexion;
?>

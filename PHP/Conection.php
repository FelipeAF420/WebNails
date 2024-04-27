<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$database = "butfirstnails"; 

try {
    $conexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

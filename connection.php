<?php
    $servername = "localhost";
    $dbname = "tfg_mad";
    $username = "MAD";
    $password = "";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
?>
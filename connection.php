<?php
    $servername = "localhost";
    $dbname = "TFG_MAD";
    $username = "MAD";
    $password = "Qwerty-1234";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
?>
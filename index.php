<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riot Merch</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="barra">
    <div>
        <a href="index.php"><img id="logo" src="img/ri8566rddb-riot-games-logo-riot-games-logo-download-vector.png"></a>
    </div>
    <div>
        <a href="carrito.php">Carrito</a>
        <?php
        if (isset($_SESSION["User"])) {
            echo "<a href=\"Perfil.php\"><p>Mi Perfil</p></a>";
        } else {
            echo "<a href=\"inicio.php\"><p>Iniciar sesión</p></a>";
        }
        ?>
    </div>
</div>
<div>
    <img id="lux" src="img/Porcelain_Lunar_Revel_2024_Promo_Homepage_Hero_2560x722.webp">
</div>
<div id="venta">
    <?php
    include "Conexion.php";
    $select = $conn->prepare("SELECT * FROM producto");
    $select->execute();
    $resultado = $select->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        $ID = $fila["ID"];
        $Nombre = $fila["Nombre"];
        $foto = $fila["foto"];
        $precio = $fila["precio"];
        echo "<div class=\"producto\">";
        echo "<a href=\"producto.php?id=$ID\">";
        echo "<div>";
        echo "<img src=\"img/Productos/$foto.png\">";
        echo "</div>";
        echo "<p>$Nombre</p>";
        echo "<p id=\"precio\">$precio</p>";
        echo "</a>";
        echo "</div>";
    }
    
    ?>
</div>
</body>
<footer>
    <div><img src="img/ri8566rddb-riot-games-logo-riot-games-logo-download-vector.png" alt=""></div>
    <div><ul>
        <li>
            <a href="https://es-es.facebook.com/"><i class="fa-brands fa-facebook"></i></a>
        </li>
        <li><a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a></li>
        <li><a href="https://www.youtube.com/"><i class="fa-brands fa-youtube"></i></a></li>
    </ul></div>
</footer>
</html>

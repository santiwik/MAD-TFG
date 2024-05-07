<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            session_start();
            if (isset($_SESSION["User"])) {
                echo "<a href=\"Perfil.php\"><p>Mi Perfil</p></a>";
            } else {
                echo "<a href=\"inicio.php\"><p>Iniciar sesión</p></a>";
            }
            ?>
        </div>
</div>
    <?php
    include "Conexion.php";
    $id_producto = $_GET["id"];
    $select = $conn->prepare("SELECT * FROM producto WHERE id=?");
    $select->bind_param("s", $id_producto);
    $select->execute();
    $resultado = $select->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $ID = $fila["ID"];
        $Nombre = $fila["Nombre"];
        $foto = $fila["foto"];
        $precio = $fila["precio"];
        $descripcion = $fila["descripcion"];
        $edicion = $fila["edicion"];
        $juego = $fila["juego"];
    }
    echo "<title>$Nombre</title>";
    if (isset($_POST["carrito"])) {
        if (isset($_SESSION["carrito"][$ID])) {
            $_SESSION["carrito"][$ID]["cantidad"]++;
            $_SESSION["mensaje"] = "<p id=\"mensaje\">Se ha agregado correctamente, de nuevo.</p>";
        } else {
            $_SESSION["carrito"][$ID] = array(
                "ID" => $ID,
                "Nombre" => $Nombre,
                "precio" => $precio,
                "foto" => $foto,
                "cantidad" => 1
            );
            $_SESSION["mensaje"] = "<p id=\"mensaje\">Se ha agregado correctamente.</p>";
        }
    }
    ?>
    <div id="pro">
        <div id="img">
            <?php echo "<img src=\"img/Productos/$foto.png\">"; ?>
        </div>
        <div id="producto">
            <div id="juego">
                <?php echo "<img src=\"img/$juego.png\">"; ?>
            </div>
            <div>
                <div>
                    <?php echo "<p id=\"edicion\">$edicion</p>"; ?>
                </div>
                <div id="prenom">
                    <?php echo "<h2>$Nombre</h2>"; ?>
                    <?php echo "<p>$precio</p>"; ?>
                </div>
                <div id="boton-carrito">
                    <form method="post">
                        <input type="submit" id="boton-enviar" name="carrito" value="<?php echo $precio; ?> - Añadir a la Cesta">
                        <?php if (isset($_SESSION["mensaje"])) {
                            echo  $_SESSION["mensaje"];
                        }
                        ?>
                    </form>
                </div>
                <div>
                    <?php echo "<p id=\"descripcion\">$descripcion</p>"; ?>
                </div>
            </div>
        </div>
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

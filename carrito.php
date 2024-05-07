<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
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
        <div id="carritocontainer">
    <?php
    include "Conexion.php";
    if (empty($_SESSION["carrito"])) {
        echo "Tu carrito está vacío.";
    } else {
        echo "<h2>Carrito de Compras</h2>";
        echo "<form method='post'>";
        echo "<table border=\"1\">";
        echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acciones</th></tr>";
        $total = 0;
        foreach ($_SESSION["carrito"] as $ID => $producto) {
            $subtotal = $producto["precio"] * $producto["cantidad"];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>{$producto["Nombre"]}</td>";
            echo "<td>€ {$producto["precio"]}</td>";
            echo "<td>";
            echo "<button type='submit' name='restar' value='$ID'>-</button>";
            echo " {$producto["cantidad"]} ";
            echo "<button type='submit' name='sumar' value='$ID'>+</button>";
            echo "</td>";
            echo "<td>€ {$subtotal}</td>";
            echo "<td><button type=\"submit\" name=\"eliminar\" value=\"$ID\">Eliminar</button></td>";
            echo "</tr>";
        }
        echo "<tr><td colspan=\"3\">Total</td><td>€ {$total}</td><td></td></tr>";
        echo "</table>";
        echo "</form>";
        if (isset($_POST["eliminar"])) {
            $ID = $_POST["eliminar"];
            unset($_SESSION["carrito"][$ID]);
            header("Location: carrito.php");
            exit;
        }
        if (isset($_POST["restar"])) {
            $ID = $_POST["restar"];
            if ($_SESSION["carrito"][$ID]["cantidad"] > 1) {
                $_SESSION["carrito"][$ID]["cantidad"]--;
            } else {
                unset($_SESSION["carrito"][$ID]);
            }
            header("Location: carrito.php");
            exit;
        }
        if (isset($_POST["sumar"])) {
            $ID = $_POST["sumar"];
            $_SESSION["carrito"][$ID]["cantidad"]++;
            header("Location: carrito.php");
            exit;
        }
        if(isset($_SESSION["User"])) {
            echo "<form method='post'>";
            echo "<button id=\"boton-enviar\" type='submit' name='pagar'>Pagar</button>";
            echo "</form>";
            if(isset($_POST["pagar"])) {
                $update = $conn->prepare("UPDATE cliente SET Pedidos = Pedidos + 1, DineroGastado = DineroGastado + ? WHERE User = ?");
                $update->bind_param("ss", $total, $_SESSION["User"]);
                $update->execute();
                echo "La compra se realizó correctamente.";
                unset($_SESSION["carrito"]);
            }
        } else {
            echo "Debes iniciar sesión para poder pagar.";
        }
    }
    ?></div>

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
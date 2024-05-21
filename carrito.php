<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";
    if (isset($_POST["del"])) {
        $idprod = $_POST["del"];
        unset($_SESSION["carro"][$idprod]);
        header("Location: carrito.php");
        exit;
    }
    if (isset($_POST["restar"])) {
        $idprod = $_POST["restar"];
        if ($_SESSION["carro"][$idprod]["cant"] > 1) {
            $_SESSION["carro"][$idprod]["cant"]--;
        } else {
            unset($_SESSION["carro"][$idprod]);
        }
        header("Location: carrito.php");
        exit;
    }
    if (isset($_POST["sumar"])) {
        $idprod = $_POST["sumar"];
        $_SESSION["carro"][$idprod]["cant"]++;
        header("Location: carrito.php");
        exit;
    }
    ?>
</head>

<body class="login">
    <header>
        <?php
        include "header.php";
        ?>
    </header>
    <main>
        <div id="no-form">
        <?php
        $total = 0;
        if (!empty($_SESSION["carro"])) {
            echo '<h2>Carrito</h2>';
            echo '<form method="post">';
            echo '<table>';
            echo '    <tr>';
            echo '        <th>Producto</th>';
            echo '        <th>Precio</th>';
            echo '        <th>Cantidad</th>';
            echo '        <th>Subtotal</th>';
            echo '        <th>Eliminar</th>';
            echo '    </tr>';
            foreach ($_SESSION["carro"] as $idprod => $prod) {
                $subtotal = $prod["precioprod"] * $prod["cant"];
                $total += $subtotal;
                echo '<tr>';
                echo '  <td>' . $prod["nameprod"] . '</td>';
                echo '  <td>' . $prod["precioprod"] . '</td>';
                echo '  <td>';
                echo '      <div class="resta">';
                echo '          <button type="submit" name="restar" value=' . $idprod . '>-</button>';
                echo            $prod["cant"];
                echo '          <button type="submit" name="sumar" value=' . $idprod . '>+</button>';
                echo '      </div>';
                echo '  </td>';
                echo '  <td>€ ' . $subtotal . '</td>';
                echo '  <td><button type="submit" name="del" value="' . $idprod . '">Eliminar</button></td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '    <th colspan="3">Total</th>';
            echo '    <td>€ ' . $total . '</td>';
            echo '</tr>';
            echo '</table>';
            echo '</form>';
            echo '<form method="post">';
            echo '    <input id="botonpago" value="Pagar" type="submit" name="pagar">';
            echo '</form>';
            if(isset($_POST["pagar"])) {
                $sql = $conn->prepare("INSERT INTO pedidos (user_id, total_price) VALUES (?, ?)");
                $sql->bind_param("ss", $_SESSION["id"], $total);
                $sql->execute();
                $pedido_id = $sql->insert_id;
                foreach ($_SESSION["carro"] as $idprod => $prod) {
                    $subtotal2= $prod["precioprod"]*$prod["cant"];
                    $sql = $conn->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, quantity, precio) VALUES (?, ?, ?, ?)");
                    $sql->bind_param("ssss", $pedido_id, $idprod, $prod["cant"], $subtotal2);
                    $sql->execute();
                }
                unset($_SESSION["carro"]);
                $_SESSION["msg"] = "El pedido se realizó correctamente. ¡Gracias por su compra!";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit;
            }
        } else {
            echo "<p>Tu carro esta vacio </p>";
        }
        if(isset($_SESSION["msg"])) {
            echo "<p>" . $_SESSION["msg"] . "</p>";
            unset($_SESSION["msg"]); // Limpiar el mensaje después de mostrarlo
        }
        ?>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="index.css">
</head>

<body id="inicio">
    <?php
    //Conexion php
    include "Conexion.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Datos del cliente
        $Name = $_POST["Name"];
        $Surname = $_POST["Surname"];
        $User = $_POST["User"];
        $Email = $_POST["Email"];
        $Password = $_POST["Password"];
        $Password1 = $_POST["Password1"];
        $Direccion = $_POST["Direccion"];

        $Pass = password_hash($Password, PASSWORD_DEFAULT);


        $stmt = $conn->prepare("SELECT * FROM cliente WHERE User = ?");
        if ($stmt === false) {
            die("prepare() failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("s", $User);
        $stmt->execute();

        $error = $stmt->get_result();
        if ($Password != $Password1) {
            $_SESSION["error1"] = "Las contraseñas no coinciden";
        } elseif ($error->num_rows > 0) {
            $_SESSION["error"] = "Este usuario ya existe";
        } else {
            $sql = $conn->prepare("INSERT INTO `cliente` VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, DEFAULT, DEFAULT)");
            $sql->bind_param("ssssss", $Name, $Surname, $User, $Email, $Pass, $Direccion);

            if ($sql->execute()) {
                header("Location: inicio.php");
            } else {
                echo "Error al insertar el registro: " . $conn->error;
            }
        }
    }
    ?>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
        <div class="center">
            <a href="index.php">
                <img id="logo" src="img/riot-games-logo-1.png" alt="Logo">
            </a>
        </div>
        <div id="registro">
            <div>
                <p>Nombre: </p><input type="text" name="Name" required>
            </div>
            <div>
                <p>Apellido: </p> <input type="text" name="Surname" required>
            </div>
            <div>
                <p>Usuario: </p> <input type="text" name="User" required>
            </div>
            <?php if (isset($_SESSION["error"])) : ?>
                <div class="center">
                    <?php
                    echo "<p id=\"error\">";
                    echo $_SESSION["error"];
                    echo "</p>";
                    ?>
                </div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>
            <div>
                <p>Email: </p> <input type="email" name="Email" required>
            </div>
            <div>
                <p>Contrase&ntilde;a: </p> <input type="password" name="Password" required>
            </div>
            <div>
                <p>Confirmar Contrase&ntilde;a: </p> <input type="password" name="Password1" required>
            </div>
            <?php
            if (isset($_SESSION["error1"])) {
                echo "<div class=\"center\">";
                echo "<p id=\"error\">";
                echo $_SESSION["error1"];
                echo "</p>";
                echo "</div>";
            }
            ?>
            <div>
                <p>Direccion: </p> <input type="text" name="Direccion" required>
            </div>
        </div>
        <div class="center">
            <input id="boton-enviar" value="⮕" type="submit">
        </div>
        <a href="inicio.php">¿Tienes cuenta? Inicia Sesion</a>
    </form>
</body>

</html>
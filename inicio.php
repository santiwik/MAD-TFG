<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body id="inicio">
    <?php
    session_start();
    if (isset($_SESSION["User"])) {
        header("Location: Perfil.php");
        exit();
    }
    //conexion base de datos
    include "Conexion.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Datos usuario
        $User = $_POST["User"];
        $Password = $_POST["Password"];
        //confirmacion de usuario
        $select = $conn->prepare("SELECT USER FROM cliente WHERE USER=?");
        $select->bind_param("s", $User);
        $select->execute();
        $resultado = $select->get_result();
        if ($resultado->num_rows == 1) {
            $Usuarioconfirmado = true;
        } else {
            $Usuarioconfirmado = false;
            $_SESSION["error"] = "Usuario inexistente";
        }
        //Confirmacion de contraseña
        if ($Usuarioconfirmado == true) {
            $select = $conn->prepare("SELECT PASSWORD FROM cliente WHERE USER=?");
            $select->bind_param("s", $User);
            $select->execute();
            $resultado = $select->get_result();
            $row = $resultado->fetch_assoc();
            $hash = $row["PASSWORD"];
            //confirmacion de errores
            if (password_verify($Password, $hash) && $Usuarioconfirmado == true) {
                $_SESSION["User"] = $User;
                header("Location: Perfil.php");
            } else {
                $_SESSION["error"] = "Contraseña incorrecta";
            }
        }
    }

    ?>
    <form method="post">
        <div class="center">
            <a href="index.php">
                <img id="logo"src="img/riot-games-logo-1.png" alt="Logo">
            </a>
        </div>
        <div>
            <p>Usuario: </p>
            <input type="text" name="User">
        </div>
        <div>
            <p>Contraseña: </p>
            <input type="password" name="Password">
        </div>
        <div class="center">
            <input id="boton-enviar" value="⮕" type="submit">
        </div>
        <?php if (isset($_SESSION["error"])) : ?>
                <div class="center"><?php echo "<p id=\"error\">";
                echo $_SESSION["error"]; 
                echo "</p>";?></div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

        <a href="Registro.php">¿No tienes cuenta? Crea una.</a>


    </form>

</body>

</html>
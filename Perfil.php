<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
</head>
<header>
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
</header>

<body id="perfil">

    <?php
    //conexion base de datos
    include "Conexion.php";
    $User = $_SESSION["User"];
    //variables de inicio 
    $cambiou = false;
    $cambiou2 = false;
    $cambioc = false;
    $cambiod = false;
    $confirmacion = " Los cambios se realizaron correctamente, actualice para ver los cambios";
    $erroru = "Este usuario ya existe, porfavor cambielo";
    //inicio
    $select = $conn->prepare("SELECT * FROM cliente WHERE USER=?");
    $select->bind_param("s", $User);
    $select->execute();
    $resultado = $select->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $Name = $fila["Name"];
        $Surname = $fila["Surname"];
        $hash = $fila["Password"];
        $User = $fila["User"];
        $Email = $fila["Email"];
        $Direccion = $fila["Direccion"];
        $Pedidos = $fila["Pedidos"];
        $DineroGastado = $fila["DineroGastado"];
    }
    //cambiar usuario
    if (isset($_POST["confirmar_usuario"])) {
        $User_nuevo = $_POST["cambio_usuario"];
        $select1 = $conn->prepare("SELECT User FROM cliente WHERE USER=?");
        $select1->bind_param("s", $User_nuevo);
        $select1->execute();
        $error4 = $select1->get_result();
        if ($error4->num_rows <= 0) {
            $update = $conn->prepare("UPDATE cliente set USER=? where USER=?");
            $update->bind_param("ss", $User_nuevo, $User);
            $update->execute();
            $_SESSION["User"] = $User_nuevo;
            $cambiou = true;
        } else {
            $cambiou2 = true;
        }
    }
    //cambiar contraseña
    if (isset($_POST["confirmar_contrasena"])) {
        $c0 = $_POST["cambio_contrasena"];
        $c1 = $_POST["nueva_contrasena"];
        $c2 = $_POST["nueva_contrasena1"];
        if ($c1 == $c2 && password_verify($c0, $hash)) {
            $update = $conn->prepare("UPDATE cliente set Password=? where USER=?");
            $update->bind_param("ss", password_hash($c1, PASSWORD_DEFAULT), $User);
            $update->execute();
            session_destroy();
            header("Location: inicio.php");
        } elseif ($c1 != $c2) {
            $_SESSION["error"] = "<p>Las contraseñas no coinciden</p>";
        } else {
            $_SESSION["error"] = "<p>Contraseña incorrecta</p>";
        }
    }
    //cambiar correo
    if (isset($_POST["confirmar_email"])) {
        $e = $_POST["cambio_email"];
        $update = $conn->prepare("UPDATE cliente set Email=? where USER=?");
        $update->bind_param("ss", $e, $User);
        $update->execute();
        $cambioc = true;
    }
    //cambio datos personales
    if (isset($_POST["confirmar_datos"])) {
        $n = $_POST["cambio_name"];
        $s = $_POST["cambio_surname"];
        $d = $_POST["cambio_direccion"];
        $update = $conn->prepare("UPDATE cliente set Name=? , Surname=? , Direccion=? where USER=?");
        $update->bind_param("ssss", $n, $s, $d, $User);
        $update->execute();
        $cambiod = true;
    }
    //cerrar sesion
    if (isset($_POST["cerrar"])) {
        session_destroy();
        header("Location: index.php");
    }
    ?>
    <div class="bloques">
        <h1>Administracion de la cuenta</h1>
        <div>
            <ul>
                <li><a href="#usuario" class="enlace"><i class="fa-solid fa-user icono"></i>
                        <p>Usuario</p>
                    </a></li>
                <li><a href="#contraseña" class="enlace"><i class="fa-solid fa-key icono"></i>
                        <p>Camiar Contraseña</p>
                    </a></li>
                <li><a href="#correo" class="enlace"><i class="fa-solid fa-envelope icono"></i>
                        <p>Cambiar Correo</p>
                    </a></li>
                <li><a href="#datos" class="enlace"><i class="fa-solid fa-info icono"></i>
                        <p>Informacion Personal</p>
                    </a></li>
                <li><a href="#pedidos" class="enlace"><i class="fa-solid fa-cart-shopping icono"></i>
                        <p>Pedidos realizados</p>
                    </a></li>
            </ul>
        </div>
        <form method="post">
            <input class="submitperfil" type="submit" name="cerrar" value="Cerrar sesion">
        </form>
    </div>
    <div class="bloques">
        <!--cambiar usuario-->
        <div class="cambios" id="usuario">
            <div>
                <h2>Cambiar tu nombre de Usuario</h2>
                <p>Los jugadores usaran tu Usuario para encontrarte en el panel social de nuestros juegos.</p>
            </div>
            <div>
                <form method="post">
                    <div>
                        <input class="textperfil" type="text" placeholder="<?php echo $User ?>" name="cambio_usuario" required>
                        <?php if ($cambiou2 == true) {
                            echo "<p>";
                            echo $erroru;
                            echo "</p>";
                        } elseif ($cambiou == true) {
                            echo "<p>";
                            echo $confirmacion;
                            echo "</p>";
                            $cambiou = false;
                        } ?>
                    </div>
                    <div>
                        <input class="submitperfil" type="submit" name="confirmar_usuario" value="Guardar Cambios">
                    </div>
                </form>
            </div>
        </div>
        <!--cambiar contraseña-->
        <div class="cambios" id="contraseña">
            <div>
                <h2>Cambiar tu contraseña</h2>
                <p>Te recomendamos que actualices periódicamente tu contraseña para evitar el acceso no autorizado a tu cuenta.</p>
            </div>
            <div>
                <form method="post">
                    <div>
                        <input class="textperfil" type="password" name="cambio_contrasena" placeholder="Contraseña actual" required>
                        <input class="textperfil" type="password" name="nueva_contrasena" placeholder="Contraseña nueva" required>
                        <input class="textperfil" type="password" name="nueva_contrasena1" placeholder="Confirme nueva contraseña" required>
                        <?php if (isset($_SESSION["error"])) {
                            echo $_SESSION["error"];
                            unset($_SESSION["error"]);
                        } ?>
                    </div>
                    <div>
                        <input class="submitperfil" type="submit" name="confirmar_contrasena" value="Guardar Cambios">
                    </div>
                </form>
            </div>
        </div>
        <!--cambiar correo-->
        <div class="cambios" id="correo">
            <div>
                <h2>Cambiar el correo</h2>
                <p>En caso de querer exportar la cuenta a otro correo, cambielo aqui.</p>
            </div>
            <div>
                <form method="post">
                    <div>
                        <input class="textperfil" type="email" placeholder="<?php echo $Email ?>" name="cambio_email" required>
                        <?php if ($cambioc == true) {
                            echo $confirmacion;
                        } ?>
                    </div>
                    <div>
                        <input class="submitperfil" type="submit" name="confirmar_email" value="Guardar Cambios">
                    </div>
                </form>
            </div>
        </div>
        <!--cambiar datos personales-->
        <div class="cambios" id="datos">
            <div>
                <h2>Informacion personal</h2>
                <p>Esta información es privada y no se compartirá con otros jugadores.</p>
            </div>
            <div>
                <form method="post">
                    <div>
                        <p>Nombre:</p>
                        <input class="textperfil" type="text" value="<?php echo $Name ?>" name="cambio_name">
                        <p>Apellido:</p>
                        <input class="textperfil" type="text" value="<?php echo $Surname ?>" name="cambio_surname">
                        <p>Direccion:</p>
                        <input class="textperfil" type="text" value="<?php echo $Direccion ?>" name="cambio_direccion">
                        <?php if ($cambiod == true) {
                            echo $confirmacion;
                        } ?>
                    </div>
                    <div>
                        <input class="submitperfil" type="submit" name="confirmar_datos" value="Guardar Cambios">
                    </div>
                </form>
            </div>
        </div>
        <!--visualizacion de pedidos-->
        <div class="cambios" id="pedidos">
            <div>
                <h2>Pedidos realizados</h2>
            </div>
            <div>
                <div>
                    <p>PEDIDOS HECHOS: <?php echo $Pedidos ?></p>
                </div>
                <div>
                    <p>DineroGastado: <?php echo $DineroGastado ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
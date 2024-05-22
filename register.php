<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";
    if (isset($_SESSION["user"])) {
        header("Location:profile.php");
        exit();
    }

    if (isset($_POST["google"])) {
        $_SESSION["google"] = 1;
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit();
    }
    if (isset($_POST["registro"])) {
        /*Error Usuario*/
        $sql = $conn->prepare("select user from usuarios where user = ?");
        $sql->bind_param("s", $_POST["user"]);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows == 1) {
            $_SESSION["error"] = "Este usuario ya esta registrado, prueba a iniciar sesion o usar otro usuario";
            $usrbad = true;
        } else {
            $usrbad = false;
        }
        $sql = $conn->prepare("insert into usuarios (user, email, pwd) values(?,?,?)");
        if ($usrbad == false) {
            if ($_POST["pwd"] != $_POST["cpwd"]) {
                $_SESSION["error"] = "Las contraseñas no coinciden";
            } else {
                $hash = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                $sql->bind_param("sss", $_POST["user"], $_POST["email"], $hash);
                if ($sql->execute()) {
                    header("Location:login.php");
                }
            }
        }
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
        <form method="post">
            <legend><h1>Reg&iacute;strate</h1></legend>
            <div class="input-text">
                <input type="text" name="user" placeholder="Usuario" required>
            </div>
            <div class="input-text">

                <input type="email" name="email" placeholder="Email"required>
            </div>
            <div class="input-text">
                
                <input type="password" name="pwd" placeholder="Contraseña" required>
            </div>
            <div class="input-text">
                
                <input type="password" name="cpwd" placeholder="Confirmar contraseña" required>
            </div>
            <div class="input-text">
                <input type="submit" value="Registrarse" name="registro">
            </div>
            <?php
            if (isset($_SESSION["error"])) {
                echo '<div class="Error">';
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
                echo "</div>";
            }
            ?>

            <p>¿Tienes cuenta? <a href="login.php">Inicia sesi&oacute;n</a></p>
        </form>
    </main>
    <footer>

    </footer>
</body>

</html>
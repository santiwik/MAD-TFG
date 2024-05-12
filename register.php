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

    /*Google*/
    require_once 'vendor/autoload.php';
    require_once 'config.php';

    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    if (isset($_POST["google"])) {
        $_SESSION["google"] = 1;
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit();
    }

    /*Registro*/
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

<body>
    <header>
        <?php
        include "header.php";
        ?>
    </header>
    <main>
        <form method="post">
            <legend>Reg&iacute;strate</legend>
            <div>
                <label for="user">Usuario: </label>
                <input type="text" name="user" required>
            </div>
            <div>
                <label for="user">Email: </label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label for="pwd">Contrase&ntilde;a: </label>
                <input type="password" name="pwd" required>
            </div>
            <div>
                <label for="pwd">Confirmar contrase&ntilde;a: </label>
                <input type="password" name="cpwd" required>
            </div>
            <div>
                <input type="submit" value="Iniciar Sesi&oacute;n" name="registro">
            </div>
            <?php
            if (isset($_SESSION["error"])) {
                echo "<div>";
                echo $_SESSION["error"];
                unset($_SESSION["error"]);
                echo "</div>";
            }
            ?>
            <div>
                <input type="submit" name="google" value="Registrate con Google">
            </div>
            <p>¿Tienes cuenta? <a href="register.php">Inicia sesi&oacute;n</a></p>
        </form>
    </main>
    <footer>

    </footer>
</body>

</html>
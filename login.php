<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
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


  /*Inicio sesion normal*/

  /*Comprobacion de usuario*/
  if (isset($_POST["pwd"])) {
    $sql = $conn->prepare("select user from usuarios where user=?");
    $sql->bind_param("s", $_POST["user"]);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows == 1) {
      $usrconfirm = true;
    } else {
      $usrconfirm = false;
      $_SESSION["error"] = "El usuario indicado no existe";
    }
    /*Contraseña*/
    if ($usrconfirm == true) {
      $sql = $conn->prepare("select pwd, rol from usuarios where user=?");
      $sql->bind_param("s", $_POST["user"]);
      $sql->execute();
      $result = $sql->get_result();
      if ($row = $result->fetch_assoc()) {
        $pwd = $row["pwd"];
      }
      /*errores contraseña*/
      if (password_verify($_POST["pwd"], $pwd) && $usrconfirm == true) {
        $_SESSION["user"] = $_POST["user"];
        $_SESSION["rol"] = $row["rol"];
        header("profile.php");
      } else {
        $_SESSION["error"] = "La contraseña es incorrecta";
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
    <div>
      <form method="post">
        <legend>
          <h1>Iniciar Sesi&oacute;n</h1>
        </legend>
        <div class="input-text">
          <?php
          if (isset($_SESSION["error"])) {
            echo "<div>";
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
            echo "</div>";
          }
          ?>
          <label for="user"> </label>
          <input type="text" name="user" placeholder="Usuario">
        </div>
        <div class="input-text">
          <label for="pwd"></label>
          <input type="password" name="pwd" placeholder="Contraseña">
        </div>
        <div>
          <input type="submit" value="Iniciar Sesi&oacute;n" name="login">
        </div>
        <div>
          <input type="submit" name="google" value="Iniciar sesión con Google">
        </div>
        <p>¿No tienes cuenta? <a href="register.php">Registrate</a></p>
      </form>
    </div>
  </main>
  <footer>

  </footer>
</body>

</html>
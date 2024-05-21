<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
  <?php
  session_start();
  include "connection.php";
  require_once 'vendor/autoload.php';
  require_once 'config.php';

  // Google Client Configuration
  $client = new Google_Client();
  $client->setClientId($clientID);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($redirectUri);
  $client->addScope("email");
  $client->addScope("profile");

  if (isset($_GET['code']) && $_SESSION["google"] = 1) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;

    $sql = $conn->prepare("SELECT user FROM usuarios WHERE user=?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows != 1) {
      $sql = $conn->prepare("INSERT INTO usuarios(user,email) VALUES(?,?)");
      $sql->bind_param("ss", $name, $email);
      $sql->execute();
    }
    $_SESSION["user"] = $name;
    $sql = $conn->prepare("SELECT rol FROM usuarios WHERE user=?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $result = $sql->get_result();
    if ($row = $result->fetch_assoc()) {
      $_SESSION["rol"] = $row["rol"];
    }
    $_SESSION["Google"] = 1;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }

  if (isset($_POST["cerrar"])) {
    session_destroy();
    header("Location: index.php");
    exit;
  }

  if (isset($_SESSION["user"])) {
    $sql = $conn->prepare("SELECT * FROM usuarios WHERE user=?");
    $sql->bind_param("s", $_SESSION["user"]);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    $changen = is_null($row["name"]) ? "Porfavor añada su nombre" : $row["name"];
    $changea = is_null($row["surname"]) ? "Porfavor añada su apellido" : $row["surname"];
    $changed = is_null($row["direction"]) ? "Porfavor añada su direcci&oacute;n de domicilio" : $row["direction"];
  } else{
    header("Location: login.php");
    $_SESSION["error"]="<p>Porfavor inicie sesion para acceder</p>";
    exit;
  }

  if (isset($_POST["datos"])) {
    $changen2 = !empty($_POST['changen']) ? $_POST['changen'] : null;
    $changea2 = !empty($_POST['changea']) ? $_POST['changea'] : null;
    $changed2 = !empty($_POST['changed']) ? $_POST['changed'] : null;

    if ($changen2 !== null) {
      $sql = $conn->prepare("UPDATE usuarios SET name = ? WHERE id = ?");
      $sql->bind_param("ss", $changen2, $row["id"]);
      $sql->execute();
    }
    if ($changea2 !== null) {
      $sql = $conn->prepare("UPDATE usuarios SET surname = ? WHERE id = ?");
      $sql->bind_param("ss", $changea2, $row["id"]);
      $sql->execute();
    }
    if ($changed2 !== null) {
      $sql = $conn->prepare("UPDATE usuarios SET direction = ? WHERE id = ?");
      $sql->bind_param("ss", $changed2, $row["id"]);
      $sql->execute();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }

  if (isset($_POST["pwdchange"])) {
    $sql = $conn->prepare("SELECT pwd FROM usuarios WHERE user=?");
    $sql->bind_param("s", $_SESSION["user"]);
    $sql->execute();
    $result = $sql->get_result();
    if ($row = $result->fetch_assoc()) {
      $pwd = $row["pwd"];
    }

    if (!is_null($pwd) && password_verify($_POST["changec"], $pwd)) {
      if ($_POST["changec1"] == $_POST["changec2"]) {
        $hash = password_hash($_POST["changec1"], PASSWORD_DEFAULT);
        $sql = $conn->prepare("UPDATE usuarios SET pwd=? WHERE user=?");
        $sql->bind_param("ss", $hash, $_SESSION["user"]);
        if ($sql->execute()) {
          echo "Contraseña actualizada con éxito.";
          session_destroy();
          header("Location: index.php");
          exit;
        } else {
          echo "Error al actualizar la contraseña: " . $sql->error;
        }
      } else {
        echo "Las nuevas contraseñas no coinciden.";
      }
    } elseif (isset($_SESSION["Google"])) {
      echo "Por favor inicie sesión con una cuenta que no sea de Google para cambiar la contraseña.";
    } else {
      echo "La contraseña actual es incorrecta o no se ha proporcionado.";
    }
  }
  ?>
</head>

<body class="login">
  <header>
    <?php include "header.php"; ?>
  </header>
  <main>
    <?php if (isset($_SESSION["error"])) : ?>
      <div>
        <?php
        echo $_SESSION["error"];
        unset($_SESSION["error"]);
        ?>
      </div>
    <?php endif; ?>

    <!-- Cerrado de sesion -->
    <form method="post" id="cerrar_sesion" class="input-text">
        <input type="submit" name="cerrar" value="Cerrar Sesión">
    </form>

    <!-- Cambio de nombre -->
    <div id="registro">
      <form method="post">
        <legend>
          <h2>Cambiar datos Personales</h2>
        </legend>
        <div class="input-text">
          <label for="changen">Cambiar nombre: </label>
          <input name="changen" type="text" placeholder="<?php echo $changen; ?>">
        </div>
        <div class="input-text">
          <label for="changea">Cambiar apellido: </label>
          <input name="changea" type="text" placeholder="<?php echo $changea; ?>">
        </div>
        <div class="input-text">
          <label for="changed">Cambiar direcci&oacute;n de domicilio: </label>
          <input name="changed" type="text" placeholder="<?php echo $changed; ?>">
        </div>
        <div class="input-text">
          <input type="submit" name="datos" value="Actualizar">
        </div>
      </form>

      <!-- Cambio de contraseña -->
      <form method="post">
        <legend>
          <h2>Cambiar la contraseña</h2>
        </legend>
        <div class="input-text">
          <label for="changec">Contrase&ntilde;a actual: </label>
          <input name="changec" type="password">
        </div>
        <div class="input-text">
          <label for="changec1">Nueva contrase&ntilde;a: </label>
          <input name="changec1" type="password">
        </div>
        <div class="input-text">
          <label for="changec2">Confirme nueva contrase&ntilde;a: </label>
          <input name="changec2" type="password">
        </div>
        <div class="input-text">
          <input type="submit" name="pwdchange" value="Cambiar contraseña">
        </div>
      </form>
    </div>
  </main>
  <footer></footer>
</body>

</html>
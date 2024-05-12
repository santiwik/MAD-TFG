<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
  <?php

  use Google\Service\Classroom\Name;

  session_start();
  include "connection.php";
  require_once 'vendor/autoload.php';
  require_once 'config.php';
  $client = new Google_Client();
  $client->setClientId($clientID);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($redirectUri);
  $client->addScope("email");
  $client->addScope("profile");

  if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    /*Registro google*/
    $sql = $conn->prepare("select user from usuarios where user=?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows != 1) {
      $sql = $conn->prepare("insert into usuarios(user,email) values(?,?)");
      $sql->bind_param("ss", $name, $email);
      $sql->execute();
    }
    $_SESSION["user"] = $name;
    $sql = $conn->prepare("select rol from usuarios where user=?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $result = $sql->get_result();
    if ($row = $result->fetch_assoc()) {
      $_SESSION["rol"] = $row["rol"];
    }
  }
  if (isset($_POST["cerrar"])) {
    session_destroy();
    header("Location:index.php");
  }
  if (isset($_SESSION["user"])) {
    $sql = $conn->prepare("select * from usuarios where user=?");
    $sql->bind_param("s", $_SESSION["user"]);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    if (is_null($row["name"])) {
      $changen = "Porfavor añada su nombre";
    } else {
      $changen = $row["name"];
    }
    if (is_null($row["surname"])) {
      $changea = "Porfavor añada su apellido";
    } else {
      $changea = $row["surname"];
    }
    if (is_null($row["direction"])) {
      $changed = "Porfavor añada su direcci&oacute;n de domicilio";
    } else {
      $changed = $row["direction"];
    }
    if (isset($_POST["datos"])) {
      $changen2 = !empty($_POST['changen']) ? $_POST['changen'] : null;
      $changea2 = !empty($_POST['changea']) ? $_POST['changea'] : null;
      $changed2 = !empty($_POST['changed']) ? $_POST['changed'] : null;

      if ($changen2 !== null || $changea2 !== null || $changed2 !== null) {
        // Al menos uno de los campos tiene datos, procede con la actualización

        // Verificar y actualizar el nombre
        if ($changen2 !== null) {
          $sql = $conn->prepare("UPDATE usuarios SET name = ? WHERE id = ?");
          $sql->bind_param("ss", $changen2, $row["id"]);
          if (!$sql->execute()) {
            echo "Error al actualizar el nombre: " . $sql->error;
          }
        }
        if ($changea2 !== null) {
          $sql = $conn->prepare("UPDATE usuarios SET surname = ? WHERE id = ?");
          $sql->bind_param("ss", $changea2, $row["id"]);
          if (!$sql->execute()) {
            echo "Error al actualizar el apellido: " . $sql->error;
          }
        }
        if ($changed2 !== null) {
          $sql = $conn->prepare("UPDATE usuarios SET direction = ? WHERE id = ?");
          $sql->bind_param("ss", $changed2, $row["id"]);
          if (!$sql->execute()) {
            echo "Error al actualizar la dirección: " . $sql->error;
          }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      } else {
        echo "No se proporcionaron datos para actualizar.";
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
    <!--Cerrado de sesion-->
    <form method="post">
      <input type="submit" name="cerrar" value="Cerrar Sesión">
    </form>
    <!--Cambio de nombre-->
    <form method="post">
      <legend>Cambiar datos Personales </legend>
      <label for="changen">Cambiar nombre: </label>
      <input name="changen" type="text" placeholder="<?php if (isset($changen)) {
                                                        echo $changen;
                                                      } ?>">
      <label for="changea">Cambiar apellido: </label>
      <input name="changea" type="text" placeholder="<?php if (isset($changea)) {
                                                        echo $changea;
                                                      } ?>">
      <label for="changed">Cambiar direcci&oacute;n de domicilio: </label>
      <input name="changed" type="text" placeholder="<?php if (isset($changed)) {
                                                        echo $changed;
                                                      } ?>">
      <input type="submit" name="datos" value="Actualizar">
    </form>
  </main>
  <footer>

  </footer>
</body>

</html>
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
  if(isset($_SESSION["user"])){
    $sql=$conn->prepare("select * from usuarios where user=?");
    $sql->bind_param("s", $_SESSION["user"]);
    $sql->execute();
    $result = $sql->get_result();
    $row=$result->fetch_assoc();
    if(is_null($row["name"])){
      $changen="Porfavor añada su nombre";
    } else{
      $changen=$row["name"];
    }
    if(is_null($row["surname"])){
      $changea="Porfavor añada su apellido";
    } else{
      $changea=$row["surname"];
    }
    if(is_null($row["direction"])){
      $changed="Porfavor añada su direcci&oacute;n de domicilio";
    } else{
      $changed=$row["direction"];
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
      <input name="changen" type="text" placeholder="<?php if(isset($changen)){ echo $changen; }?>">
      <label for="changea">Cambiar apellido: </label>
      <input name="changea" type="text" placeholder="<?php if(isset($changea)){ echo $changea; }?>">
      <label for="changed">Cambiar direcci&oacute;n de domicilio: </label>
      <input name="changea" type="text" placeholder="<?php if(isset($changed)){ echo $changed; }?>">
    </form>
  </main>
  <footer>

  </footer>
</body>

</html>
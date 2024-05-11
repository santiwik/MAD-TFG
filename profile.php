<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="css/style.css">
    <?php
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
    if($result->num_rows != 1){
      $sql = $conn -> prepare("insert into usuarios(user,email) values(?,?)");
      $sql->bind_param("ss", $name, $email);
      $sql->execute();
    }
    echo $email . '<br>';
    echo $name;
  }
  ?>
</head>
<body>
    <header>

    </header>
    <main>

    </main>
    <footer>

    </footer>
</body>
</html>
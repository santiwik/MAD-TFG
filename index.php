<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <?php
    require_once 'vendor/autoload.php';
    require_once 'config.php';
     
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");
    
    
    echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
    ?>
</head>
<body>
    
</body>
</html>
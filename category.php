<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";
    $sql = $conn -> prepare("select * from categoria where id = ?");
    $sql -> bind_param("s",$_SESSION["id"]);
    $sql -> execute();
    $result = $sql -> get_result();
    if($row=$result->fetch_assoc()){
        $name= $row["name"];
        $descripcion= $row["descripcion"];
    }else{
        header("Location: index.php");
        exit;
    }
    ?>
    <title><?php echo $name; ?></title>
</head>
<body>
    <header>
    <?php
        include "header.php";
        ?>
    </header>
    <main>
        
    </main>
    <footer>

    </footer>
</body>
</html>
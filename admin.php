<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar la pagina</title>
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";
    if(isset($_SESSION["rol"])){
        if($_SESSION["rol"]==1){
            header("Location: index.php");
            exit;
        }
    } else{
        header("Location: index.php");
        exit;
    }
    $sql = $conn->prepare("select * from roles where id=?");
    $sql ->bind_param("s",$_SESSION["rol"]);
    $sql->execute();
    $result=$sql->get_result();
    if($row=$result->fetch_assoc()){
        $namerol = $row["name"];
        $update = $row["Update"];
        $insert = $row["Insert"];
        $delete = $row["Delete"];
    }

    ?>
    
</head>

<body>
    <header>
        <?php
        include "header.php";
        ?>
    </header>
    <main id="no_flex">
        <div class="banner">
        <h1>Administrar Pagina</h1>
        </div>

    </main>
    <footer>

    </footer>
</body>

</html>
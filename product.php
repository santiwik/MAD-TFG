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
    $idprod = $_GET["idprod"];
    $sql = $conn->prepare("select * from producto where id = ?");
    $sql->bind_param("s", $idprod);
    $sql->execute();
    $result = $sql->get_result();
    if ($row = $result->fetch_assoc()) {
        $nameprod = $row["name"];
        $descripcionprod = $row["descripcion"];
        $precioprod = $row["precio"];
        $categoryprod = $row["category"];
        $sql = $conn->prepare("select name, id from categoria where id = ?");
        $sql->bind_param("s", $categoryprod);
        $sql->execute();
        $result = $sql->get_result();
        if ($row = $result->fetch_assoc()) {
            $namecat = $row["name"];
            $idcat = $row["id"];
        }
    } else {
        header("Location: index.php");
        exit;
    }
    if (isset($_POST["btncarrito"]) && isset($_SESSION["user"])) {
        if (isset($_SESSION["carro"][$idprod])) {
            $_SESSION["carro"][$idprod]["cant"]++;
        } else {
            $_SESSION["carro"][$idprod] = array(
                "idprod" => $idprod,
                "nameprod" => $nameprod,
                "precioprod" => $precioprod,
                "cant" => 1
            );
        }
        $_SESSION["msg"] = '<p id="msg">Se añadio correctamente al carrito</p>';
    } elseif (isset($_POST["btncarrito"]) && !isset($_SESSION["user"])) {
        $_SESSION["error"] = '<p>Porfavor inicie sesion para añadir al carrito</p>';
        header("Location: login.php");
        exit;
    }
    ?>
    <title><?php echo $nameprod; ?></title>
</head>

<body>
    <header>
        <?php
        include "header.php";
        ?>
    </header>
    <main id="no_flex">
        <p id="ruta"><a href="index.php"><i class="fa-solid fa-house"></i></a> > <a href="category.php?id=<?php echo $idcat; ?>"><?php echo $namecat; ?></a> > <?php echo $nameprod; ?> </p>
        <div id="producto">
            <img src="img/products/<?php echo $idprod; ?>.png" alt="Producto">
            <div>
                <div>
                    <h1><?php echo $nameprod; ?></h1>
                    <p id="precio"> <?php echo $precioprod; ?></p>
                </div>
                <form  method="post">
                    <input type="submit" name="btncarrito" value="<?php echo $precioprod; ?> - Añadir al Carrito">
                    
                </form>
                <?php
                    if (isset($_SESSION["msg"])) {
                        echo $_SESSION["msg"];
                        unset($_SESSION["msg"]);
                    }
                ?>
                <p><?php echo $descripcionprod; ?></p>
            </div>
        </div>
    </main>
    <footer>
    <?php 
    include "footer.php";
    ?>
    </footer>
</body>

</html>
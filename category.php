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
    if (isset($_GET["id"])) {
        $idc = $_GET["id"];
    } else {
        header("Location: index.php");
        exit;
    }
    $sql = $conn->prepare("select * from categoria where id = ?");
    $sql->bind_param("s", $idc);
    $sql->execute();
    $result = $sql->get_result();
    if ($row = $result->fetch_assoc()) {
        $namec = $row["name"];
        $descripcion = $row["descripcion"];
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
    <main id="no_flex">
        <div class="banner">
            <h1><?php echo $namec;?></h1>
        </div>
        <div id="indx">
            <?php
            $sql = $conn->prepare("select * from producto where category = ?");
            $sql ->bind_param("s", $idc);
            $sql->execute();
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                $idprod = $row["id"];
                $Nombre = $row["name"];
                $precio = $row["precio"];
                echo '<div class="product">';
                echo '<a href="product.php?idprod=' . $idprod . '">';
                echo '<img src="img/products/' . $idprod . '.png">';
                echo '<p>' . $Nombre . '</p>';
                echo '<p class="precio">' . $precio . '</p>';
                echo '</a>';
                echo '</div>';
            }

            ?>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>
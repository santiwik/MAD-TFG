<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";
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
        <h1>Nuevos productos</h1>
        </div>
        <div id="indx">
            <?php
            $sql = $conn->prepare("select * from producto order by id desc");
            $sql->execute();
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                $idprod = $row["id"];
                $Nombre = $row["name"];
                $precio = $row["precio"];

                echo '<a  class="product" href="product.php?idprod=' . $idprod . '">';
                echo '<img src="img/products/' . $idprod . '.png">';
                echo '<p>' . $Nombre . '</p>';
                echo '<p class="precio">' . $precio . '</p>';
                echo '</a>';
            }

            ?>
        </div>
    </main>
    <footer>
    <?php 
    include "footer.php";
    ?>
    </footer>
</body>

</html>
<?php
session_start();
include "connection.php";
if (isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] == 1) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
$sql = $conn->prepare("select * from roles where id=?");
$sql->bind_param("s", $_SESSION["rol"]);
$sql->execute();
$result = $sql->get_result();
if ($row = $result->fetch_assoc()) {
    $namerol = $row["name"];
    $update = $row["Update"];
    $insert = $row["Insert"];
    $delete = $row["Delete"];
}
if(isset($_POST["subrol"])){
    $sql = $conn->prepare("update usuarios set rol=? where id=?");
    $sql -> bind_param("ss",$_POST["Usuario"], $_POST["rol"]);
    $sql->execute();
    header()
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar la pagina</title>
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>
    <main id="no_flex">
        <div class="banner">
            <h1>Administrar Pagina</h1>
        </div>
        <?php
        if ($update != 0 && $insert != 0 && $delete != 0) {
            echo '<form method="post">';
            echo '    <legend><h2>Añadir Admins</h2></legend>';
            echo '    <label for="Usuario">El usuario </label>';
            echo '    <select name="Usuario">';
            $sql = $conn->prepare("select user, id from usuarios order by id");
            $sql->execute();
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["id"] . '">';
                echo $row["user"];
                echo '</option>';
            }
            echo '    </select>';
            echo '    <label for="rol"> será ascendido a</label>';
            echo '    <select name="rol">';
            $sql = $conn->prepare("select id, name from roles");
            $sql->execute();
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["id"] . '">';
                echo $row["name"];
                echo '</option>';
            }
            echo '    </select>';
            echo '    <input type="submit" name="subrol">';
            echo '</form>';

        }
        ?>
    </main>
    <footer>

    </footer>
</body>

</html>
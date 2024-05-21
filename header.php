<h1><a href="index.php">MAD-TFG</a></h1>
<div id="cat">
<?php
$sql = $conn->prepare("SELECT * FROM categoria");
$sql->execute();
$result = $sql->get_result();
while ($row = $result->fetch_assoc()) {
    $name = $row["name"];
    $id = $row["id"];
    echo '<a href="category.php?id='.$id.'">'.$name.'</a>';
}
?>
</div>
<?php
if (!isset($_SESSION["user"])) {
    echo '<div id="user">';
    echo '    <a href="login.php">Inicia sesión</a>';
    echo '    <a href="register.php">Regístrate</a>';
    echo '</div>';
} else {
    echo '<div id="user">';
    echo '    <a href="profile.php"><i class="fa-solid fa-user fa-2xl"></i></a>';
    echo '    <a href="carrito.php"><i class="fa-solid fa-cart-shopping fa-2xl"></i></a>';
    echo '</div>';
}
?>

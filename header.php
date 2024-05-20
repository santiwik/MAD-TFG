<h1><a href="index.php">MAD-TFG</a></h1>
<div id="cat">
<?php
$sql = $conn->prepare("SELECT * FROM categoria");
$sql->execute();
$result = $sql->get_result();
while ($row = $result->fetch_assoc()) {
    $name = htmlspecialchars($row["name"]);
    $id = htmlspecialchars($row["id"]);
    echo '<form method="post">';
    echo '<input type="submit" name="button_' . $id . '" value="' . $name . '">';
    echo '</form>';
}
foreach ($_POST as $key => $value) {
    if (strpos($key, 'button_') === 0) {
        $id = str_replace('button_', '', $key);
        $_SESSION["id"] = $id;
        header("Location: category.php");
        exit;
    }
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
    echo '</div>';
}
?>

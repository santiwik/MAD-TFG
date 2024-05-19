
<div class="header">
<h1><a href="index.php">MAD-TFG</a></h1>

<?php
if (!isset($_SESSION["user"])) {
    echo '<div>';
    echo '    <a href="login.php">Inicia sesión</a>';
    echo '    <a href="register.php">Regístrate</a>';
    echo '</div>';
} else {
    echo '<div>';
    echo '    <a href="profile.php"><i class="fa-solid fa-user fa-2xl"></i></a>';
    echo '</div>';
}
?>
</div>
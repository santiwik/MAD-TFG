<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar la página</title>
    <script src="https://kit.fontawesome.com/76fb5d8fe4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <?php
    session_start();
    include "connection.php";

    if (!isset($_SESSION["rol"])) {
        header("Location: index.php");
        exit;
    }

    if ($_SESSION["rol"] == 1) {
        header("Location: index.php");
        exit;
    }

    $sql = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $sql->bind_param("s", $_SESSION["rol"]);
    $sql->execute();
    $result = $sql->get_result();

    if ($row = $result->fetch_assoc()) {
        $namerol = $row["name"];
        $update = $row["Update"];
        $insert = $row["Insert"];
        $delete = $row["Delete"];
        $cat = $row["Cat"];
        $Prod = $row["Prod"];
    }

    if (isset($_POST["subrol"])) {
        if ($_POST["Usuario"] != 0 && $_POST["rol"] != 0) {
            $sql = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
            $sql->bind_param("ss", $_POST["rol"], $_POST["Usuario"]);
            if ($sql->execute()) {
                header("Location: admin.php");
                exit;
            } else {
                $_SESSION["msg"] = "<p>Error al actualizar el rol.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, rellene correctamente el formulario.</p>";
        }
    }

    // Añadir Categoría
    if (isset($_POST['add_category'])) {
        if (!empty($_POST['name']) && !empty($_POST['descripcion'])) {
            $sql = $conn->prepare("INSERT INTO categoria (name, descripcion) VALUES (?, ?)");
            $sql->bind_param("ss", $_POST['name'], $_POST['descripcion']);
            if ($sql->execute()) {
                $_SESSION["msg"] = "<p>Categoría añadida correctamente.</p>";
            } else {
                $_SESSION["msg"] = "<p>Error al añadir la categoría.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, complete todos los campos.</p>";
        }
    }

    // Eliminar Categoría
    if (isset($_POST['delete_category'])) {
        if ($_POST['category_id'] != 0) {
            $sql = $conn->prepare("DELETE FROM producto WHERE category = ?");
            $sql->bind_param("s", $_POST['category_id']);
            $sql->execute();
            $sql = $conn->prepare("DELETE FROM categoria WHERE id = ?");
            $sql->bind_param("s", $_POST['category_id']);
            if ($sql->execute()) {
                $_SESSION["msg"] = "<p>Categoría eliminada correctamente.</p>";
            } else {
                $_SESSION["msg"] = "<p>Error al eliminar la categoría.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, seleccione una categoría.</p>";
        }
    }

    // Actualizar Categoría
    if (isset($_POST['update_category'])) {
        if ($_POST['category_id'] != 0 && !empty($_POST['name']) && !empty($_POST['descripcion'])) {
            $sql = $conn->prepare("UPDATE categoria SET name = ?, descripcion = ? WHERE id = ?");
            $sql->bind_param("sss", $_POST['name'], $_POST['descripcion'], $_POST['category_id']);
            if ($sql->execute()) {
                $_SESSION["msg"] = "<p>Categoría actualizada correctamente.</p>";
            } else {
                $_SESSION["msg"] = "<p>Error al actualizar la categoría.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, complete todos los campos.</p>";
        }
    }

    // Añadir Producto
    if (isset($_POST['add_product'])) {
        if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['price']) && $_POST['category_id'] != 0) {
            if ($_FILES['image']['type'] == 'image/png') {
                $sql = $conn->prepare("INSERT INTO producto (name, descripcion, precio, category) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssss", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category_id']);
                if ($sql->execute()) {
                    $product_id = $sql->insert_id;
                    $target_path = "img/products/" . $product_id . ".png";
                    move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
                    $_SESSION["msg"] = "<p>Producto añadido correctamente.</p>";
                } else {
                    $_SESSION["msg"] = "<p>Error al añadir el producto.</p>";
                }
            } else {
                $_SESSION["msg"] = "<p>Por favor, suba una imagen PNG.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, complete todos los campos.</p>";
        }
    }

    // Eliminar Producto
    if (isset($_POST['delete_product'])) {
        if ($_POST['product_id'] != 0) {
            $sql = $conn->prepare("DELETE FROM producto WHERE id = ?");
            $sql->bind_param("s", $_POST['product_id']);
            if ($sql->execute()) {
                $image_path = "img/products/" . $_POST['product_id'] . ".png";
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $_SESSION["msg"] = "<p>Producto eliminado correctamente.</p>";
            } else {
                $_SESSION["msg"] = "<p>Error al eliminar el producto.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, seleccione un producto.</p>";
        }
    }

    // Actualizar Producto
    if (isset($_POST['update_product'])) {
        if ($_POST['product_id'] != 0 && !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['price']) && $_POST['category_id'] != 0) {
            $sql = $conn->prepare("UPDATE producto SET name = ?, descripcion = ?, precio = ?, category = ? WHERE id = ?");
            $sql->bind_param("sssss", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category_id'], $_POST['product_id']);
            if ($sql->execute()) {
                if ($_FILES['image']['type'] == 'image/png') {
                    $target_path = "img/products/" . $_POST['product_id'] . ".png";
                    move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
                }
                $_SESSION["msg"] = "<p>Producto actualizado correctamente.</p>";
            } else {
                $_SESSION["msg"] = "<p>Error al actualizar el producto.</p>";
            }
        } else {
            $_SESSION["msg"] = "<p>Por favor, complete todos los campos.</p>";
        }
    }
    ?>
</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>
    <main id="no_flex" class="admin">
        <div class="banner">
            <h1>Administrar Página</h1>
        </div>
        <div>
            <?php
            if (isset($_SESSION["msg"])) {
                echo $_SESSION["msg"];
                unset($_SESSION["msg"]);
            }

            // Admin Roles
            if ($_SESSION["rol"] == 2) {
                echo '<form method="post">';
                echo '    <legend><h2>Añadir Admins</h2></legend>';
                echo '    <label for="Usuario">El usuario</label>';
                echo '    <select name="Usuario">';
                echo '      <option value="0">Seleccione usuario</option>';

                $sql = $conn->prepare("SELECT user, id FROM usuarios ORDER BY id");
                $sql->execute();
                $result = $sql->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["user"] . '</option>';
                }
                echo '    </select>';
                echo '    <label for="rol"> será ascendido a</label>';
                echo '    <select name="rol">';
                echo '      <option value="0">Seleccione rol</option>';

                $sql = $conn->prepare("SELECT id, name FROM roles");
                $sql->execute();
                $result = $sql->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                }
                echo '    </select>';
                echo '    <input type="submit" name="subrol">';
                echo '</form>';
            }

            // Admin Categorías
            if ($cat != 0) {
                // Añadir Categoría
                if ($insert != 0) {
                    echo '<form method="post">';
                    echo '<legend><h2>Añadir Categoría de producto</h2></legend>';
                    echo '<label for="name">Nombre de la Categoría</label>';
                    echo '<input type="text" name="name" required>';
                    echo '<label for="descripcion">Descripción</label>';
                    echo '<textarea name="descripcion" required></textarea>';
                    echo '<input type="submit" name="add_category" value="Añadir Categoría">';
                    echo '</form>';
                }

                // Eliminar Categoría
                if ($delete != 0) {
                    echo '<form method="post">';
                    echo '<legend><h2>Eliminar Categoría</h2></legend>';
                    echo '<label for="category_id">Categoría</label>';
                    echo '<select name="category_id">';
                    echo '<option value="0">Seleccione una categoría</option>';

                    $sql = $conn->prepare("SELECT id, name FROM categoria");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<input type="submit" name="delete_category" value="Eliminar Categoría">';
                    echo '</form>';
                }

                // Actualizar Categoría
                if ($update != 0) {
                    echo '<form method="post">';
                    echo '<legend><h2>Actualizar Categoría</h2></legend>';
                    echo '<label for="category_id">Categoría</label>';
                    echo '<select name="category_id">';
                    echo '<option value="0">Seleccione una categoría</option>';

                    $sql = $conn->prepare("SELECT id, name FROM categoria");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<label for="name">Nuevo Nombre de la Categoría</label>';
                    echo '<input type="text" name="name" required>';
                    echo '<label for="descripcion">Nueva Descripción</label>';
                    echo '<textarea name="descripcion" required></textarea>';
                    echo '<input type="submit" name="update_category" value="Actualizar Categoría">';
                    echo '</form>';
                }
            }

            // Admin Productos
            if ($Prod != 0) {
                // Añadir Producto
                if ($insert != 0) {
                    echo '<form method="post" enctype="multipart/form-data">';
                    echo '<legend><h2>Añadir Producto</h2></legend>';
                    echo '<label for="name">Nombre del Producto</label>';
                    echo '<input type="text" name="name" required>';
                    echo '<label for="description">Descripción</label>';
                    echo '<textarea name="description" required></textarea>';
                    echo '<label for="price">Precio</label>';
                    echo '<input type="number" step="0.01" name="price" required>';
                    echo '<label for="category_id">Categoría</label>';
                    echo '<select name="category_id">';
                    echo '<option value="0">Seleccione una categoría</option>';

                    $sql = $conn->prepare("SELECT id, name FROM categoria");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<label for="image">Imagen (PNG)</label>';
                    echo '<input type="file" name="image" required>';
                    echo '<input type="submit" name="add_product" value="Añadir Producto">';
                    echo '</form>';
                }

                // Eliminar Producto
                if ($delete != 0) {
                    echo '<form method="post">';
                    echo '<legend><h2>Eliminar Producto</h2></legend>';
                    echo '<label for="product_id">Producto</label>';
                    echo '<select name="product_id">';
                    echo '<option value="0">Seleccione un producto</option>';

                    $sql = $conn->prepare("SELECT id, name FROM producto");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<input type="submit" name="delete_product" value="Eliminar Producto">';
                    echo '</form>';
                }

                // Actualizar Producto
                if ($update != 0) {
                    echo '<form method="post" enctype="multipart/form-data">';
                    echo '<legend><h2>Actualizar Producto</h2></legend>';
                    echo '<label for="product_id">Producto</label>';
                    echo '<select name="product_id">';
                    echo '<option value="0">Seleccione un producto</option>';

                    $sql = $conn->prepare("SELECT id, name FROM producto");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<label for="name">Nuevo Nombre del Producto</label>';
                    echo '<input type="text" name="name" required>';
                    echo '<label for="description">Nueva Descripción</label>';
                    echo '<textarea name="description" required></textarea>';
                    echo '<label for="price">Nuevo Precio</label>';
                    echo '<input type="number" step="0.01" name="price" required>';
                    echo '<label for="category_id">Nueva Categoría</label>';
                    echo '<select name="category_id">';
                    echo '<option value="0">Seleccione una categoría</option>';

                    $sql = $conn->prepare("SELECT id, name FROM categoria");
                    $sql->execute();
                    $result = $sql->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                    }

                    echo '</select>';
                    echo '<label for="image">Nueva Imagen (PNG)</label>';
                    echo '<input type="file" name="image">';
                    echo '<input type="submit" name="update_product" value="Actualizar Producto">';
                    echo '</form>';
                }
            }
            ?>
        </div>
    </main>
    <footer id="adminfoot">
        <?php
        include "footer.php";
        ?>
    </footer>
</body>

</html>
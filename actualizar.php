<?php

require_once "funciones.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    //Si no existe el id que se quiere eliminar volvemos al home
    header('Location: index.php');
    exit;
}

$PDO = new PDO("mysql:host=localhost; port=3306; dbname=crud_productos", "root", "");
$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$consulta = $PDO->prepare("SELECT * FROM productos WHERE id = :id");
$consulta->bindValue(':id', $id);
$consulta->execute();
$producto = $consulta->fetch(pdo::FETCH_ASSOC);

/* echo "<pre>";
var_dump($producto);
echo "</pre>"; */

$errores = [];
$nombre = $producto['nombre'];
$descripcion = $producto['descripcion'];
$precio = $producto['precio'];

if (count($_POST) > 0) { // Si se cargo la pagina mediante el boton de crear producto
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    if (!is_dir('img')) { // Si no existe la carpeta img se la crea
        mkdir('img');
    }
    $rutaImagen = '';
    $imagen = $_FILES['imagen'] ?? null;

    if ($imagen && $imagen['tmp_name']) {
        $rutaImagen = 'img/' . randomString(8) . '/' . $imagen["name"]; #ruta aleatoria de la imagen
        mkdir(dirname($rutaImagen));
        move_uploaded_file($imagen['tmp_name'], $rutaImagen);
    }

    if (!$_POST['nombre']) {
        $errores[] = "El nombre del producto es obligatorio";
    }

    if (!$_POST['precio'] or $_POST['precio'] < 0) {
        $errores[] = "El precio del producto es obligatorio y debe ser positivo";
    }

    if (count($errores) == 0) { // si no hay errores inserto el producto en la tabla

        $consulta = $PDO->prepare("UPDATE productos
                        SET nombre=:nombre,
                        imagen=:imagen,
                        precio=:precio,
                        descripcion = :descripcion
                        WHERE id=:id");
        $consulta->bindValue(":nombre", $nombre);
        $consulta->bindValue(":imagen", $rutaImagen);
        $consulta->bindValue(":precio", $precio);
        $consulta->bindValue(":descripcion", $descripcion);
        $consulta->bindValue(":id", $id);

        $consulta->execute();
        //Si se carga la consulta me debería enviar al home
        header('Location: index.php');

    }
}
?>

<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Producto</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
    <h1>Edición de <?=$producto['nombre']?></h1>
    <a href="index.php"><button type="button" class="btn btn-success btn-lg">Volver</button></a>

    <?php foreach ($errores as $error) {?>
      <div class="alert alert-danger" role="alert">
        <?=$error?>
      </div>
    <?php }?>

    <form method="post" enctype="multipart/form-data">

  <div class="mb-3">
    <img src="<?=$producto['imagen']?>" class="imagen editar" >
    <label>Imagen</label>
    <input type="file" class="form-control" id="imagen" name="imagen" >
  </div>
  <div class="mb-3">
    <label>Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre ?>">
  </div>
  <div class="mb-3">
    <label>Descripcion</label>
    <textarea class="form-control" id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>
  </div>
  <div class="mb-3">
    <label>Precio</label>
    <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $precio ?>">
  </div>

  <button type="submit" class="btn btn-primary">Editar Producto</button>
</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>
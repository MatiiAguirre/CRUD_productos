<?php

$PDO = new PDO("mysql:host=localhost; port=3306; dbname=crud_productos", "root", "");
$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$buscar = $_GET['buscar'] ?? '';

  if ($buscar) {
      $consulta = $PDO->prepare("SELECT * FROM productos WHERE nombre LIKE :nombre");
      $consulta->bindValue(':nombre', "%$buscar%");

  } else {
      $consulta = $PDO->prepare("SELECT * FROM productos ORDER BY id DESC");
  }

$consulta->execute();
$productos = $consulta->fetchAll(pdo::FETCH_ASSOC);
/* echo "<pre>";
var_dump($productos);
echo "</pre>"; */
?>

<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Productos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="../15.CRUD/css/styles.css">
   
  </head>
  <body>
    <h1>CRUD Productos</h1>
    <a href="crear.php"><button type="button" class="btn btn-success btn-lg">Crear</button></a>
<form>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Nombre del producto" aria-label="Recipient's username" aria-describedby="button-addon2" name="buscar">
  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
</div>
</form>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Imagen</th>
      <th scope="col">Nombre</th>
      <th scope="col">Descripcion</th>
      <th scope="col">Precio</th>
      <th scope="col">Fecha de creacion</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($productos as $i => $producto) {?>

    <tr>
        <th scope="row"><?=$i + 1?></th>
            <td>
                <img src="<?=$producto['imagen']?>" class="imagen" >
            </td>
                <td><?=$producto['nombre']?></td>
                <td><?=$producto['descripcion']?></td>
                <td><?=$producto['precio']?></td>
                <td><?=$producto['fecha_creacion']?></td>
        <td>

            <form method="GET" action="actualizar.php">
                <input type="hidden" name="id" value="<?=$producto['id']?>"/>
                <button type="submit" class="btn btn-info btn-sm">Actualizar</button>
            </form>

            <form method="POST" action="borrar.php">
                <input type="hidden" name="id" value="<?=$producto['id']?>"/>
                <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
            </form>

        </td>
    </tr>

    <?php }?>

    </tbody>

</table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  
</body>
</html>
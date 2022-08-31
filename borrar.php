<?php

$PDO = new PDO("mysql:host=localhost; port=3306; dbname=crud_productos", "root", "");
$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_POST['id'] ?? null;
if (!$id) {
    //Si no existe el id que se quiere eliminar volvemos al home
    header('Location: index.php');
    exit;
}

$consulta = $PDO->prepare("DELETE FROM productos WHERE id = :id");
$consulta->bindValue(':id', $id);
$consulta->execute();
//Una vez se borra el producto exitosamente se vuelve al home
header('Location: index.php');

/* echo "<pre>";
var_dump($productos);
echo "</pre>"; */
?>
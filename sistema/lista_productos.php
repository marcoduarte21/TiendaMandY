<?php

session_start();
if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

    header('location: ../');
  }
include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Lista de productos</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
		
    <h1><i class="fa-brands fa-codepen"></i> Lista de productos</h1>
    <a href="registro_producto.php" class="btn_new"><i class="fa-solid fa-plus"></i> <i class="fa-brands fa-codepen"></i> Añadir producto</a>

<form action="buscar_productos.php" method="GET" class="form_search">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
    <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>

</form>

    <table>
        <tr>
        <th>Código</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Existencia</th>
        <th>
        <?php

$query_marca = mysqli_query($conection, "SELECT id_marca, marca FROM marca WHERE estatus = 1 ORDER BY marca ASC");
$result_marca = mysqli_num_rows($query_marca);

?>

<select name="marca" id="search_marca">
<option value="" selected>Marca</option>
    <?php

    if ($result_marca > 0) {
        while ($marca = mysqli_fetch_array($query_marca)) {
    ?>
    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca'] ?></option>
    <?php
        }
    }
    ?>
</select>

        </th>
        <th>
        <?php

$query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
$result_proveedor = mysqli_num_rows($query_proveedor);

?>

<select name="proveedor" id="search_proveedor">
<option value="" selected>Proveedor</option>
    <?php

    if ($result_proveedor > 0) {
        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
    ?>
    <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor'] ?></option>
    <?php
        }
    }
    ?>
</select>

        </th>
        <th>Ilustración</th>
        <th>Acciones</th>
        </tr>

<?php

$sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM producto WHERE estatus = 1");

$result_register = mysqli_fetch_array($sql_register);
$total_registro = $result_register['total_registro'];

//registro por pagina
$por_pagina = 5;

if(empty($_GET['pagina'])){
    $pagina = 1;
}else{
    $pagina = $_GET['pagina'];
}

$desde = ($pagina - 1) * $por_pagina;
$total_paginas = ceil($total_registro / $por_pagina);


$query = mysqli_query($conection, "SELECT p.codproducto, p.descripcion, p.precio, p.existencia, m.marca, pr.proveedor, p.foto 
FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN marca m ON p.marca = m.id_marca WHERE p.estatus = 1 ORDER BY p.codproducto DESC LIMIT $desde, $por_pagina");
    mysqli_close($conection);

$result = mysqli_num_rows($query);

if ($result > 0) {

    while ($data = mysqli_fetch_array($query)) {
        
        if($data['foto'] != 'producto.jpg'){
            $foto = 'img/uploads/' . $data['foto'];
        }else{
            $foto = 'img/' . $data['foto'];
        }

?>

        <tr class="row<?php echo $data["codproducto"] ?>">
            <td><?php echo $data["codproducto"] ?></td>
            <td><?php echo $data["descripcion"] ?></td>
            <td class="celPrecio"><?php echo $data["precio"] ?></td>
            <td class="celExistencia"><?php echo $data["existencia"] ?></td>
            <td><?php echo $data["marca"] ?></td>
            <td><?php echo $data["proveedor"] ?></td>
            <td class="img_producto"> <img src="<?php echo $foto; ?>" alt="<?php echo $data["descripcion"] ?>"></td>
            
            <td>
                <a class="link_add add_product" product="<?php echo $data["codproducto"] ?>" href="#"><i class="fa-solid fa-plus"></i> Agregar</a>
                |
                <a class="link_edit" href="editar_producto.php?id=<?php echo $data["codproducto"] ?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                |
                <a class="link_delete delete_product" href="#" product="<?php echo $data["codproducto"] ?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
                <?php 
    }
}?>
            </td>
        </tr>
    </table>
    <div class="paginador">
    <ul>
            <?php
            if ($pagina != 1) {

            ?>
            <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
            <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-caret-left fa-lg"></i></a></li>
<?php
            }
for($i=1; $i <= $total_paginas; $i++){
    if($i == $pagina){
        echo '<li class="pageSelected">'.$i.'</li>';
    } else {
        echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
    }
}
            if ($pagina != $total_paginas) {
            ?>
   
            <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-caret-right fa-lg"></i></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
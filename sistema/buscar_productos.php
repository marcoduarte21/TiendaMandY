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

    <?php

    $busqueda = '';
    $search_proveedor = '';
    if(empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor']) && empty($_REQUEST['marca'])){
        header("location: lista_productos.php");
    }

    if(!empty($_REQUEST['busqueda'])){
        $busqueda = strtolower($_REQUEST['busqueda']);
        $where = "(p.codproducto LIKE '%$busqueda%' OR
        p.descripcion LIKE '%$busqueda%')
        AND
        p.estatus = 1 ";
        $buscar = 'busqueda=' . $busqueda;
    }

    if(!empty($_REQUEST['proveedor'])){
        $search_proveedor = $_REQUEST['proveedor'];
        $where = "(p.proveedor LIKE $search_proveedor)
        AND
        p.estatus = 1";
        $buscar = 'proveedor=' . $search_proveedor;
    }

    if(!empty($_REQUEST['marca'])){
        $search_marca = $_REQUEST['marca'];
        $where = "(p.marca LIKE $search_marca)
        AND
        p.estatus = 1";
        $buscar = 'marca=' . $search_marca;
    }


    ?>
		
    <h1>Lista de productos</h1>
    <a href="registro_producto.php" class="btn_new"><i class="fa-solid fa-plus"></i> <i class="fa-brands fa-codepen"></i> Añadir producto</a>

<form action="buscar_productos.php" method="GET" class="form_search">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda ?>">
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

$marc = 0;
if(!empty($_REQUEST['marca'])){
    $marc = $_REQUEST['marca'];
}

$query_marca = mysqli_query($conection, "SELECT id_marca, marca FROM marca WHERE estatus = 1 ORDER BY marca ASC");
$result_marca = mysqli_num_rows($query_marca);

?>

<select name="marca" id="search_marca">
<option value="" selected>Marca</option>
    <?php

    if ($result_marca > 0) {
        while ($marca = mysqli_fetch_array($query_marca)) {
            if ($marc == $marca['id_marca']) {


                ?>
                <option value="<?php echo $marca['id_marca']; ?>" selected><?php echo $marca['marca'] ?></option>
                <?php
                        }else{
    ?>
    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca'] ?></option>
    <?php
                        }
        }
    }
    ?>
</select>

        </th>
        <th>
        <?php

        $pro = 0;
        if(!empty($_REQUEST['proveedor'])){
            $pro = $_REQUEST['proveedor'];
        }

$query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
$result_proveedor = mysqli_num_rows($query_proveedor);

?>

<select name="proveedor" id="search_proveedor">
    <option value="" selected>Proveedor</option>
    <?php

    if ($result_proveedor > 0) {
        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
            if ($pro == $proveedor['codproveedor']) {


    ?>
    <option value="<?php echo $proveedor['codproveedor']; ?>" selected><?php echo $proveedor['proveedor'] ?></option>
    <?php
            }else{
                ?>
                <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor'] ?></option>
                <?php 
            }
        }
    }
    ?>
</select>

        </th>
        <th>Ilustración</th>
        <th>Acciones</th>
        </tr>

<?php

$sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM producto as p WHERE $where");

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
FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor INNER JOIN marca m ON p.marca = m.id_marca WHERE $where ORDER BY p.codproducto DESC LIMIT $desde, $por_pagina");
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
    <?php
        if($total_paginas != 0){

    ?>
    <div class="paginador">
        <ul>
            <?php
            if ($pagina != 1) {

            ?>
            <li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>">|<</a></li>
            <li><a href="?pagina=<?php echo $pagina - 1; ?>&<?php echo $buscar; ?>"><<</a></li>
<?php
            }
for($i=1; $i <= $total_paginas; $i++){
    if($i == $pagina){
        echo '<li class="pageSelected">'.$i.'</li>';
    } else {
        echo '<li><a href="?pagina=' . $i . '&'.$buscar.'">' . $i . '</a></li>';
    }
}
            if ($pagina != $total_paginas) {
            ?>
   
            <li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar; ?>">>></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>">>|</a></li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php } ?>
	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
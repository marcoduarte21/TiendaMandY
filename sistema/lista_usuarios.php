<?php

session_start();
if($_SESSION['idrol'] != 1){

  header('location: ../');
}

include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Lista de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
		
    <h1><i class="fa-solid fa-users-line"></i>  Lista de usuarios</h1>
    <a href="registro_usuario.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Añadir usuario</a>

<form action="buscar_usuario.php" method="GET" class="form_search">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
    <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>

</form>

    <table>
        <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Acciones</th>
        </tr>

<?php

$sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuario WHERE estatus = 1");

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


$query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN
    rol r ON u.rol = r.idrol WHERE estatus = 1 ORDER BY u.idusuario ASC LIMIT $desde, $por_pagina");
    mysqli_close($conection);

$result = mysqli_num_rows($query);

if($result > 0){

    while($data = mysqli_fetch_array($query)){

 ?>

        <tr>
            <td><?php echo $data["idusuario"] ?></td>
            <td><?php echo $data["nombre"] ?></td>
            <td><?php echo $data["correo"] ?></td>
            <td><?php echo $data["usuario"] ?></td>
            <td><?php echo $data["rol"] ?></td>
            <td>
                <a class="link_edit" href="editar_usuario.php?id=<?php echo $data["idusuario"] ?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                <?php
        if ($data["idusuario"] != 1) {
                ?>
                |
                <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data["idusuario"] ?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
                <?php }  ?>
            </td>
        </tr>
<?php
    }
}

?>
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
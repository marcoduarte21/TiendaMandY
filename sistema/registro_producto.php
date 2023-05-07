<?php
session_start();
if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

    header('location: ../');
  }

include "../conexion.php";


  if(!empty($_POST)){


  $alert = '';
  if(empty($_POST['proveedor']) || empty($_POST ['producto']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0 || empty($_POST['marca'])){

    $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
  }else{

    $proveedor = $_POST['proveedor'];
    $marca = $_POST['marca'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['idUser'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type_foto = $foto['type'];
        $url_temp = $foto['tmp_name'];

        $imgProducto = 'producto.jpg';

        if($nombre_foto != ''){

            $destino = 'img/uploads/';
            $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        }

      $query_insert = mysqli_query($conection, "INSERT INTO producto(proveedor, marca, descripcion, precio, existencia, usuario_id, foto) VALUES('$proveedor', $marca ,'$producto', $precio, $cantidad, '$usuario_id', '$imgProducto') ");
      
      if($query_insert){
        if($nombre_foto != ''){
      move_uploaded_file($url_temp, $src);
        }
        $alert = '<p class="msg_save">El producto se ha creado correctamente.</p>';
      }else{
        $alert = '<p class="msg_error">Error al crear el producto.</p>';
      }
    }
    
    }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Registro Producto</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
        
		<div class="form_register">
    <h1><i class="fa-brands fa-codepen"></i> Registro Producto</h1>
    <hr>
    <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

    <form action="" method="post" enctype="multipart/form-data">
    <label for="proveedor">Proveedor: </label>

    <?php

    $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
    $result_proveedor = mysqli_num_rows($query_proveedor);
    
    ?>

    <select name="proveedor" id="proveedor">
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
    <label for="marca">Marca: </label>

    <?php

    $query_marca = mysqli_query($conection, "SELECT id_marca, marca FROM marca WHERE estatus = 1 ORDER BY marca ASC");
    $result_marca = mysqli_num_rows($query_proveedor);
    
    ?>

    <select name="marca" id="marca">
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
    <label for="producto">Producto: </label>
    <input type="text" name="producto" id="producto" placeholder="Nombre del producto">
    <label for="precio">Precio: </label>
    <input type="number" name="precio" id="precio" placeholder="Precio del producto">
    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" id="cantidad" placeholder="cantidad del producto">
    
    <div class="photo">
	<label for="foto">Foto</label>
        <div class="prevPhoto">
        <span class="delPhoto notBlock">X</span>
        <label for="foto"></label>
        </div>
        <div class="upimg">
        <input type="file" name="foto" id="foto">
        </div>
        <div id="form_alert"></div>
</div>

<button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Crear Producto</button>
    </form>

        </div>

	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
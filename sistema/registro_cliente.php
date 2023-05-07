<?php
session_start();

include "../conexion.php";

  if(!empty($_POST)){
  $alert = '';
  if(empty($_POST['nombre']) || empty($_POST['telefono']) ||empty($_POST['direccion']) ){

    $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
  }else{

    $nit = $_POST['nit'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $usuario_id = $_SESSION['idUser'];

    $result = 0;

      $query_insert = mysqli_query($conection, "INSERT INTO cliente(nombre, correo, telefono, direccion, usuario_id) VALUES('$nombre', '$correo' ,'$telefono', '$direccion', '$usuario_id') ");
      
      if($query_insert){
        $alert = '<p class="msg_save">El cliente se ha creado correctamente.</p>';
      }else{
        $alert = '<p class="msg_error">Error al crear el cliente.</p>';
      }
    }
    
    }
    
  

  mysqli_close($conection);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Registro Cliente</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
        
		<div class="form_register">
    <h1><i class="fa-solid fa-person-circle-plus"></i> Registro de cliente</h1>
    <hr>
    <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

    <form action="" method="post">
    <label for="nombre">Nombre: </label>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
    <label for="correo">Correo: </label>
    <input type="email" name="correo" id="correo" placeholder="Correo electrónico">
    <label for="telefono">Teléfono: </label>
    <input type="number" name="telefono" id="telefono" placeholder="Número de teléfono">
    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" id="direccion" placeholder="Dirección completa">
    
    <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Crear Cliente</button>
    </form>

        </div>

	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
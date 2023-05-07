<?php

session_start();
if($_SESSION['idrol'] != 1){

  header('location: ../');
}

include "../conexion.php";

  if(!empty($_POST)){
  $alert = '';
  if(empty($_POST['nombre']) || empty($_POST['correo']) ||empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])){

    $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
  }else{
    
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $user = $_POST['usuario'];
    $clave = md5($_POST['clave']);
    $rol = $_POST['rol'];


    $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = 'user' OR correo = '$email'");
    //lo que devuelva query lo ingresa en un array
    
    $result = mysqli_fetch_array($query);

    // si es mayor a 0 es porque si hay registro
    if($result > 0){
      $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
    }else{
      $query_insert = mysqli_query($conection, "INSERT INTO usuario(nombre, correo, usuario, clave, rol) VALUES('$nombre', '$email', '$user', '$clave', '$rol') ");

      if($query_insert){
        $alert = '<p class="msg_save">El usuario se ha creado correctamente.</p>';
      }else{
        $alert = '<p class="msg_error">Error al crear el usuario.</p>';
      }
    }
  }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Registro Usuario</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
        
		<div class="form_register">
    <h1><i class="fa-solid fa-user-plus"></i> Registro Usuario</h1>
    <hr>
    <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

    <form action="" method="post">
    <label for="nombre">Nombre: </label>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
    <label for="correo">Correo electrónico: </label>
    <input type="email" name="correo" id="correo" placeholder="Correo electrónico">
    <label for="usuario">Usuario: </label>
    <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario">
    <label for="clave">Contraseña</label>
    <input type="password" name="clave" id="clave" placeholder="Contraseña de acceso">
    <label for="rol">Rol</label>

    <?php

    // consulta de roles

    $query_rol = mysqli_query($conection, "SELECT * FROM rol");
    mysqli_close($conection);
    $result_rol = mysqli_num_rows($query_rol); // # filas que devuelve el query

    ?>

    <select name="rol" id="rol">

    <?php

if ($result_rol > 0) {
  while ($rol = mysqli_fetch_array($query_rol)) {
    ?>
  <option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
  <?php
  }
}
    ?>
    </select>

    <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Crear Usuario</button>
    </form>

        </div>

	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
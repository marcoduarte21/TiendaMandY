<?php

session_start();


include "../conexion.php";

  if(!empty($_POST)){
  $alert = '';
  if(empty($_POST['nombre']) || empty($_POST['telefono']) ||empty($_POST['direccion']) ){

    $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
  }else{

    $idCliente = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = ($_POST['direccion']);

        $result = 0;

    // si es mayor a 0 es porque si hay registro
    if($result > 0){
      $alert = '<p class="msg_error">El nit ya existe.</p>';
    }else{

        if($nit == ''){
                $nit = 0;
        }

    $sql_update = mysqli_query($conection, "UPDATE cliente SET nombre = '$nombre', telefono = '$telefono', direccion = '$direccion' WHERE idcliente= $idCliente");


      if($sql_update){
        $alert = '<p class="msg_save">El cliente se ha actualizado correctamente.</p>';
      }else{
        $alert = '<p class="msg_error">Error al actualizar el cliente.</p>';
      }
    }
  }
  
  }



  // Mostrar datos del id solo si no esta vacio
  if(empty($_REQUEST['id'])){

    header('Location: lista_clientes.php');
    mysqli_close($conection);
  }

$idcliente = $_REQUEST['id'];

$sql = mysqli_query($conection, "SELECT * FROM cliente WHERE
idcliente = $idcliente and estatus = 1");
mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if($result_sql ==0){
    header('Location: lista_usuarios.php');
}else{

    while($data = mysqli_fetch_array($sql)){
        $idcliente = $data['idcliente'];
        $nombre = $data['nombre'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
        
		<div class="form_register">
    <h1>Actualizar Cliente</h1>
    <hr>
    <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

    <form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
    <label for="nit">NIT: </label>
    <input type="number" name="nit" id="nit" placeholder="Número de NIT" value="<?php echo $nit; ?>">
    <label for="nombre">Nombre: </label>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php echo $nombre; ?>">
    <label for="telefono">Teléfono: </label>
    <input type="number" name="telefono" id="telefono" placeholder="Número de teléfono" value="<?php echo $telefono; ?>">
    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" id="direccion" placeholder="Dirección completa" value="<?php echo $direccion; ?>">
    
    <input type="submit" value="Actualizar Cliente" class="btn_save">
    </form>

        </div>

	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
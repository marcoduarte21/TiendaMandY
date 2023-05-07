<?php 

    session_start();
    if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

        header('location: ../');
      }
    
    include "../conexion.php";

    if(!empty($_POST)){
        $alert = '';
        if(!empty($_POST['marca'])){

            $marca = strtoupper($_POST['marca']);
            $usuario = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO marca(marca, idusuario) VALUES ('$marca', $usuario)");

            if($query_insert){

                $alert = '<p class="msg_save">La marca se ha creado correctamente.</p>';
            }else{
              $alert = '<p class="msg_error">Error al crear la marca.</p>';
            }
        }else{
            $alert = '<p class="msg_error">El campo marca es obligatorio.</p>';
        }
        mysqli_close($conection);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro marca</title>
    <?php include "includes/scripts.php"; ?>
</head>
<body>
<?php include "includes/header.php"?>
	<section id="container">
        
		<div class="form_register">
    <h1><i class="fa-solid fa-tag"></i> Registro Marca</h1>
    <hr>
    <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

    <form action="" method="post">
    <label for="marca">Marca: </label>
    <input type="text" name="marca" id="marca" placeholder="Nombre de la marca">
    
    <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Guardar marca</button>
    </form>

        </div>

	</section>

	<?php include "includes/footer.php"?>
</body>
</html>
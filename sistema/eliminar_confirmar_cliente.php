<?php
session_start();
if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST)){

    if(empty($_POST['idcliente'])){
        header("location: lista_usuarios.php");
        mysqli_close($conection);
    }
    
    $idcliente = $_POST['idcliente'];

    //eliminar
    //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario = $idusuario")


    $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idcliente");
    mysqli_close($conection);
    if($query_delete){
        header("location: lista_clientes.php");
    }else{
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])){
    header("location: lista_clientes.php");
    mysqli_close($conection);
}else{

    $idcliente = $_REQUEST['id'];

    $query = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $idcliente");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){

            $nombre = $data['nombre'];
            $correo = $data['correo'];
            
        }
    }else{
        header("location: lista_clientes.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
		<div class="data_delete">
    <h2>¿Está seguro que desea eliminar el siguiente registro de cliente?</h2>
    <p>Correo: <span><?php echo $correo ?></span></p>
    <p>Nombre del Cliente: <span><?php echo $nombre;?></span></p>
    

    <form action="" method="post">

    <input type="hidden" name="idcliente" value="<?php echo $idcliente;?>">
    <a href="lista_clientes.php" class="btn_cancel">Cancelar</a>
    <input type="submit" value="Eliminar" class="btn_ok">

    </form>

        </div>
	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
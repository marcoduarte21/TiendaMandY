<?php
session_start();
if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST)){

    if(empty($_POST['idproveedor'])){
        header("location: lista_proveedores.php");
        mysqli_close($conection);
    }
    
    $idproveedor = $_POST['idproveedor'];

    //eliminar
    //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario = $idusuario")


    $query_delete = mysqli_query($conection, "UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor");
    mysqli_close($conection);
    if($query_delete){
        header("location: lista_proveedores.php");
    }else{
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])){
    header("location: lista_proveedores.php");
    mysqli_close($conection);
}else{

    $idproveedor = $_REQUEST['id'];

    $query = mysqli_query($conection, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){

            $proveedor = $data['proveedor'];
            
        }
    }else{
        header("location: lista_proveedores.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>Eliminar Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"?>
	<section id="container">
		<div class="data_delete">
    <h2>¿Está seguro que desea eliminar el siguiente registro?</h2>
    <p>Proveedor: <span><?php echo $proveedor;?></span></p>
    
    
    <form action="" method="post">

    <input type="hidden" name="idproveedor" value="<?php echo $idproveedor;?>">
    <a href="lista_proveedores.php" class="btn_cancel">Cancelar</a>
    <input type="submit" value="Eliminar" class="btn_ok">

    </form>

        </div>
	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
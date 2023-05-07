<?php
session_start();
include "../conexion.php";

$busqueda = '';
$fecha_de = '';
$fecha_a = '';


//validar si existe y si va vacio se redirecciona
if(isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])){

    if($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == ''){
        header("location: index.php");
    }
}

    if(!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])){
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a = $_REQUEST['fecha_a'];

    $buscar = '';

    if($fecha_de > $fecha_a){
        header("location: index.php");

    }else if($fecha_de == $fecha_a){
        $where = "fecha LIKE '$fecha_de%'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
        
    }else{ 
        //inicializar la hora para que busque desde un inicio hasta el final
        $f_de = $fecha_de . ' 00:00:00';
        $f_a = $fecha_a . ' 23:59:59';
        //BETWEEN: busca un rango
        $where = "fecha BETWEEN '$f_de' AND '$f_a'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php" ?>
	<title>M&Y</title>
</head>
<body>
	<?php
	include "includes/header.php";

	//Datos empresa
	$nombreEmpresa = '';
	$razonSocial = '';
	$telEmpresa = '';
	$emailEmpresa = '';
	$dirEmpresa = '';
	$iva = ';';

	$query_empresa = mysqli_query($conection, "SELECT * FROM configuracion");
	$row_empresa = mysqli_num_rows($query_empresa);
	if($row_empresa > 0){
		while($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)){
			$nombreEmpresa = $arrInfoEmpresa['nombre'];
			$telEmpresa = $arrInfoEmpresa['telefono'];
			$emailEmpresa = $arrInfoEmpresa['email'];
			$dirEmpresa = $arrInfoEmpresa['direccion'];
			$iva = $arrInfoEmpresa['iva'];
		}
	}

	$query = mysqli_query($conection, "SELECT SUM(monto) as ganancia FROM ganancias WHERE $where and estatus = 1");

$result = mysqli_num_rows($query);

if($result > 0){
    $dataVenta = mysqli_fetch_assoc($query);
}

$formatoMoneda_ganancia = number_format($dataVenta['ganancia'], 2);

	$query_dash = mysqli_query($conection, "CALL dataDashboard();");
	$result_das = mysqli_num_rows($query_dash);
	if($result_das > 0){
		$data_dash = mysqli_fetch_assoc($query_dash);
		mysqli_close($conection);
	}
	
	?>
	<section id="container">
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">Panel de control</h1>
			</div>
			<div class="dashboard">
				<?php 
				if($_SESSION['idrol'] == 1 || $_SESSION['idrol'] == 2){
				?>
				<a href="lista_usuarios.php"><i class="fas fa-users"></i>
				<p>
					<strong>Usuarios</strong><br>
					<span><?php echo $data_dash['usuarios'] ?></span>
				</p>
				</a>
				<?php } ?>
				<a href="lista_clientes.php"><i class="fas fa-user"></i>
				<p>
					<strong>Clientes</strong><br>
					<span><?php echo $data_dash['clientes'] ?></span>
				</p>
				<?php 
				if($_SESSION['idrol'] == 1 || $_SESSION['idrol'] == 2){
				?>
				</a>
				<a href="lista_proveedores.php"><i class="far fa-building"></i>
				<p>
					<strong>Proveedores</strong><br>
					<span><?php echo $data_dash['proveedores'] ?></span>
				</p>
				</a>
				<?php } ?>
				<a href="lista_productos.php"><i class="fas fa-cubes"></i>
				<p>
					<strong>Productos</strong><br>
					<span><?php echo $data_dash['productos'] ?></span>
				</p>
				</a>
				<a href="ventas.php"><i class="fa-solid fa-money-bill"></i>
				<p>
					<strong>Ventas del día</strong><br>
					<span><?php echo $data_dash['ventas'] ?></span>
				</p>
				</a>
			</div>
			</div>

		<div class="divContainer">
		<div>
				<h1 class="titlePanelControl">Ventas</h1>
			</div>
			<div>
			<h5>Buscar por fecha</h5>
<form action="" method="get" class="form_search_date">
<label>De: </label>
<input type="date" name="fecha_de" id="fecha_de" required>
<label>A: </label>
<input type="date" name="fecha_a" id="fecha_a" required>
<button type="submit" class="btn_view"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
</div>

<div class="dashboard">
<a href="#"><i class="fa-solid fa-money-bill"></i>

				<p>
					<strong>Ventas por rango</strong><br>
					<span><?php echo '₡'.$formatoMoneda_ganancia; ?></span><br>
					<strong><?php echo $fecha_de .' hasta '.$fecha_a ?></strong>
				</p>
				</a>
		</div>
		</div>


		<div class="divInfoSistem">
			<div>
				<h1 class="titlePanelControl">Configuración</h1>
			</div>
			<div class="containerPerfil">
			<div class="containerDataUser">
				<div class="logoUser">
					<img src="img/logoUser.png">
				</div>
				<div class="divDataUser">

					<h4>Información personal</h4>
					<div>
						<label>Nombre: </label> <span><?php echo $_SESSION['nombre']; ?></span>
					</div>
					<div>
						<label>Correo: </label> <span><?php echo $_SESSION['correo']; ?></span>
					</div>
					<h4>Datos Usuario</h4>
					<div>
						<label>Rol: </label> <span><?php echo $_SESSION['rol']; ?></span>
					</div>
					<div>
						<label>Usuario: </label> <span><?php echo $_SESSION['usuario']; ?></span>
					</div>
					
					<h4>Cambiar contraseña</h4>
					<form action="" method="post" name="frmChangePass" id="frmChangePass">

					<div>
					<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
					</div>
					<div>
						<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
					</div>
					<div>
						<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar contraseña" required>
					</div>
					<div class="alertChangePass" style="display: none;"></div>
					<div>
						<button type="submit" class="btn_save btnChangePass"><i class="fa-solid fa-key"></i> Cambiar contraseña</button>
					</div>
					</form>
				</div>
			</div>
			<?php if($_SESSION['idrol'] == 1){ ?>
			<div class="containerDataEmpresa">
			<div class="logoEmpresa">
					<img src="img/m&Y.png">
				</div>
				<h4>Datos de la empresa</h4>

				<form action="" method="post" name="frmEmpresa" id="frmEmpresa">
				<input type="hidden" name="action" value="updateDataEmpresa">

					<div>
					<label>Nombre:</label><input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="<?php echo $nombreEmpresa ?>" required>
					</div>
					<div>
					<label>Teléfono:</label><input type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de teléfono" value="<?php echo $telEmpresa ?>" required>
					</div>
					<div>
					<label>Correo electrónico:</label><input type="text" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo electrónico" value="<?php echo $emailEmpresa ?>" required>
					</div>
					<div>
					<label>Dirección:</label><input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la empresa" value="<?php echo $dirEmpresa ?>" required>
					</div>
					<div>
					<label>IVA (%):</label><input type="text" name="txtIva" id="txtIva" placeholder="Impuesto al valor agregado (IVA)" value="<?php echo $iva ?>" required>
					</div>

					<div class="alertFormEmpresa" style="display: none;"></div>
					<div>
						<button type="submit" class="btn_save btn"><i class="fa-solid fa-floppy-disk"></i> Guardar datos</button>
					</div>
					</form>
			</div>
			<?php } ?>
			</div>
		</div>

	</section>

	<?php include "includes/footer.php"?>

</body>
</html>
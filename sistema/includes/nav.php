<nav>
			<ul>
				<li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
				<?php
	
                if ($_SESSION['idrol'] == 1) {
                ?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-user"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fa-solid fa-users-line"></i> Lista de Usuarios</a></li>
					</ul>
				</li>

				<?php
                }
				?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-users"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php"><i class="fa-solid fa-person-circle-plus"></i> Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="fa-solid fa-users"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				<?php 
				if($_SESSION['idrol'] == 1 || $_SESSION['idrol'] == 2){
				?>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-building-user"></i> Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fa-solid fa-plus"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="fa-solid fa-building"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
					<?php }
                if ($_SESSION['idrol'] == 1 || $_SESSION['idrol'] == 2) {
                    ?>
				<li class="principal">
					<a href="#"><i class="fa-brands fa-codepen"></i> Productos</a>
					<ul>
						<li><a href="registro_producto.php"><i class="fa-solid fa-plus"></i> Nuevo Producto</a></li>
						<li><a href="lista_productos.php"><i class="fa-brands fa-codepen"></i> Lista de Productos</a></li>
						<li><a href="registro_marca.php"><i class="fa-solid fa-plus"></i> Registro de marca</a></li>
					</ul>
				</li>
				<?php }?>
					<li><a href="lista_deudores.php"><i class="fa-solid fa-money-bill-1-wave"></i> Lista de deudores</a></li>
				<li class="principal">
					<a href="#"><i class="fa-solid fa-file-invoice-dollar"></i> Ventas</a>
					<ul>
						<li><a href="nueva_venta.php"><i class="fa-solid fa-plus"></i> Nueva Venta</a></li>
						<li><a href="ventas.php"><i class="fa-solid fa-file-invoice-dollar"></i> Lista de Ventas Contado</a></li>
						<li><a href="ventasCredito.php"><i class="fa-solid fa-file-invoice-dollar"></i> Lista de Ventas Credito</a></li>
					</ul>
				</li>
			</ul>
		</nav>
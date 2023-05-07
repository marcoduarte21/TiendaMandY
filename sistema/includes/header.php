<?php

    if(empty($_SESSION['active'])){
        header('location: ../');
    }

?>


<header>
		<div class="header">
			
		<h1><i class="fa-solid fa-shop"></i> Tienda M&Y</h1>
    <div class="optionsBar">
        <p>Costa Rica, <?php echo fechaC(); ?> </p>
        <span>|</span>
        <span id="photoUser"><i class="fa-solid fa-circle-user"></i></span>
        <p id="usuario"><?php echo $_SESSION['usuario']; ?></p>
        <a href="salir.php"><i class="fa-solid fa-right-from-bracket"></i></a>
			</div>
		</div>
		<?php include "nav.php"?>
	</header>
	<div class="modal">
		<div class="bodyModal">
		</div>
	</div>
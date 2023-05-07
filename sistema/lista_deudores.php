<?php

session_start();
if($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2){

  header('location: ../');
}

include "../conexion.php";


$queryAdeudoTotal = mysqli_query($conection, "SELECT SUM(monto_pendiente) as monto_pendiente FROM deudores WHERE estatus = 1");

$resultAdeudo = mysqli_num_rows($queryAdeudoTotal);

if($resultAdeudo > 0){
    $AdeudoTotal = mysqli_fetch_assoc($queryAdeudoTotal);
}


$show_adeudo = $AdeudoTotal['monto_pendiente'];
$formatoMoneda_adeudo = number_format($show_adeudo, 2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de deudores</title>
</head>
<body>
<?php include "includes/header.php"?>
<section id= "container">
    <h1><i class="fa-solid fa-money-bill-1-wave"></i> Lista de Deudores</h1>
    <a href="nueva_venta.php" class="btn_new"><i class="fa-solid fa-plus"></i> <i class="fa-solid fa-file-invoice-dollar"></i> Nueva venta</a>
    <form action="buscar_deudores.php" method="GET" class="form_search">
    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
    <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
<p style="font-weight:bold;">Adeudo total: <span class="link_add"><?php echo '₡ '.$formatoMoneda_adeudo ?></span></p><br>
  <table>
    <tr>
      <th>ID</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Correo</th>
      <th>Monto de venta</th>
      <th>Saldo anterior</th>
      <th>Saldo actual</th>
      <th>Estado</th>
      <th>Acciones</th>

    </tr>
    
    <?php

$sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM deudores");

$result_register = mysqli_fetch_array($sql_register);
$total_registro = $result_register['total_registro'];

//registro por pagina
$por_pagina = 10;

if(empty($_GET['pagina'])){
    $pagina = 1;
}else{
    $pagina = $_GET['pagina'];
}

$desde = ($pagina - 1) * $por_pagina;
$total_paginas = ceil($total_registro / $por_pagina);

$query = mysqli_query($conection, "SELECT d.id, d.fecha, cl.nombre, cl.correo, CONCAT('₡ ', FORMAT(d.monto_credito, 2)) as monto_credito, CONCAT('₡ ', FORMAT(d.monto_pendiente, 2)) as monto_pendiente,  CONCAT('₡ ', FORMAT(d.saldo_anterior, 2)) as saldo_anterior, d.estatus FROM deudores d INNER JOIN cliente cl
ON d.idcliente = cl.idcliente
ORDER BY d.fecha DESC LIMIT $desde, $por_pagina");

    mysqli_close($conection);

$result = mysqli_num_rows($query);

if ($result > 0) {

    while ($data = mysqli_fetch_array($query)) {

            $status = '';
            $correo = '';

            if($data["estatus"] == 1){

                $status = '<span class="pendiente">Pendiente</span>';
            }else if($data["estatus"] == 2){
                $status = '<span class="pagada">Pagada</span>';
            }else{
                $status = '<span class="anulada">Anulada</span>';
            }

            if(empty($data["correo"])){
                $correo = 'N/A';
            }else{
                $correo = $data["correo"];
            }

        ?>

        <tr class="row<?php echo $data["id"]; ?>">
            <td><?php echo $data["id"] ?></td>
            <td><?php echo $data["fecha"] ?></td>
            <td><?php echo $data["nombre"] ?></td>
            <td><?php echo $correo ?></td>
            <td><?php echo $data["monto_credito"] ?></td>
            <td class="celSaldoAnterior"><?php echo $data['saldo_anterior'] ?></td>
            <td class="celMontoPendiente"><?php echo $data["monto_pendiente"] ?></td>
            <td id="estado"><?php echo $status ?></td>
            
            <td>
            <div class="div_accionesDeudores">
                <?php

                if($data["estatus"] == 2 || $data["estatus"] == 3){
                ?>
                <div id="deudaPagada">
            <a class="link_add disabled" href="#"> <i class="fa-solid fa-plus"></i> Abonar</a>
            </div>
                <?php
                                }else{
                ?>
                <div id="deudaPagada">
                <a class="link_add add_abono" id="<?php echo $data["id"] ?>" href="#" style="margin-right: 5px;"><i class="fa-solid fa-plus"></i> Abonar</a>
                </div>
                <?php
                    }
                ?>
                |
                <div id="pagos">
                <a class="link_edit view_pagos" href="#" id="<?php echo $data["id"] ?>"><i class="fa-solid fa-eye"></i> Pagos</a>
                </div>

            <?php 
                        } 
                     } ?>
            </div>
        </td>
        </tr>
    </table>
    <div class="paginador">
        <ul>
            <?php
            if ($pagina != 1) {

            ?>
            <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a></li>
            <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-caret-left fa-lg"></i></a></li>
<?php
            }
for($i=1; $i <= $total_paginas; $i++){
    if($i == $pagina){
        echo '<li class="pageSelected">'.$i.'</li>';
    } else {
        echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
    }
}
            if ($pagina != $total_paginas) {
            ?>
   
   <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-caret-right fa-lg"></i></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
            <?php
            }
            ?>
        </ul>
    </div>

    </section>
    <?php include "includes/footer.php"?>

    <script>
        
    </script>
</body>
</html>
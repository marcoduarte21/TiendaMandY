<?php
	$subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0;
	$descuento = 0;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/proyecto002/sistema/factura/style.css">
</head>
<body>
<?php
if($factura['estatus'] == 2){
?>

<img class="anulada" src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/proyecto002/sistema/factura/img/anulado.png">

<?php }?>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
				<img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/proyecto002/sistema/factura/img/logoEmpresa.png" width="180px">
				</div>
			</td>
			<td class="info_empresa">
				<?php
					if($result_config > 0){
						$iva = $configuracion['iva'];
				 ?>
				<div>
					<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					<p><?php echo $configuracion['direccion']; ?></p>
					<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
					<p>Email: <?php echo $configuracion['email']; ?></p>
				</div>
				<?php
					}
					if($result > 0){
				 ?>
			</td>
			<td class="info_factura">
				<div class="round">
					<span class="h3">Factura</span>
					<p>No. Factura: <strong><?php echo $factura['nofactura']; ?></strong></p>
					<p>Fecha: <?php echo $factura['fecha']; ?></p>
					<p>Hora: <?php echo $factura['hora']; ?></p>
					<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
					<p>Tipo: <?php echo $factura['forma_pago'] ?></p>
				</div>
			</td>
		</tr>
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Cliente</span>
					<table class="datos_cliente">
					<tr>
							<td><label>Nombre:</label> <p><?php echo $factura['nombre']; ?></p></td>
							<td><label>Correo:</label> <p><?php echo $factura['correo']; ?></p></td>
						</tr>
						<tr>
							<td><label>Teléfono:</label> <p><?php echo $factura['telefono']; ?></p></td>
							<td><label>Dirección:</label> <p><?php echo $factura['direccion']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>
						<?php } ?>
		</tr>
	</table>

	<table id="factura_detalle">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Descripción</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

			<?php

				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
			 ?>
				<tr>
					<td class="textcenter"><?php echo $row['cantidad']; ?></td>
					<td><?php echo $row['descripcion']; ?></td>
					<td class="textright"><?php echo $row['precio_venta']; ?></td>
					<td class="textright"><?php echo $row['precio_total']; ?></td>
				</tr>
				</tbody>
			<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}
				print_r($subtotal);
				$descuento = $_GET['desc'];
				print_r($descuento);

				if($descuento != 0){

				$show_descuento = $subtotal * ($descuento / 100);
                $total_a_pagar = round($subtotal - $show_descuento, 2);
				
                $formatoMoneda_descuento = number_format($show_descuento, 2);
                //$formatoMoneda_iva = number_format($impuesto, 2);
                $formatoMoneda_precioTotalFactura = number_format($total_a_pagar, 2);
				}
				$formatoMoneda_subtotal = number_format($subtotal, 2);

				if($descuento == 0){
				?>
			<tfoot id="detalle_totales">
			<tr>
				<td colspan="3" class="textright separador"><span>TOTAL FACTURA CRC.</span></td>
				<td class="textright"><span><?php echo $formatoMoneda_subtotal ; ?></span></td>
				</tr>
			</tfoot>
			<?php }else{ ?>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL CRC.</span></td>
					<td class="textright"><span><?php echo $formatoMoneda_subtotal; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>DESCUENTO (<?php echo $descuento; ?> %)</span></td>
					<td class="textright"><span><?php echo $formatoMoneda_descuento; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL CRC.</span></td>
					<td class="textright"><span><?php echo $formatoMoneda_precioTotalFactura ; ?></span></td>
				</tr>
		</tfoot>
		<?php } ?>
	</table>
	<div>
		<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con Yeral Guadamuz, su número es 63172574 y su correo: guadamuznic@hotmail.com</p>
		<h4 class="label_gracias">¡Gracias por su compra!</h4>
	</div>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/proyecto002/sistema/factura/style.css">
</head>
<body>
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
					<p>Dirección: <?php echo $configuracion['direccion']; ?></p>
					<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
					<p>Email: <?php echo $configuracion['email']; ?></p>
				</div>
				<?php
					}
				 ?>
			</td>
			<td class="info_factura">
				<div class="round">
					<span class="h3">Factura</span>
					<p>No. Factura: <strong><?php echo $last_pago['nofactura']; ?></strong></p>
					<p>Fecha: <?php echo $last_pago['fecha']; ?></p>
					<p>Hora: <?php echo $last_pago['hora']; ?></p>
					<p>Monto Abono: <?php echo $last_pago['monto_pagado']; ?></p>
					<p>No. Deuda: <?php echo $credito['id']; ?></p>
					<p>Vendedor: <?php echo $last_pago['vendedor']; ?></p>
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
							<td><label>Nombre:</label> <p><?php echo $credito['nombre']; ?></p></td>
							<td><label>Correo:</label> <p><?php echo $credito['correo']; ?></p></td>
						</tr>
						<tr>
							<td><label>Teléfono:</label> <p><?php echo $credito['telefono']; ?></p></td>
							<td><label>Dirección:</label> <p><?php echo $credito['direccion']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>
	<h5>Control de pagos</h5><br>
	<table id="factura_detalle">
			<thead>
				<tr>
					<th width="50px">PAGO NO.</th>
					<th width="50px">NO. FACTURA</th>
					<th class="textleft">FECHA</th>
					<th class="textleft">HORA</th>
					<th class="textright" width="150px">MONTO DE ABONO</th>
                    <th class="textright" width="150px">FACTURADO POR</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

			<?php

if($result_pago > 0){
						$numero_pago = 1;
					while($pago = mysqli_fetch_assoc($query_pago)){

			 ?>
				<tr>
					<td class="textcenter"><?php echo $numero_pago ?></td>
					<td class="textcenter"><?php echo $pago['nofactura']; ?></td>
					<td><?php echo $pago['fecha']; ?></td>
					<td><?php echo $pago['hora']; ?></td>
					<td class="textright"><?php echo $pago['monto_pagado']; ?></td>
					<td class="textright"><?php echo $pago['nombre']; ?></td>
				</tr>
			<?php
					$numero_pago = $numero_pago +1;
					}
				}
			?>
			</tbody><br>
			<tfoot id="detalle_totales">
			<?php 

if($credito['estatus'] != 2){
?>
			<tr class = "separador">
					<td colspan="3" class="textright"><span>SALDO ANTERIOR DEUDA CRC.</span></td>
					<td class="textright"><span><?php echo $credito['saldo_anterior']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>SALDO ACTUAL DEUDA CRC.</span></td>
					<td class="textright"><span><?php echo $credito['monto_pendiente']; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>ESTADO DE LA DEUDA:</span></td>
					<td class="textright"><span><strong class="estadoPendiente">PENDIENTE</strong></span></td>
					</tr>
					<?php }else if($credito['estatus'] == 2) {?>
						<tr class = "separador">
					<td colspan="3" class="textright"><span>SALDO ACTUAL DEUDA CRC.</span></td>
					<td class="textright"><span><?php echo $credito['monto_pendiente']; ?></span></td>
					</tr>
					<tr>
					<td colspan="3" class="textright"><span>ESTADO DE LA DEUDA:</span></td>
					<td class="textright"><span><strong class="estadoPagada">PAGADA</strong></span></td>
					</tr>
					<?php } ?>
		</tfoot>
	</table>
	<div>
		<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con Yeral Guadamuz, su número es 63172574 y su correo: guadamuznic@hotmail.com</p>
		<h4 class="label_gracias">¡Gracias por preferirnos!</h4>
	</div>

</div>

</body>
</html>
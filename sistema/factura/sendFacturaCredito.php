<?php

	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}


	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	 // REQUERIMOS EL AUTOLOAD
	 require '../email/vendor/autoload.php';

	
	use Dompdf\Dompdf;
	//En esta linea de código mandamos a llamar las opciones de dpmpdf para usarlas al momento de usar imágenes
use Dompdf\Options;
//LLAMAMOS LAS CLASES DE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use phpmailer\PHPMailer\Exception;


	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		
		if(empty($_REQUEST['desc'])){
            $descuento = 0.00;
            }else{
                $descuento = $_REQUEST['desc'];
            }

		//Datos de empresa
		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}

		//DATOS DE LA FACTURA
		$query = mysqli_query($conection,"SELECT f.nofactura, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora, fp.forma_pago, f.codcliente, f.estatus, v.nombre as vendedor, cl.nombre, cl.telefono,cl.direccion, cl.correo
											FROM factura f
											INNER JOIN usuario v
											ON f.usuario = v.idusuario
											INNER JOIN forma_pago fp
											ON f.id_formapago = fp.id
											INNER JOIN cliente cl
											ON f.codcliente = cl.idcliente
											WHERE f.nofactura = $noFactura AND f.codcliente = $codCliente  AND f.estatus != 10 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$factura['descuento'] = $descuento;
			$no_factura = $factura['nofactura'];

			//DATOS DE LOS PRODUCTOS
			$query_productos = mysqli_query($conection,"SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detallefactura dt
														ON f.nofactura = dt.nofactura
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE f.nofactura = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);

            //DATOS DE PAGO CREDITO

            $query_credito = mysqli_query($conection, "SELECT id, monto_pendiente, saldo_anterior FROM deudores WHERE noventa = $no_factura");

			$result_credito = mysqli_num_rows($query_credito);

			
			if($result_credito > 0){
				$credito = mysqli_fetch_assoc($query_credito);
			
			}
			$id_deuda = $credito['id'];
			//DATOS DEL PAGO
			$query_pago = mysqli_query($conection, "SELECT monto_pagado FROM pagos WHERE deuda_id = $id_deuda");

			$result_pago = mysqli_num_rows($query_pago);

			if($result_pago > 0){
				$pago = mysqli_fetch_assoc($query_pago);
			}

			$query_ultimo_pago = mysqli_query($conection, "SELECT * from pagos WHERE deuda_id = $id_deuda order by nofactura desc LIMIT 1;");

			$result_last_pago = mysqli_num_rows($query_ultimo_pago);

			if($result_last_pago > 0){
				$last_pago = mysqli_fetch_assoc($query_ultimo_pago);
			}

			$nombreCliente = $factura['nombre'];
			$correoCliente = $factura['correo'];
			$correoEmpresa = $configuracion['email'];

			//guardar en memoria el archivo
			ob_start();
		    include(dirname('__FILE__').'/facturaCredito.php');
			//carga el html de factura
		    $html = ob_get_clean();
//Aquí se crea el objeto a utilizar
		$options = new Options();

//Y debes activar esta opción "TRUE"
$options->set('isRemoteEnabled', TRUE);

$dompdf = new Dompdf($options);

			$filename = 'factura_'.$noFactura.'.pdf';
			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render (leer) the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream($filename,array('Attachment'=>false));

			$file = $dompdf->output();
    		file_put_contents($filename, $file);

			//TRABAJAR EXCEPCIONES
			$mail = new PHPMailer(true);

			try{
				//VER LAS EXCEPCIONES
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->isSMTP();
				//CONFIGURAR HOST CON GMAIL
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'tienda.myy@gmail.com';
				$mail->Password = 'uouvuljckxnkflqn';
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->Port = 465;
	
				//CUENTA QUE ENVIARA LOS CORREOS
				$mail->setFrom($correoEmpresa, 'TIENDA M&Y');
				//CORREOS QUE RECIBEN
				$mail->addAddress($correoCliente, $nombreCliente);
	
				//ENVIAR DOCUMENTOS E IMAGENES
				$mail->addAttachment($filename);
	
				//ENVIAR DATOS EN HTML
				$mail->isHTML(true);
	
				//ASUNTO Y CUERPO
				$mail->Subject = strtoupper('Facturacion de TIENDA M&Y');
				$mail->Body = strtoupper('HOLA '.$nombreCliente.', <br><br>SE HA GENERADO CORRECTAMENTE LA FACTURA DE TU COMPRA REALIZADA EN NUESTRA TIENDA.<br><br>
				<b>¡GRACIAS POR PREFERIRNOS!</b>');
				$mail->send();
	
				unlink($filename);
	
	
			} catch(Exception $e){
	
				echo 'Mensaje' . $mail->ErrorInfo;
			}

			exit;
		}
		}
	

?>
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


if(empty($_REQUEST['cl']) || empty($_REQUEST['f']) || empty($_REQUEST['deuda']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
        $id_deuda = $_REQUEST['deuda'];
		$anulada = '';


		//Datos de empresa
		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}
        //DATOS DE PAGO CREDITO

        $query_credito = mysqli_query($conection, "SELECT d.id, CONCAT('₡ ', FORMAT(d.monto_pendiente, 2)) as monto_pendiente, CONCAT('₡ ', FORMAT(d.monto_credito, 2)) as monto_credito, CONCAT('₡ ', FORMAT(d.saldo_anterior, 2)) as saldo_anterior, d.estatus, cl.nombre, cl.correo, cl.telefono, cl.direccion FROM deudores d
        INNER JOIN cliente cl ON d.idcliente = cl.idcliente
        WHERE d.id = $id_deuda AND d.estatus != 3");

        $result_credito = mysqli_num_rows($query_credito);

        if($result_credito > 0){

            $credito = mysqli_fetch_assoc($query_credito);
            $id_Deuda = $credito['id'];


        //DATOS DEL PAGO
        $query_pago = mysqli_query($conection, "SELECT p.nofactura, DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(p.fecha,'%H:%i:%s') as hora, p.monto_pagado, usu.nombre FROM pagos p
        INNER JOIN usuario usu ON p.idusuario = usu.idusuario
        WHERE deuda_id = $id_Deuda AND p.monto_pagado != 0.00");


        //MOSTRAR ULTIMO PAGO
        $query_mostrar_ultimo_pago = mysqli_query($conection, "SELECT p.nofactura, DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(p.fecha,'%H:%i:%s') as hora, usu.nombre as vendedor, p.monto_pagado FROM pagos p
        INNER JOIN usuario usu ON p.idusuario = usu.idusuario
        WHERE p.nofactura = $noFactura");
        $existe_ultimo_pago = mysqli_num_rows($query_mostrar_ultimo_pago);

        if($existe_ultimo_pago > 0){
            $last_pago = mysqli_fetch_assoc($query_mostrar_ultimo_pago);
        }

        $result_pago = mysqli_num_rows($query_pago);

        $nombreCliente = $credito['nombre'];
        $correoCliente = $credito['correo'];
        $correoEmpresa = $configuracion['email'];

        //guardar en memoria el archivo
        ob_start();
        include(dirname('__FILE__').'/factura_pagos.php');
        //carga el html de factura
        $html = ob_get_clean();
//Aquí se crea el objeto a utilizar
    $options = new Options();

//Y debes activar esta opción "TRUE"
$options->set('isRemoteEnabled', TRUE);

$dompdf = new Dompdf($options);

        $filename = 'facturaPago_'.$noFactura.'.pdf';
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
            $mail->Body = strtoupper('HOLA '.$nombreCliente.', <br><br>SE HA GENERADO CORRECTAMENTE LA FACTURA DE TU PAGO REALIZADO EN NUESTRA TIENDA.<br><br>
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
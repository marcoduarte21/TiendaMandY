<?php

	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;
	//En esta linea de código mandamos a llamar las opciones de dpmpdf para usarlas al momento de usar imágenes
use Dompdf\Options;

	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		$anulada = '';
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
		}
			//$no_factura = $factura['nofactura'];

			//DATOS DE LOS PRODUCTOS
			$query_productos = mysqli_query($conection,"SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detallefactura dt
														ON f.nofactura = dt.nofactura
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE f.nofactura = $noFactura");
			$result_detalle = mysqli_num_rows($query_productos);
			print_r($_GET['desc']);
			//guardar en memoria el archivo
			ob_start();
		    include("factura.php");
			//carga el html de factura
		    $html = ob_get_clean();
//Aquí se crea el objeto a utilizar
		$options = new Options();

//Y debes activar esta opción "TRUE"
$options->set('isRemoteEnabled', TRUE);

$dompdf = new Dompdf($options);

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render (leer) the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>false));
			exit;
	}
?>
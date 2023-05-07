<?php

use Masterminds\HTML5;

include "../conexion.php";
session_start();
/*print_r($_POST);
exit;*/

if(!empty($_POST)){

    //extraer datos del producto
    if($_POST['action'] == 'infoProducto'){

        $producto_id = $_POST['producto'];
        $query = mysqli_query($conection, "SELECT codproducto, descripcion, existencia, precio FROM producto WHERE codproducto = $producto_id AND estatus = 1");

        mysqli_close($conection);

        $resultDB = mysqli_num_rows($query);

        if($resultDB > 0){
            $data = mysqli_fetch_assoc($query);
            //unescaped es para que guarde el contenido del elemento como tal
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }


    // INFO DE LOS ABONOS MODAL
    if($_POST['action'] == 'infoDeuda'){
        $id_deuda = $_POST['id'];
        $query = mysqli_query($conection, "SELECT d.id, d.idcliente, cl.nombre, d.monto_pendiente FROM deudores d INNER JOIN cliente cl ON d.idcliente = cl.idcliente WHERE d.id = $id_deuda and d.estatus = 1");

        mysqli_close($conection);

        $resultDB = mysqli_num_rows($query);

        if($resultDB > 0){
            $data = mysqli_fetch_assoc($query);
            //unescaped es para que guarde el contenido del elemento como tal
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }

    // AGREGAR PRODUCTOS A ENTRADA
    if($_POST['action'] == 'addProduct'){


       if(!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])){

            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id = $_POST['producto_id'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO entradas(codproducto, cantidad, precio, usuario_id) VALUES($producto_id, $cantidad, $precio, $usuario_id)");

            if($query_insert){
                //ejecutar procedimiento almacenado

                $query_upd = mysqli_query($conection, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                $result_pro = mysqli_num_rows($query_upd);

                if($result_pro > 0){
                    $data = mysqli_fetch_assoc($query_upd);
                    $data['producto_id'] = $producto_id;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }else{
                echo 'error';
            }
            mysqli_close($conection);
       }else{
            echo 'error';
       }
        exit;
    }

    // AGREGAR PAGOS
    if($_POST['action'] == 'addAbono'){


        if(!empty($_POST['id']) || !empty($_POST['monto']) || !empty($_POST['codcliente'])){
 
             $id = $_POST['id'];
             $usuario_id = $_SESSION['idUser'];
             $idcliente = $_POST['codcliente'];
             $monto_pago = $_POST['monto'];
 
                 $query_upd = mysqli_query($conection, "CALL procesar_pago($id, $monto_pago, $idcliente, $usuario_id)");
                 $result_pro = mysqli_num_rows($query_upd);
 
                 if($result_pro > 0){
                     $data = mysqli_fetch_assoc($query_upd);
                     $data['id'] = $id;
                     $data['idcliente'] = $idcliente;
                     echo json_encode($data, JSON_UNESCAPED_UNICODE);
                     exit;
                 }
             mysqli_close($conection);
        }else{
             echo 'error';
        }
         exit;
     }

     if($_POST['action'] == 'searchClienteDeuda'){

        if(!empty($_POST['deuda'])){

            $deuda = $_POST['deuda'];

            $query_searchClienteDeuda = mysqli_query($conection, "SELECT d.idcliente, cl.nombre, cl.telefono, cl.correo, cl.direccion FROM deudores d INNER JOIN cliente cl ON d.idcliente = cl.idcliente  WHERE d.id = $deuda");
            $result = mysqli_num_rows($query_searchClienteDeuda);

            if($result > 0){
                $cliente_deuda = mysqli_fetch_assoc($query_searchClienteDeuda);
                echo json_encode($cliente_deuda, JSON_UNESCAPED_UNICODE);
                     exit;

            }
            mysqli_close($conection);
        }else{
            echo 'error';
        }
        exit;
     }


     //LIST
     if($_POST['action'] == 'viewPagos'){

        if(!empty($_POST['id'])){

            $id_deuda = $_POST['id'];

            $query = mysqli_query($conection, "SELECT id, idcliente FROM deudores WHERE id = $id_deuda");

            $result = mysqli_num_rows($query);

            if($result > 0){

                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);

                }
            mysqli_close($conection);
        }else{
            echo 'error';
       }
        exit;

     }


    //eliminar datos del producto
    if($_POST['action'] == 'deleteProduct'){


        if(empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
            echo 'error';
        } else {
            $idproducto = $_POST['producto_id'];

            $query_delete = mysqli_query($conection, "UPDATE producto SET estatus = 0 WHERE codproducto = $idproducto");
            mysqli_close($conection);
            if ($query_delete) {
                echo 'Acción Exitosa';
            } else {
                echo "Error al eliminar";
            }
        }
        echo 'error';
        exit;
    }

    // buscar cliente
    if($_POST['action'] == 'searchCliente'){

        if(!empty($_POST['cliente'])){
            $id = $_POST['cliente'];

            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $id and estatus = 1");

            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data = '';
            if($result > 0){
                $data = mysqli_fetch_assoc($query);
            }else{
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
       
    }

    // Registra Cliente - Ventas
    if($_POST['action'] == 'addCliente'){

    
    $nombre = $_POST['nom_cliente'];
    $correo = $_POST['correo_cliente'];
    $telefono = $_POST['tel_cliente'];
    $direccion = $_POST['dir_cliente'];
    $usuario_id = $_SESSION['idUser'];

    $query_insert = mysqli_query($conection, "INSERT INTO cliente(nombre, correo, telefono, direccion, usuario_id) VALUES('$nombre', '$correo' ,'$telefono', '$direccion', '$usuario_id') ");
        

        if ($query_insert) {
            //devuelve el id del cliente
            $codCliente = mysqli_insert_id($conection);
            $msg = $codCliente;
        }else{
            $msg = 'error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
    }

    // Agregar producto al detalle temporal
    if($_POST['action'] == 'addProductoDetalle'){


        if(empty($_POST['producto']) || empty($_POST['cantidad'])){
            echo 'error';
        }else{
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $token = md5($_SESSION['idUser']);
            if(empty($_POST['descuento'])){
                $descuento = 0.00;
                }else{
                    $descuento = $_POST['descuento'];
                }

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion ");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL add_detalle_temp($codproducto, $cantidad, '$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $sub_total_sinDesc = 0;
            $show_descuento = 0;

            $arrayData = array();

            if($result > 0){
                if($result_iva > 0){
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }

                while ($data = mysqli_fetch_assoc($query_detalle_temp)){

                    //round: redondea y,2 los decimales

                    $precio = round($data['cantidad'] * $data['precio_venta'], 2);
                    $apply_descuento = round($precio * ($descuento / 100),2);
                    $precioTotal = $precio - $apply_descuento;
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $sub_total_sinDesc = round($sub_total_sinDesc + $precio, 2);
                    $show_descuento = round($show_descuento + $apply_descuento,2);

                    $formatoMoneda_precioTotal = number_format($precioTotal, 2);
                    $formatoMoneda_precioVenta = number_format($data['precio_venta'], 2);
                    $formatoMoneda_desc = number_format($apply_descuento, 2);

                    if($descuento != 0.00){

                    $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">'.$formatoMoneda_desc.'</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                    }else{
                        $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">-</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                    }
                }
                
                //$impuesto = round($subtotal * ($iva / 100), 2);
                //$total_siniva = round($sub_total - $impuesto, 2);
                //$total = round($total_siniva + $impuesto, 2);
                $total_a_pagar = round($sub_total_sinDesc - $show_descuento, 2);

                $formatoMoneda_subtotal = number_format($sub_total_sinDesc, 2);
                $formatoMoneda_descuento = number_format($show_descuento, 2);
                //$formatoMoneda_iva = number_format($impuesto, 2);
                $formatoMoneda_precioTotalFactura = number_format($total_a_pagar, 2);

                if($descuento == 0.00){

                $detalleTotales = '<tr>
                <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                <td class="textright totalFactura">'.$formatoMoneda_subtotal.'</td>
                </tr>';
                }else{
                    $detalleTotales = '<tr>
                <td colspan="5" class="textright">SUBTOTAL CRC.</td>
                <td class="textright">'.$formatoMoneda_subtotal.'</td>
                </tr>
                <tr>
                <td colspan="5" class="textright">DESCUENTO ('.$descuento.'%) CRC.</td>
                <td class="textright">'.$formatoMoneda_descuento.'</td>
                </tr>
                <tr>
                <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                <td class="textright totalFactura">'.$formatoMoneda_precioTotalFactura.'</td>
                </tr>';
                }
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

            }else{
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    // Extrae datos del detalle temp
    if($_POST['action'] == 'searchForDetalle'){

        if(empty($_POST['user'])){
            echo 'error';
        }else{

            if(empty($_POST['descuento'])){
                $descuento = 0.00;
                }else{
                    $descuento = $_POST['descuento'];
                }
            
            $token = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion
            FROM detalle_temp tmp
            INNER JOIN producto p
            ON tmp.codproducto = p.codproducto
            WHERE token_user = '$token' ");

            $result = mysqli_num_rows($query);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion ");
            $result_iva = mysqli_num_rows($query_iva);

            
            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $sub_total_sinDesc = 0;
            $show_descuento = 0;
            $arrayData = array();
            

            if($result > 0){
                if($result_iva > 0){
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }

                while ($data = mysqli_fetch_assoc($query)){

                    //round: redondea y,2 los decimales

                    $precio = round($data['cantidad'] * $data['precio_venta'], 2);
                    $apply_descuento = round($precio * ($descuento / 100),2);
                    $precioTotal = $precio - $apply_descuento;
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $sub_total_sinDesc = round($sub_total_sinDesc + $precio, 2);
                    $show_descuento = round($show_descuento + $apply_descuento,2);

                    $formatoMoneda_precioTotal = number_format($precioTotal, 2);
                    $formatoMoneda_precioVenta = number_format($data['precio_venta'], 2);
                    $formatoMoneda_desc = number_format($apply_descuento, 2);

                    if($descuento != 0.00){

                    $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">'.$formatoMoneda_desc.'</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                    }else{
                        $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">-</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                }
            }
                //$impuesto = round($subtotal * ($iva / 100), 2);
                //$total_siniva = round($sub_total - $impuesto, 2);
                //$total = round($total_siniva + $impuesto, 2);
                $total_a_pagar = round($sub_total_sinDesc - $show_descuento, 2);

                $formatoMoneda_subtotal = number_format($sub_total_sinDesc, 2);
                $formatoMoneda_descuento = number_format($show_descuento, 2);
                //$formatoMoneda_iva = number_format($impuesto, 2);
                $formatoMoneda_precioTotalFactura = number_format($total_a_pagar, 2);


                if($descuento == 0.00){

                    $detalleTotales = '<tr>
                    <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                    <td class="textright totalFactura">'.$formatoMoneda_subtotal.'</td>
                    </tr>';
                    }else{
                        $detalleTotales = '<tr>
                    <td colspan="5" class="textright">SUBTOTAL CRC.</td>
                    <td class="textright">'.$formatoMoneda_subtotal.'</td>
                    </tr>
                    <tr>
                    <td colspan="5" class="textright">DESCUENTO ('.$descuento.'%) CRC.</td>
                    <td class="textright">'.$formatoMoneda_descuento.'</td>
                    </tr>
                    <tr>
                    <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                    <td class="textright totalFactura">'.$formatoMoneda_precioTotalFactura.'</td>
                    </tr>';
                    }

                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

            }else{
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    if ($_POST['action'] == 'delProductoDetalle') {

        if(empty($_POST['id_detalle'])){
            echo 'error';
        }else{

            if(empty($_POST['descuento'])){
                $descuento = 0.00;
                }else{
                    $descuento = $_POST['descuento'];
                }

            $id_detalle = $_POST['id_detalle'];
            $token = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion ");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL del_detalle_temp($id_detalle, '$token') ");
            $result = mysqli_num_rows($query_detalle_temp);

            
            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $sub_total_sinDesc = 0;
            $show_descuento = 0;
            $arrayData = array();

            if($result > 0){
                if($result_iva > 0){
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }

                while ($data = mysqli_fetch_assoc($query_detalle_temp)){

                    //round: redondea y,2 los decimales

                    $precio = round($data['cantidad'] * $data['precio_venta'], 2);
                    $apply_descuento = round($precio * ($descuento / 100),2);
                    $precioTotal = $precio - $apply_descuento;
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $sub_total_sinDesc = round($sub_total_sinDesc + $precio, 2);
                    $show_descuento = round($show_descuento + $apply_descuento,2);

                    $formatoMoneda_precioTotal = number_format($precioTotal, 2);
                    $formatoMoneda_precioVenta = number_format($data['precio_venta'], 2);
                    $formatoMoneda_desc = number_format($apply_descuento, 2);

                    if($descuento != 0.00){

                    $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">'.$formatoMoneda_desc.'</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                    }else{
                        $detalleTabla .= '<tr>
                    <td>'.$data['codproducto'].'</td>
                    <td colspan="2">'.$data['descripcion'].'</td>
                    <td class="textcenter">'.$data['cantidad'].'</td>
                    <td class="textright">'.$formatoMoneda_precioVenta.'</td>
                    <td class="textright">-</td>
                    <td class="textright">'.$formatoMoneda_precioTotal.'</td>
                    <td class="">
                    <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    </tr>';
                }
                }

                //$impuesto = round($subtotal * ($iva / 100), 2);
                //$total_siniva = round($sub_total - $impuesto, 2);
                //$total = round($total_siniva + $impuesto, 2);
                $total_a_pagar = round($sub_total_sinDesc - $show_descuento, 2);

                $formatoMoneda_subtotal = number_format($sub_total_sinDesc, 2);
                $formatoMoneda_descuento = number_format($show_descuento, 2);
                //$formatoMoneda_iva = number_format($impuesto, 2);
                $formatoMoneda_precioTotalFactura = number_format($total_a_pagar, 2);


                if($descuento == 0.00){

                    $detalleTotales = '<tr>
                    <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                    <td class="textright totalFactura">'.$formatoMoneda_subtotal.'</td>
                    </tr>';
                    }else{
                        $detalleTotales = '<tr>
                    <td colspan="5" class="textright">SUBTOTAL CRC.</td>
                    <td class="textright">'.$formatoMoneda_subtotal.'</td>
                    </tr>
                    <tr>
                    <td colspan="5" class="textright">DESCUENTO ('.$descuento.'%) CRC.</td>
                    <td class="textright">'.$formatoMoneda_descuento.'</td>
                    </tr>
                    <tr>
                    <td colspan="5" class="textright">TOTAL A PAGAR CRC.</td>
                    <td class="textright totalFactura">'.$formatoMoneda_precioTotalFactura.'</td>
                    </tr>';
                    }
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

            }else{
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;

    }


    // QUERY PARA ANULAR LA VENTA
    if($_POST['action'] == 'anularVenta'){

        $token = md5($_SESSION['idUser']);
        $query_del = mysqli_query($conection, "DELETE FROM detalle_temp WHERE token_user = '$token'");
        mysqli_close($conection);

        if($query_del){
            echo 'ok';
        }else{
            echo 'error';
        }
        exit;
    }

    // PROCESAR VENTA
    if($_POST['action'] == 'procesarVenta'){

        if(empty($_POST['codcliente'])){

            echo 'error';
        }else{

            $codcliente = $_POST['codcliente'];

            if(empty($_POST['descuento'])){
            $descuento = 0.00;
            }else{
                $descuento = $_POST['descuento'];
            }
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];

        $query = mysqli_query($conection, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
        $result = mysqli_num_rows($query);

        if($result > 0){

            $query_procesar = mysqli_query($conection, "CALL procesar_venta($usuario, $codcliente, '$token', $descuento)");
            $result_detalle = mysqli_num_rows($query_procesar);

            if($result_detalle > 0){
                $data = mysqli_fetch_assoc($query_procesar);
                $data['descuento'] = $descuento;
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }else{
                echo "error";
            }

        }else{
            echo "error";
        }
    }
        mysqli_close($conection);
        exit;

    }

    //PROCESAR CREDITO
    if($_POST['action'] == 'procesarCredito'){

        if(empty($_POST['codcliente'])){

            echo 'error';
        }else{
            $codcliente = $_POST['codcliente'];
            
        if(empty($_POST['descuento'])){
            $descuento = 0.00;
            }else{
                $descuento = $_POST['descuento'];
            }
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        $montoCredito = $_POST['montoPago'];
        $id_deuda = $_POST['idDeuda'];


        $query = mysqli_query($conection, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
        $result = mysqli_num_rows($query);

        if($result > 0){

            $query_procesar = mysqli_query($conection, "CALL procesar_credito($usuario,$codcliente, '$token', $montoCredito, $id_deuda, $descuento)");
            $result_detalle = mysqli_num_rows($query_procesar);

            if($result_detalle > 0){
                $data = mysqli_fetch_assoc($query_procesar);
                $data['descuento'] = $descuento;
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }else{
                echo "error";
            }

        }else{
            echo "error";
        }
    }
        mysqli_close($conection);
        exit;
    }



    // info factura
    if($_POST['action'] == 'infoFactura'){
        if(!empty($_POST['nofactura'])){

            $nofactura = $_POST['nofactura'];
            $query = mysqli_query($conection, "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estatus = 1");
            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }

        }
        echo 'error';
        exit;
    }

        // anular factura
        if($_POST['action'] == 'anularFactura'){

            if(!empty($_POST['noFactura'])){
            $noFactura = $_POST['noFactura'];

            $query_anular = mysqli_query($conection, "CALL anular_factura($noFactura)");
            mysqli_close($conection);
            $result = mysqli_num_rows($query_anular);

            if($result > 0){
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }

            }
        echo "error";
        exit;
        }

        //CAMBIAR CONTRASEÑA
        if($_POST['action'] == 'changePassword'){

            if(!empty($_POST['passActual']) && !empty($_POST['passNuevo'])){

            $password = md5($_POST['passActual']);
            $newPass = md5($_POST['passNuevo']);
            $idUser = $_SESSION['idUser'];

            $code = '';
            $msg = '';
            $arrData = array();

            $query_user = mysqli_query($conection, "SELECT * FROM usuario WHERE clave = '$password' and idusuario = $idUser");

            $result = mysqli_num_rows($query_user);

            if($result > 0){
                $query_update = mysqli_query($conection, "UPDATE usuario set clave = '$newPass' WHERE idusuario = $idUser ");
                mysqli_close($conection);

                if($query_update){
                    $code = '00';
                    $msg = "Su contraseña se ha actualizado con éxito.";
                }else{
                    $code = '2';
                    $msg = "No es posible cambiar su contraseña.";
                }
            }else{
                $code = '1';
                    $msg = "La contraseña actual es incorrecta.";
            }
            $arrData = array('cod' => $code, 'msg' => $msg);
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);

            }else{
            echo 'error';
            }
        exit;
        }


        //ACTUALIZAR DATOS DE EMPRESA
        if($_POST['action'] == 'updateDataEmpresa'){

        
        if (empty($_POST['txtNombre']) || empty($_POST['txtTelEmpresa']) || empty($_POST['txtEmailEmpresa']) || empty($_POST['txtDirEmpresa']) || empty($_POST['txtIva'])) {

        $code = '1';
        $msg = "Todos los campos son obligatorios";

        }else{
    
            $strNombre = $_POST['txtNombre'];
            $intTel = intval($_POST['txtTelEmpresa']);
            $strEmail = $_POST['txtEmailEmpresa'];
            $strDir = $_POST['txtDirEmpresa'];
            $strIva = $_POST['txtIva'];

            $queryUpd = mysqli_query($conection, "UPDATE configuracion SET nombre = '$strNombre',telefono = $intTel, email = '$strEmail',
            direccion = '$strDir', iva = $strIva WHERE id = 1");

            mysqli_close($conection);

            if ($queryUpd) {

                $code = '00';
                $msg = "Datos actualizados correctamente.";
            } else {
                $code = '2';
                $msg = "Error al actualizar los datos.";
            }

        }

        $arrData = array('cod' => $code, 'msg' => $msg);
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit;
    }
        }
    exit;

?>

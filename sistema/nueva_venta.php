<?php

session_start();
include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <?php include "includes/scripts.php"; ?>
    <title>Nueva Venta</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
    <div class="title_page">
    <h1><i class="fa-solid fa-file-invoice-dollar"></i> Nueva Venta</h1>
    </div>
    <div class="datos_cliente">
    <h4><i class="fa-solid fa-user"></i>  Datos del Cliente</h4>
    <a href="#" class="btn_new_cliente btn_new"><i class="fa-solid fa-person-circle-plus"></i> Nuevo cliente</a>
    <?php 
    $query_cliente = mysqli_query($conection, "SELECT idcliente, nombre, correo FROM cliente WHERE estatus = 1 ORDER BY nombre ASC");
$result_cliente = mysqli_num_rows($query_cliente);

?>
<div class="wd60 select_clientes">
<select name="cliente" id="select_cliente" class="form-select mb-3">
<option value="" selected>Seleccionar cliente</option>
    <?php

    if ($result_cliente > 0) {
        while ($cliente = mysqli_fetch_assoc($query_cliente)) {
    ?>
    <option id="option_cliente" value="<?php echo $cliente['idcliente']; ?>"><?php echo $cliente['nombre'] ?> - <?php echo $cliente['correo'] ?></option>
    <?php
        }
    }
    ?>
</select>
</div>
    <form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
    <input type="hidden" name="action" value="addCliente">
    <input type="hidden" id="idcliente" name="idcliente" value="" required>
    <div class="wd30">
    <label>Nombre:</label>
    <input type="text" name="nom_cliente" id="nom_cliente" required>
    </div>
    <div class="wd30">
    <label>Correo:</label>
    <input type="email" name="correo_cliente" id="correo_cliente">
    </div>
    <div class="wd30">
    <label>Teléfono:</label>
    <input type="number" name="tel_cliente" id="tel_cliente" required>
    </div>
    <div class="wd100">
    <label>Dirección:</label>
    <input type="text" name="dir_cliente" id="dir_cliente" required>
    </div>
    <div id="div_registro_cliente" class="wd100" style="display: none;">
    <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
    </div>
    </form>
    </div>
    <h4 class="datos_venta">Datos de Venta</h4>
    <table class="tbl_venta">
        <thead>
            <tr>
                <th width="100px">Código</th>
                <th>Descripción</th>
                <th>Existencia</th>
                <th width="100px">Cantidad</th>
                <th class="textright">Precio</th>
                <th class="textright">Precio Total</th>
                <th> Acción</th>
                <th></th>
            </tr>
            <tr>
                <td><input type="text" name="txt_cod_producto"  id="txt_cod_producto"></td>
                <td id="txt_descripcion">-</td>
                <td id="txt_existencia">-</td>
                <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                <td id="txt_precio" class="textright">0.00</td>
                <td id="txt_precio_total" class="textright">0.00</td>
                <td><a href="#" id="add_product_venta" class="link_add"><i class="fa-solid fa-plus"></i> Agregar</a></td>
                
            </tr>
            <th id="lbl_descuento">Descuento:(%)</th>
            <td><input type="number" name="txt_descuento"  id="txt_descuento" value="0" onkeyup="siElDescuentoEsNulo();" disabled></td>
            <tr>
                <th>Código</th>
                <th colspan="2">Descripción</th>
                <th>Cantidad</th>
                <th class="textright">Precio unitario</th>
                <th class="textright">Descuento</th>
                <th class="textright">Precio final</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="detalle_venta">
            <!-- CONTENIDO AJAX -->
        </tbody>
        <tfoot id="detalle_totales">
            <!-- CONTENIDO EN AJAX -->
            
        </tfoot>
    </table><br>
    <div class="datos_venta">
        <div class="datos">
          <div class="wd30">
            <label>Vendedor:</label>
            <p><?php echo $_SESSION['nombre']; ?></p>
          </div>
          <div class="wd30">
          <label>Forma de Pago:</label>
        <div class="divFormaDePago">
        <input type="radio" name="formaPago" id="contado" value= "contado" onclick="ocultarMonto()"><label for="contado">Contado</label><br>
        <input type="radio" name="formaPago" id="credito" value="credito" onclick="mostrarMontoInicialDePago()" style="margin-left:30px;"><label for="credito">A credito</label><br>
        </div>
        <div id="divMontoInicial">
        <label>Monto del abono:</label>
        <input type="number" name="monto_inicial" id="monto_inicial">
        </div>
        </div>
        <?php 
    $query_deuda = mysqli_query($conection, "SELECT d.id, cl.nombre FROM deudores d INNER JOIN cliente cl ON d.idcliente = cl.idcliente WHERE d.estatus = 1 ORDER BY id DESC");
$result_deuda = mysqli_num_rows($query_cliente);

?>

    <div class="wd40 deuda" style="display: none;">
    <h6>¿Desea añadir la venta a una deuda existente?</h6>
    <input type="hidden" name="cliente_deuda" id="cliente_deuda" value="" required>
<select name="deuda" id="select_deuda" class="form-select mb-3">
<option value="0" selected>Seleccionar No. Deuda</option>
<option value="0">No seleccionar</option>
    <?php

    if ($result_deuda > 0) {
        while ($deuda = mysqli_fetch_assoc($query_deuda)) {
    ?>
    <option id="option_deuda" value="<?php echo $deuda['id']; ?>">No. <?php echo $deuda['id'] ?> - <?php echo $deuda['nombre'] ?></option>
    <?php
        }
    }
    ?>
</select>
</div>
          <div class="wd100">
            <label>Acciones</label>
            <div class="acciones_venta">
                <a href="#" class="btn_ok textcenter" id="btn_anular_venta"><i class="fa-solid fa-ban"></i> Anular</a>
                <a href="" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;"><i class="fa-solid fa-file-invoice-dollar"></i> Procesar</a>
                <a href="" class="btn_new textcenter" id="btn_facturar_venta_correo" style="display: none;">Procesar y enviar <i class="fa-solid fa-envelope"></i></a>

            </div>
          </div>
          </div>
    </div>
    </section>
    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            var usuarioid = '<?php echo $_SESSION['idUser']; ?>'
            searchForDetalle(usuarioid);

        });

        function mostrarMontoInicialDePago(){

            let monto_inicial = $('#divMontoInicial');
            monto_inicial.slideDown();
            $('.deuda').slideDown();
        }

        function ocultarMonto(){
            $('#divMontoInicial').slideUp();
            $('#monto_inicial').val('');
            $('.deuda').slideUp();
        }

        function siElDescuentoEsNulo(){

            if(($('#txt_descuento').val()).length > 2 || $('#txt_descuento').val() > 50 || $('#txt_descuento').val() < 0){

                alert('El descuento no se puede aplicar.');
                $('#txt_descuento').val('');
            }
        }

    </script>
</body>
</html>
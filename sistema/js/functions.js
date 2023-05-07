$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('img_producto.png');
        }

    });



    //MODAL FORM ADD PRODUCT

    $('.add_product').click(function(e){

        // prevenir la acción
        e.preventDefault();

        // attr: acceder al id de un elemento
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},
        
        success: function(response){
           
            if(response != 'error'){

                // acceder al archivo json mediante objetos
                var info = JSON.parse(response);
                //$('#producto_id').val(info.codproducto);
               // $('.nameProducto').html(info.descripcion);


                $('.bodyModal').html('<form action="" method="post"'+ 'name="form_add_producto"'+ 'id="form_add_producto" onsubmit="event.preventDefault(); sendDataProduct();">'+
                '<h1><i class="fa-solid fa-box" style="font-size: 45pt;"></i> <br> Agregar producto</h1>'+
                '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del producto" required><br>'+
                '<input type="text" name="precio" id="txtPrecio" placeholder="Precio del producto" required>'+
                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required>'+
                '<input type="hidden" name="action" value="addProduct" required>'+
                '<div class="alert alertAddProduct"><p></p></div>'+
                '<button type="submit" class="btn_new"><i class="fa-solid fa-plus"></i> Agregar</button>'+
                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fa-solid fa-xmark"></i> Cerrar</a>'+
                '</form>');
            }
        },

        error: function(error){
            console.log(error);
        }

        });

        // mostrar el recuadro
        $('.modal').fadeIn();
    });

//MODAL FORM DELETE PRODUCT

$('.delete_product').click(function(e){

    // prevenir la acción de recargar la página
    e.preventDefault();

    // attr: acceder al id de un elemento
    var producto = $(this).attr('product');
    var action = 'infoProducto';

    $.ajax({

        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, producto:producto},
    
    success: function(response){
       
        if(response != 'error'){

            // acceder al archivo json mediante objetos
            var info = JSON.parse(response);
            //$('#producto_id').val(info.codproducto);
           // $('.nameProducto').html(info.descripcion);


            $('.bodyModal').html('<form action="" method="post"'+ 'name="form_delete_product"'+ 'id="form_delete_product" onsubmit="event.preventDefault(); deleteProduct();">'+
            '<h1><i class="fa-solid fa-box" style="font-size: 45pt;"></i> <br> Eliminar Producto</h1>'+

            '<p>¿Está seguro que desea eliminar el siguiente registro?</p>'+

            '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
            
            '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required>'+
            '<input type="hidden" name="action" value="deleteProduct" required>'+
            '<div class="alert alertAddProduct"><p></p></div>'+
            '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fa-solid fa-xmark"></i> Cerrar</a>'+
            '<button type="submit" class="btn_ok"><i class="fa-solid fa-trash"> Eliminar</button>'+
            '</form>');
        }
    },

    error: function(error){
        console.log(error);
    }

    });

    // mostrar el recuadro
    $('.modal').fadeIn();
});


$('#search_proveedor').change(function(e){

e.preventDefault();
var sistema = getUrl();
location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();

});

$('#search_marca').change(function(e){

    e.preventDefault();
    var sistema = getUrl();
    location.href = sistema+'buscar_productos.php?marca='+$(this).val();
    
    });


$('#select_deuda').change(function(e){

    e.preventDefault();

    var deuda = $(this).val();
    var action = 'searchClienteDeuda';

    $.ajax({

        url: 'ajax.php',
        type: "POST",
        async : true,
        data : {action:action,deuda:deuda},
    
        success: function(response){
            
            if(response != 'error'){
                //parsea la respuesta para convertirla en un objeto json
                var data = JSON.parse(response);
                $('#cliente_deuda').val(data.idcliente);
                $('#idcliente').val(data.idcliente);
            $('#nom_cliente').val(data.nombre);
            $('#tel_cliente').val(data.telefono);
            $('#correo_cliente').val(data.correo);
            $('#dir_cliente').val(data.direccion);
            //ocultar boton de agregar
            $('.btn_new_cliente').slideUp();

            //Bloquea campos
            $('#nom_cliente').attr('disabled', 'disabled');
            $('#tel_cliente').attr('disabled', 'disabled');
            $('#dir_cliente').attr('disabled', 'disabled');
            $('#correo_cliente').attr('disabled', 'disabled');

            //oculta boton guardar
            $('#div_registro_cliente').slideUp();
            $('.select_clientes').slideUp();
            }
        },
        error: function(error){
            console.log(error);
        }

        });


});


// buscar cliente
//keyup: al presionar se ejecuta el evento
$('#select_cliente').change(function(e){
    e.preventDefault();
    
    var cl = $(this).val();
    var action = 'searchCliente';

    $.ajax({

    url: 'ajax.php',
    type: "POST",
    async : true,
    data : {action:action,cliente:cl},

    success: function(response){
        
        if(response == 0){
            $('#idcliente').val('');
            $('#nom_cliente').val('');
            $('#tel_cliente').val('');
            $('#correo_cliente').val('');
            $('#dir_cliente').val('');
            //mostrar boton de agregar
            $('.btn_new_cliente').slideDown();
        }else{
            //parsea la respuesta para convertirla en un objeto json
            var data = JSON.parse(response);
            $('#idcliente').val(data.idcliente);
            $('#nom_cliente').val(data.nombre);
            $('#tel_cliente').val(data.telefono);
            $('#correo_cliente').val(data.correo);
            $('#dir_cliente').val(data.direccion);
            //ocultar boton de agregar
            $('.btn_new_cliente').slideUp();

            //Bloquea campos
            $('#nom_cliente').attr('disabled', 'disabled');
            $('#tel_cliente').attr('disabled', 'disabled');
            $('#dir_cliente').attr('disabled', 'disabled');
            $('#correo_cliente').attr('disabled', 'disabled');

            //oculta boton guardar
            $('#div_registro_cliente').slideUp();
        }
    },
    error: function(error){
        console.log(error);
    }

    });

});


    $('.btn_new_cliente').click(function(e){

        //muestra boton guardar
        $('#div_registro_cliente').slideDown();
        $('#idcliente').val('');
            $('#nom_cliente').val('');
            $('#tel_cliente').val('');
            $('#correo_cliente').val('');
            $('#dir_cliente').val('');

            $('#nom_cliente').removeAttr('disabled');
            $('#tel_cliente').removeAttr('disabled');
            $('#dir_cliente').removeAttr('disabled');
            $('#correo_cliente').removeAttr('disabled');

    });

    // Crear Cliente - Ventas
    $('#form_new_cliente_venta').submit(function(e){
        e.preventDefault();

        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : $('#form_new_cliente_venta').serialize(),
        
            success: function(response){
                
                if(response != 'error'){
                    //Agregar id a input hiden
                    $('#idcliente').val(response);

                    //bloquea campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#correo_cliente').attr('disabled', 'disabled');
            $('#tel_cliente').attr('disabled', 'disabled');
            $('#dir_cliente').attr('disabled', 'disabled');

            //oculta boton guardar
            $('#div_registro_cliente').slideUp();
            //oculta boton agregar
            $('.btn_new_cliente').slideUp();
                }
                
            },
            error: function(error){
                console.log(error);
            }
        
            });

    });

    // Buscar Producto
    $('#txt_cod_producto').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto';

        if(producto != ''){
        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : {action:action, producto:producto},
        
            success: function(response){
                
                if(response != 'error'){

                var info = $.parseJSON(response);
            $('#txt_descripcion').html(info.descripcion);
            $('#txt_existencia').html(info.existencia);
            $('#txt_cant_producto').val('1');
            $('#txt_precio').html(info.precio);
            $('#txt_precio_total').html(info.precio);

            //Activar Cantidad
            $('#txt_cant_producto').removeAttr('disabled');
            $('#txt_descuento').removeAttr('disabled');

            //Mostrar botón agregar
            $('#add_product_venta').slideDown();

                }else{
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    //bloquear cantidad
                    $('#txt_cant_producto').attr('disabled', 'disabled');
                    $('#txt_descuento').attr('disabled', 'disabled');

                    //ocultar boton agregar
                    $('#add_product_venta').slideUp();

                }
            },
            error: function(error){
                console.log(error);
            }
        
            });
        }

    });

    // Validar Cantidad del producto antes de agregar
    $('#txt_cant_producto').keyup(function(e){
        e.preventDefault();

        if($('#txt_descuento').val() != 0){
        var sub_total = $(this).val() * $('#txt_precio').html();
        var descuento = sub_total * ($('#txt_descuento').val() / 100);
        var precio_total = sub_total - descuento;
        }else{
            precio_total = $(this).val() * $('#txt_precio').html();
        }
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(precio_total);


        //oculta el boton agregar si la cantidad es menor que 1
        // isNaN: no es un número
        if($(this).val() < 1 || isNaN($(this).val()) || $(this).val() > existencia){
            $('#add_product_venta').slideUp();
            $('#txt_precio_total').html('0.00');

        }else{
            $('#add_product_venta').slideDown();
        }

    });

    // Agregar productos al detalle
    $('#add_product_venta').click(function(e){
        e.preventDefault();

       if($('#txt_cant_producto').val() > 0){

        var codproducto = $('#txt_cod_producto').val();
        var cantidad = $('#txt_cant_producto').val();
        var descuento = $('#txt_descuento').val();
        var action = 'addProductoDetalle';

        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : {action:action, producto:codproducto, cantidad:cantidad, descuento:descuento},
        
            success: function(response){
                
                if(response != 'error'){

                    var info = JSON.parse(response);
                    //console.log(info);
                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    $('#txt_cod_producto').val('');
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_descuento').val(descuento);
                    $('#txt_precio_total').html('0.00');

                    //BLOQUEAR CANTIDAD
                    $('#txt_cant_producto').attr('disabled', 'disabled');
                    $('#txt_descuento').attr('disabled', 'disabled');

                    //OCULTAR BOTON AGREGAR
                    $('#add_product_venta').slideUp();

                }else{
                    console.log('no data');
                }
                viewProcesar();
                
            },
            error: function(error){
                console.log(error);
            }
        
            });

       }

    });

        // Anular venta
        $('#btn_anular_venta').click(function(e){

            e.preventDefault();

            var rows = $('#detalle_venta tr').length;
            if(rows > 0){

                var action = 'anularVenta';

                $.ajax({

                    url: 'ajax.php',
                    type: "POST",
                    async : true,
                    data : {action:action},
                
                    success: function(response){
                        
                        if(response != 'error'){
        
                            location.reload();
                        }
                        
                    },
                    error: function(error){
                        console.log(error);
                    }
                
                    });

            }

        });

        // Facturar venta
        $('#btn_facturar_venta').click(function(e){

            if(($('#idcliente').val()).length == 0){

                alert('Debe ingresar los datos del cliente.');
            }else{

                if($('#credito').is(':checked') || $('#contado').is(':checked')){

            e.preventDefault();

            var rows = $('#detalle_venta tr').length;
            if(rows > 0){


                if($('#contado').is(':checked')){
                var action = 'procesarVenta';
                var codcliente = $('#idcliente').val();
                var descuento = $('#txt_descuento').val();

                $.ajax({

                    url: 'ajax.php',
                    type: "POST",
                    async : true,
                    data : {action:action, codcliente:codcliente, descuento:descuento},
                
                    success: function(response){
                        
                        if(response != 'error'){
                            
                            var info = JSON.parse(response);
                            console.log(info);

                            generarPDF(info.codcliente, info.nofactura, info.descuento);

                             //recarga la pagina
                            location.reload();
                        }else{
                            console.log('no data');
                        }
                        
                    },
                    error: function(error){
                        console.log(error);
                    }
                
                    });

                }
                
                if($('#monto_inicial').val() > parseInt($('.totalFactura').val())){

                    alert('El monto inicial ingresado excede el monto total de la venta.');
                }else{

                    if($('#idcliente').val() == $('#cliente_deuda').val() || parseInt($('#select_deuda').val()) == 0){

                if($('#credito').is(':checked')){
                    var action = 'procesarCredito';
                    var codcliente = $('#idcliente').val();
                    var montoPago = $('#monto_inicial').val();
                    var idDeuda = $('#select_deuda').val();
                    var descuento = $('#txt_descuento').val();

                    $.ajax({

                        url: 'ajax.php',
                        type: "POST",
                        async : true,
                        data : {action:action, codcliente:codcliente, montoPago:montoPago, idDeuda:idDeuda, descuento:descuento},
                    
                        success: function(response){
                            
                            if(response != 'error'){
                                
                                var info = JSON.parse(response);
                                generarPDFCredito(info.codcliente, info.nofactura, info.descuento);
                                
                                 //recarga la pagina
                                location.reload();
                            }else{
                                console.log('no data');
                            }
                            
                        },
                        error: function(error){
                            console.log(error);
                        }
                    
                        });
                }
        }else{
            alert('El No. Deuda no coincide con el cliente ingresado al inicio de la venta.');
        }
    }
            }
        }else{
            alert('DEBE INGRESAR LA FORMA DE PAGO!');
        }
        }

        });


        // Facturar venta correo
        $('#btn_facturar_venta_correo').click(function(e){

            if(($('#idcliente').val()).length == 0){

                alert('Debe ingresar los datos del cliente.');
            }else{

                if($('#credito').is(':checked') || $('#contado').is(':checked')){

            e.preventDefault();

            var rows = $('#detalle_venta tr').length;
            if(rows > 0){


                if($('#contado').is(':checked')){
                var action = 'procesarVenta';
                var codcliente = $('#idcliente').val();
                var descuento = $('#txt_descuento').val();
                
                $.ajax({

                    url: 'ajax.php',
                    type: "POST",
                    async : true,
                    data : {action:action, codcliente:codcliente, descuento:descuento},
                
                    success: function(response){
                        
                        if(response != 'error'){
                            
                            var info = JSON.parse(response);
                            //console.log(info);

                            sendPDF(info.codcliente, info.nofactura, info.descuento);

                             //recarga la pagina
                            location.reload();
                        }else{
                            console.log('no data');
                        }
                        
                    },
                    error: function(error){
                        console.log(error);
                    }
                
                    });

                }
                
                if($('#monto_inicial').val() > parseInt($('.totalFactura').val())){

                    alert('El monto inicial ingresado excede el monto total de la venta.');
                }else{

                    if($('#idcliente').val() == $('#cliente_deuda').val() || parseInt($('#select_deuda').val()) == 0){

                if($('#credito').is(':checked')){
                    var action = 'procesarCredito';
                    var codcliente = $('#idcliente').val();
                    var montoPago = $('#monto_inicial').val();
                    var idDeuda = $('#select_deuda').val();
                    var descuento = $('#txt_descuento').val();

                    $.ajax({

                        url: 'ajax.php',
                        type: "POST",
                        async : true,
                        data : {action:action, codcliente:codcliente, montoPago:montoPago, idDeuda:idDeuda, descuento:descuento},
                    
                        success: function(response){
                            
                            if(response != 'error'){
                                
                                var info = JSON.parse(response);
                                //console.log(info);
    
                                sendPDFCredito(info.codcliente, info.nofactura, info.descuento);
                                
                                 //recarga la pagina
                                location.reload();
                            }else{
                                console.log('no data');
                            }
                            
                        },
                        error: function(error){
                            console.log(error);
                        }
                    
                        });
                }
            
        }else{
            alert('El No. Deuda no coincide con el cliente ingresado al inicio de la venta.');
        }
            }
        }else{
            alert('DEBE INGRESAR LA FORMA DE PAGO!');
        }
        }
    }
        });

//MODAL FORM anular factura

$('.anular_factura').click(function(e){

    // prevenir la acción de recargar la página
    e.preventDefault();

    var nofactura = $(this).attr('fac');
    var action = 'infoFactura';

    $.ajax({

        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, nofactura:nofactura},
    
    success: function(response){
       
        if(response != 'error'){

            // acceder al archivo json mediante objetos
            var info = JSON.parse(response);
            //$('#producto_id').val(info.codproducto);
           // $('.nameProducto').html(info.descripcion);


            $('.bodyModal').html('<form action="" method="post"'+ 'name="form_anular_venta"'+ 'id="form_anular_venta" onsubmit="event.preventDefault(); anularFactura();">'+
            '<h1><i class="fa-solid fa-box" style="font-size: 45pt;"></i> <br> Anular Factura</h1><br>'+

            '<p>¿Realmente desea anular la factura?</p>'+
            '<p><strong>No. '+info.nofactura+'</strong></p>'+
            '<p><strong>Monto. CRC. '+info.totalfactura+'</strong></p>'+
            '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
            '<input type="hidden" name="action" value="anularFactura">'+
            '<input type="hidden" name="no_factura" id="no_factura" value="'+info.nofactura+'" required>'+

            '<div class="alert alertAddProduct" style="display: none;"><p></p></div>'+
            '<button type="submit" class="btn_ok"><i class="fa-solid fa-trash"></i> Anular</button>'+
            '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fa-solid fa-xmark"></i> Cerrar</a>'+
            '</form>');
        }
    },

    error: function(error){
        console.log(error);
    }

    });

    // mostrar el recuadro
    $('.modal').fadeIn();
});

        //ver Factura en modulo ventas

        $('.view_factura').click(function(e){
            e.preventDefault();

            var codCliente = $(this).attr('cl');
            var noFactura = $(this).attr('f');
            var descuento = $('#txt_descuento').val();

            generarPDF(codCliente, noFactura, descuento);
        });

        $('.view_factura_credito').click(function(e){
            e.preventDefault();

            var codCliente = $(this).attr('cl');
            var noFactura = $(this).attr('f');
            var descuento = $('#txt_descuento').val();
            generarPDFCredito(codCliente, noFactura, descuento);
        });


        // cambiar password

        $('.newPass').keyup(function(){

            validPass();
        });

        $('#frmChangePass').submit(function(e){

            e.preventDefault();

            var passActual = $('#txtPassUser').val();
            var passNuevo = $('#txtNewPassUser').val();
            var confirmPassNuevo = $('#txtPassConfirm').val();
            var action = "changePassword";

            if(passNuevo != confirmPassNuevo){
                $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
                $('.alertChangePass').slideDown();
                return false;
            }
    
            if(passNuevo.length < 6){
                $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe contener como mínimo 6 caracteres.</p>');
                $('.alertChangePass').slideDown();
                return false;
            }

            $.ajax({

                url: 'ajax.php',
                type: "POST",
                async : true,
                data : {action:action, passActual:passActual, passNuevo:passNuevo},
            
                success: function(response){
                    
                    if(response != 'error'){
                        
                        var info = JSON.parse(response);
                        
                        if(info.cod == '00'){
                            $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
                            $('#frmChangePass')[0].reset();
                        }else{
                            $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');
                        }
                        $('.alertChangePass').slideDown();
                    }else{
                        console.log('no data');
                    }
                    
                },
                error: function(error){
                    console.log(error);
                }
            
                });

        });

        //Actualizar datos empresa
        $('#frmEmpresa').submit(function(e){
    
            e.preventDefault();

            var strNombreEmp = $('#txtNombre').val();
            var intTelEmp = $('#txtTelEmpresa').val();
            var strEmailEmp = $('#txtEmailEmpresa').val();
            var strDirEmp = $('#txtDirEmpresa').val();
            var intIva = $('#txtIva').val();

            if( strNombreEmp == '' || intTelEmp == '' || strEmailEmp == '' || strDirEmp == '' || intIva == ''){
                $('.alertFormEmpresa').html('<p style="color:red">Todos los campos son obligatorios</p>');
                $('.alertFormEmpresa').slideDown();
                return false;
            }

            $.ajax({

                url: 'ajax.php',
                type: "POST",
                async : true,
                data : $('#frmEmpresa').serialize(),
                // Antes de el submit
                beforeSend: function(){
                $('.alertFormEmpresa').slideUp();
                $('.alertFormEmpresa').html('');
                $('#frmEmpresa input').attr('disabled', 'disabled');

                },

            
                success: function(response){
                    
                    if(response != 'error'){
                        
                        var info = JSON.parse(response);
                        
                        if(info.cod == '00'){
                            $('.alertFormEmpresa').html('<p style="color: #23922d;">'+info.msg+'</p>');
                            $('.alertFormEmpresa').slideDown();
                        }else{
                            $('.alertFormEmpresa').html('<p style="color: red;">'+info.msg+'</p>');
                        }
                        $('.alertFormEmpresa').slideDown();
                        $('#frmEmpresa input').removeAttr('disabled');
                        
                    }else{
                        console.log('no data');
                    }
                    
                },
                error: function(error){
                    console.log(error);
                }
            
                });

    });


    // MODAL ADD ABONO DEUDORES
    
    $('.add_abono').click(function(e){

        e.preventDefault();

        // attr: acceder al id de un elemento
        var id = $(this).attr('id');
        var action = 'infoDeuda';
        
        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, id:id},
        
        success: function(response){
           
            if(response != 'error'){

                // acceder al archivo json mediante objetos
                var info = JSON.parse(response);
                
                $('.bodyModal').html('<form action="" method="post"'+ 'name="form_add_abono"'+ 'id="form_add_abono" onsubmit="event.preventDefault();">'+
                '<h1><i class="fa-solid fa-money-bill-1-wave" style="font-size: 35pt;"></i> Registrar pago</h1>'+
                '<div class="data_delete">'+
                '<p>Nombre del Cliente: <span>'+info.nombre+'</span></p>'+
                '<p>Monto pendiente: <span id="info_montoPendiente">'+info.monto_pendiente+'</span></p></div><br>'+
                '<label>Monto a pagar: </label>'+
                '<input type="number" name="monto" id="txtMonto" placeholder="Monto del abono" required><br>'+
                '<input type="hidden" name="id" id="id" value="'+info.id+'" required>'+
                '<input type="hidden" name="codcliente" id="codcliente" value="'+info.idcliente+'" required>'+
                '<input type="hidden" name="action" value="addAbono" required>'+
                '<div class="alert alertAddProduct"><p></p></div>'+
                '<button type="submit" id="btn_abono" onclick="sendDataAbono();" class="btn_new style="font-size:40px"><i class="fa-solid fa-file-invoice-dollar"></i> Procesar</button>'+
                '<button type="submit" id="btn_abono_correo" onclick="facturar_pago_correo();" class="btn_new style="font-size:40px">Procesar y enviar <i class="fa-solid fa-envelope"></i></button>'+
                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fa-solid fa-xmark"></i> Cerrar</a>'+
                '</form>');
            }
        },

        error: function(error){
            console.log(error);
        }

        });

        

        // mostrar el recuadro
        $('.modal').fadeIn();
    });

    $('.view_pagos').click(function(e){

        e.preventDefault();
        var id = $(this).attr('id');
        var action = 'viewPagos';

        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : {action:action, id:id},

            success: function(response){
           
                if(response != 'error'){
    
                    // acceder al archivo json mediante objetos
                    var info = JSON.parse(response);
                    generaPDFPagos(info.idcliente, info.id);
                    
                    
                }
            },
    
            error: function(error){
                console.log(error);
            }

        });

    });

    }); //end ready


    function validPass(){
        var passNuevo = $('#txtNewPassUser').val();
        var confirmPassNuevo = $('#txtPassConfirm').val();

        if(passNuevo != confirmPassNuevo){
            $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
            $('.alertChangePass').slideDown();
            return false;
        }

        if(passNuevo.length < 6){
            $('.alertChangePass').html('<p>La nueva contraseña debe contener como mínimo 6 caracteres.</p>');
            $('.alertChangePass').slideDown();
            return false;
        }

        $('.alertChangePass').html('');
        $('.alertChangePass').slideUp();

    }

    function anularFactura(){

        var noFactura = $('#no_factura').val();
        var action = 'anularFactura';


        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : {action:action, noFactura:noFactura},
        
            success: function(response){
                
                if(response == 'error'){

                    $('.alertAddProduct').html('<p style="color:red;">Error al anular la factura.</p>');
                }else{

                    $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                    $('#form_anular_factura .btn_ok').remove();
                    $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fa-solid fa-ban"></i></button>');
                    $('.alertAddProduct').html('<p>Factura anulada.</p>');
                }
            },
            error: function(error){
                console.log(error);
            }
        
            });


    }


    function generarPDF(cliente, factura, descuento){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura+'&desc='+descuento;
        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }


    function sendPDF(cliente, factura, descuento){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/sendFactura.php?cl='+cliente+'&f='+factura+'&desc='+descuento;
        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }


    function generarPDFCredito(cliente, factura, descuento){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/generaFacturaCredito.php?cl='+cliente+'&f='+factura+'&desc='+descuento;

        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }

    function sendPDFCredito(cliente, factura, descuento){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/sendFacturaCredito.php?cl='+cliente+'&f='+factura+'&desc='+descuento;

        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }

    function sendPDFPagos(cliente, deuda, factura){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/sendPagos.php?cl='+cliente+'&deuda='+deuda+'&f='+factura;

        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }

    function generaPDFPagos(cliente, deuda){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/generaPagos.php?cl='+cliente+'&deuda='+deuda;

        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }

    function generaFacturaPDFPagos(cliente, deuda, factura){

        var ancho = 1000;
        var alto = 800;

        //Calcular posicion x, y para centrar la ventana
        var x = parseInt((window.screen.width/2) - (ancho /2));
        var y = parseInt((window.screen.height/2) - (alto /2));

        $url = 'factura/generaFacturaPagos.php?cl='+cliente+'&deuda='+deuda+'&f='+factura;

        window.open($url, "Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si, location=no, resizable=si, menubar=no");
    }




    function del_product_detalle(correlativo){
 
        var action = 'delProductoDetalle';
        var id_detalle = correlativo;
        var descuento = $('#txt_descuento').val();

        $.ajax({

            url: 'ajax.php',
            type: "POST",
            async : true,
            data : {action:action, id_detalle:id_detalle, descuento:descuento},
        
            success: function(response){
                
                if(response != 'error'){

                    var info = JSON.parse(response);

                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    $('#txt_cod_producto').val('');
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    //BLOQUEAR CANTIDAD
                    $('#txt_cant_producto').attr('disabled', 'disabled');

                    //OCULTAR BOTON AGREGAR
                    $('#add_product_venta').slideUp();

                }else{
                    $('#detalle_venta').html('');
                    $('#detalle_totales').html('');
                }
                viewProcesar();
            },
            error: function(error){
                console.log(error);
            }
        
            });
        

    }


        //Mostrar/Ocultar boton procesar
        function viewProcesar(){
            if($('#detalle_venta tr').length > 0){
                $('#btn_facturar_venta').show();
                $('#btn_facturar_venta_correo').show();

            }else{
                $('#btn_facturar_venta').hide();
                $('#btn_facturar_venta_correo').hide();
            }
        }

function searchForDetalle(id){
    var action = 'searchForDetalle';
    var user = id;
    var descuento = $('#txt_descuento').val();

    $.ajax({

        url: 'ajax.php',
        type: "POST",
        async : true,
        data : {action:action, user:user, descuento:descuento},
    
        success: function(response){
            
            if(response != 'error'){

                var info = JSON.parse(response);
                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);

            }else{
                console.log('no data');
            }
            viewProcesar();
        },
        error: function(error){
            console.log(error);
        }
    
        });
}

// ELIMINAR PRODUCTO
function deleteProduct(){

    var pr = $('#producto_id').val();
    $('.alertAddProduct').html('');

    $.ajax({

        url: 'ajax.php',
        type: 'POST',
        async: true,
        // envia todos los elementos(inputs) del form de forma serial
        data: $('#form_delete_product').serialize(),
    
    success: function(response){
    
        if(response == 'error'){
        
            $('.alertAddProduct').html('<p style="color: red;">Error al eliminar el producto.</p>')
        }else{
            
            $('.row'+pr).remove();
            $('#form_delete_product .btn_ok').remove();
            $('.alertAddProduct').html('<p>Producto eliminado correctamente.</p>');

        }
    },

    error: function(error){
        console.log(error);
    }

    });


}

function sendDataProduct(){


    $('.alertAddProduct').html('');

    $.ajax({

        url: 'ajax.php',
        type: 'POST',
        async: true,
        // envia todos los elementos(inputs) del form de forma serial
        data: $('#form_add_producto').serialize(),
    
    success: function(response){
        
        if(response == 'error'){
        
            $('.alertAddProduct').html('<p style="color: red;">Error al agregar el producto.</p>');
        }else{
            var info = $.parseJSON(response);
            $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
            $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
            $('#txtCantidad').val('');
            $('#txtPrecio').val('');
            $('.alertAddProduct').html('<p>Producto guardado correctamente.</p>');

        }


    },

    error: function(error){
        console.log(error);
    }

    });


}

function sendDataAbono(){

    $('.alertAddProduct').html('');

    if(parseInt($('#txtMonto').val()) > parseInt($('#info_montoPendiente').html())){
        $('#txtMonto').val('');
        $('.alertAddProduct').html('<p>El monto ingresado excede al monto pendiente de la deuda.</p>');
        }else{

    $.ajax({

        url: 'ajax.php',
        type: 'POST',
        async: true,
        // envia todos los elementos(inputs) del form de forma serial
        data: $('#form_add_abono').serialize(),

    success: function(response){
        
        if(response == 'error'){
        
            $('.alertAddProduct').html('<p style="color: red;">Error al registrar el abono.</p>');
        }else{
            var info = $.parseJSON(response);
            $('.row'+info.id+' .celMontoPendiente').html(info.nuevo_monto_pendiente);
            $('.row'+info.id+' .celSaldoAnterior').html(info.nuevo_saldo_anterior);
            $('#info_montoPendiente').html(info.nuevo_monto_pendiente);
            $('#txtMonto').val('');
            $('.alertAddProduct').html('<p>Abono generado correctamente.</p>');
            generaFacturaPDFPagos(info.idcliente, info.id, info.id_pago);
            if(info.nuevo_monto_pendiente == 0.00){
                $('.row'+info.id+' #estado').html('<span class="pagada">Pagada</span>');
                $('.row'+info.id+' #deudaPagada').html('<a class="link_add disabled" href="#"><i class="fa-solid fa-plus"></i> Registrar pago</a>');
                
            }
            }

    },

    error: function(error){
        console.log(error);
    }

    });
}

}

function facturar_pago_correo(){

    $('.alertAddProduct').html('');

    var id = $('#id').val();
    var codcliente = $('#codcliente').val();
    var monto = parseInt($('#txtMonto').val());
    var action = 'addAbono';

if(parseInt($('#txtMonto').val()) > parseInt($('#info_montoPendiente').html())){
    $('#txtMonto').val('');
    $('.alertAddProduct').html('<p>El monto ingresado excede al monto pendiente.</p>');
    }else{

$.ajax({

    url: 'ajax.php',
    type: 'POST',
    async: true,
    // envia todos los elementos(inputs) del form de forma serial
    data: {action:action, codcliente:codcliente, monto:monto, id:id},

success: function(response){
    
    if(response == 'error'){
    
        $('.alertAddProduct').html('<p style="color: red;">Error al registrar el abono.</p>');
    }else{
        var info = $.parseJSON(response);
        $('.row'+info.id+' .celMontoPendiente').html(info.nuevo_monto_pendiente);
        $('.row'+info.id+' .celSaldoAnterior').html(info.nuevo_saldo_anterior);
        $('#info_montoPendiente').html(info.nuevo_monto_pendiente);
        $('#txtMonto').val('');
        $('.alertAddProduct').html('<p>Abono generado correctamente.</p>');
        sendPDFPagos(info.idcliente, info.id, info.id_pago);
        if(info.nuevo_monto_pendiente == 0.00){
            $('.row'+info.id+' #estado').html('<span class="pagada">Pagada</span>');
            $('.row'+info.id+' #deudaPagada').html('<a class="link_add disabled" href="#"><i class="fa-solid fa-plus"></i> Registrar pago</a>');
            
        }
        }

},

error: function(error){
    console.log(error);
}

});
}
}


// CERRAR MODAL
function closeModal(){

    //Cerrar el recuadro
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}

// funcion para buscar proveedores

function getUrl(){
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}





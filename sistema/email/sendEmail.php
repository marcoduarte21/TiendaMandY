<?php 
        //LLAMAMOS LAS CLASES DE PHPMAILER
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use phpmailer\PHPMailer\Exception;


        // REQUERIMOS EL AUTOLOAD
        require 'vendor/autoload.php';


        //INSTANCIAS
        //TRABAJAR EXCEPCIONES
        $mail = new PHPMailer(true);

        try{
            //VER LAS EXCEPCIONES
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            //CONFIGURAR HOST CON GMAIL
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'duartemarcov@gmail.com';
            $mail->Password = 'kwepshrwkfaqetsj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            //CUENTA QUE ENVIARA LOS CORREOS
            $mail->setFrom('duartemarcov@gmail.com', 'MULTI-TIENDA M&Y');
            //CORREOS QUE RECIBEN
            $mail->addAddress('fabrimejillon@gmail.com', 'Fabricio Mejillon');
            //REPLICAS
            $mail->addCC('guadamuznic@hotmail.com');


            //ENVIAR DOCUMENTOS E IMAGENES
            $mail->addAttachment('docs/factura_48.pdf', 'M&Y_factura_48.pdf');

            //ENVIAR DATOS EN HTML
            $mail->isHTML(true);

            //ASUNTO Y CUERPO
            $mail->Subject = 'Prueba de M&Y';
            $mail->Body = 'Hola, <br>Te ha llegado un correo con la factura de tu compra de <b>Tienda M&Y</b>';
            $mail->send();

            echo 'Correo enviado con exito';


        } catch(Exception $e){

            echo 'Mensaje' . $mail->ErrorInfo;
        }
?>
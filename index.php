<?php

$alert = '';
session_start();
if(!empty($_SESSION['active'])){
    header('location: sistema/index.php');
} else {

    if (!empty($_POST)) {
        if (!empty($_POST['nombreDeUsuario']) || !empty($_POST['contrasena'])) {

            require_once "conexion.php";

            $user = mysqli_real_escape_string($conection, $_POST['nombreDeUsuario']);
            $password = md5(mysqli_real_escape_string($conection, $_POST['contrasena']));

            $query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.clave, u.rol as idrol, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.usuario = '$user' and u.clave = '$password';");
            mysqli_close($conection);
            $existe = mysqli_num_rows($query);

            if ($existe > 0) {
                $data = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['correo'] = $data['correo'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['clave'] = $data['clave'];
                $_SESSION['idrol'] = $data['idrol'];
                $_SESSION['rol'] = $data['rol'];

                header('location: sistema/');
            }else{
                $alert = 'Error al iniciar sesión: El nombre de usuario o la contraseña son incorrectos.';
                session_destroy();
            }


        } else {
            $alert = 'Datos vacíos: Ingrese un nombre de usuario y contraseña validos.';
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | M&Y</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://kit.fontawesome.com/9aac1473ee.js" crossorigin="anonymous"></script>
</head>
<body>
<h1><i class="fa-solid fa-shop"></i> Tienda M&Y</h1>
    <div class="center">
    <form action="" method="post">
        <h3><i class="fa-solid fa-lock"></i> Inicio de sesión</h3>
        <div class="text_field">
        <input type="text" name="nombreDeUsuario" id="nombreDeUsuario">
        <span></span>
        <label>Nombre de usuario</label>
        </div>
        <div class="text_field">
        <input type="password" name="contrasena" id="contrasena">
        <span></span>
        <label>Contraseña</label>
        </div>
        <div class="mensaje"><?php echo (isset($alert) ? $alert : '');?></div>
        <button type="submit">Iniciar sesión <i class="fa-solid fa-right-to-bracket"></i></button>
    </form>
    </div>
</body>
</html>
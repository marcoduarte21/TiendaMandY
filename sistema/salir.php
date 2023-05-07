<?php

    //destruir la sesion/salir

    session_start();
    session_destroy();

    //se regresa
    header('location: ../');

?>

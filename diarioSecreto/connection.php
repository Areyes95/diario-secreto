<?php
    $enlace = mysqli_connect("localhost", "root", "", "diario");
        if (mysqli_connect_error())
        {
            //Cancela la conecxion
            die("Hubo un error en la conexión, inténtelo más tarde");
        }
?>
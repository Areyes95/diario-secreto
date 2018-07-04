<?php
    session_start();
    $contenidoDiario = "";
    if (array_key_exists("id",$_COOKIE) && $_COOKIE['id']) {
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if (array_key_exists("id",$_SESSION) && $_SESSION['id']) {
        /* echo "<p>Sesión iniciada. <a href='registro.php?Logout=1'>Cerrar sesión</a></p>"; */
        /* echo "<p>Has iniciado sesión como ".$_SESSION['id']."</p>";
        echo "<p>Has iniciado sesión como ".$_COOKIE['id']."</p>"; */
        include("connection.php");
        $query = "SELECT us_diario FROM usuarios WHERE us_id = '".mysqli_real_escape_string($enlace, $_SESSION['id'])."' LIMIT 1";
        $result = mysqli_query($enlace, $query);
        $fila = mysqli_fetch_array($result);
        $contenidoDiario = $fila['us_diario'];
    }
    else {
        header("Location: registro.php");
    }
    include("header.php");
?>
    
<nav class="navbar fixed-top navbar-light bg-light justify-content-between">
    <a class="navbar-brand">Diario Secreto</a>
    <div class="form-inline">
        <a href="registro.php?Logout=1">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cerrar Sesión</button>
        </a>
    </div>
</nav>

<div class="container-fluid" id="contenedorSesionIniciada">
    <textarea name="" id="diario" class="form-control">
        <?php echo $contenidoDiario; ?>
    </textarea>
</div>



<?php include("footer.php"); ?>
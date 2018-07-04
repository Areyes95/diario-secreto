<?php
    session_start();
    $error = "";
    if(array_key_exists("Logout", $_GET))
    {
        //Viene de la página sesionIniciada
        session_unset();
        setcookie("id","",time()-60*60);
        $_COOKIE["id"]=""; //ExtraRefresco
    }
    else if ( (array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id']))
    {
        //Si ya tiene la sesion iniciada
        header("Location: sesionIniciada.php");
    }
    if(array_key_exists("submit", $_POST))
    {
        include("connection.php");

        if(!$_POST['email'])
        {
            $error .= "Email requerido.<br>";
        }
        if(!$_POST['password'])
        {
            $error .= "Contraseña  requerido.<br>";
        }
        if($error!="")
        {
            $error = "<p>Hubo algun(os) error(es) en el formulario:<br>".$error."</p>";    
        }
        else
        {
            if($_POST['registro'] == 1)
            {

                $query = "SELECT us_id FROM usuarios WHERE us_email = '".mysqli_real_escape_string($enlace, $_POST['email'])."' LIMIT 1";
                $result = mysqli_query($enlace, $query);
                if(mysqli_num_rows($result) > 0)
                {
                    $error = "Email ya registrado";
                }
                else
                {
                    $query = "INSERT INTO `usuarios` (`us_id`, `us_email`, `us_password`, `us_diario`) VALUES (NULL, '".mysqli_real_escape_string($enlace, $_POST['email'])."', '".mysqli_real_escape_string($enlace, $_POST['password'])."', NULL)";
                    if(!mysqli_query($enlace, $query))
                    {
                        $error = "<p>No hemos podido completar el registro, por favor inténtelo más tarde</p>";
                    }
                    else
                    {
                        //Actualizar el almacenamiento del password
                        $query = "UPDATE `usuarios` SET `us_password` = '".md5(md5(mysqli_insert_id($enlace)).$_POST['password'])."' WHERE `usuarios`.`us_id` = '".mysqli_insert_id($enlace)."' LIMIT 1";
                        mysqli_query($enlace,$query);
                        $_SESSION['id']=mysqli_insert_id($enlace);
                        if ($_POST['permanecerIniciada']=='1')
                        {
                            setcookie("id",mysqli_insert_id($enlace),time()+60*60*24*365);
                        }
                        header("Location: sesionIniciada.php");
                    }
                }
            }
            else
            {
                //Comprobacion de inicio de sesíon
                $query = "SELECT * FROM usuarios WHERE us_email = '".mysqli_real_escape_string($enlace, $_POST['email'])."'";
                $result = mysqli_query($enlace, $query);
                $fila = mysqli_fetch_array($result);
                if(isset($fila))
                {
                    $passwordHasheada = md5(md5($fila['us_id']).$_POST['password']);
                    if ($passwordHasheada == $fila['us_password'])
                    {
                        //Usuario autenticado
                        $_SESSION['id'] = $fila['us_id'];
                        if ($_POST['permanecerIniciada']=='1'){
                            setcookie("id",$fila['id'],time()+60*60*24*365);
                        }
                        header("Location: sesionIniciada.php");
                    }
                    else
                    {
                        $error = "El email/contraseña no pudieron ser encontrados!";
                       
                    }
                }
                else
                {
                    $error = "El email/contraseña no pudieron ser encontrados!";
                }
            }
        }
    }
?>
    <?php include("header.php");  ?>

        <div class="container" id="contenedorPaginaPrincipal">
            <h1>Diario Secreto</h1>
            <p><strong>Guarda tus pensamientos para siempre</strong></p>
            <div id="error">
                <?php
                    if ($error!=""){
                        echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
                    }
                ?>
            </div>

            <form method="POST" id="formularioRegistro">
                <p>¿Interesad@ Regístrate ahora !</p>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="" placeholder="Tu email">
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="" placeholder="Password">
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="permanecerIniciada" id="" value=1>Permanecer iniciada
                    </div>
                </div>
                <input type="hidden" name="registro" value=1>
                <input type="submit" class="btn btn-primary" name="submit" value="¡Registrate!">
                <p class="link"><a class="alternarFormularios">Iniciar sesión</a></p>
            </form>

            <form method="POST" id="formularioLogin">
                <p>Inicia sesión con tu usuario/contraseña</p>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="" placeholder="Tu email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="" placeholder="Password">
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="permanecerIniciada" id="" value=1>Permanecer iniciada
                    </div>
                </div>
                <input type="hidden" name="registro" value=0>
                <input type="submit" class="btn btn-primary" name="submit" value="Iniciar sesion">
                <p class="link"><a class="alternarFormularios">Regístrate</a></p>
                
            </form>
        </div>

    <?php include("footer.php");  ?>




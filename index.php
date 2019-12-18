<?php     
include("funciones.php");
//Si se ha pulsado el botón de enviar...
if(!isset($_COOKIE['intentos'])){
    setcookie("intentos",1);
}
else{
    setcookie("intentos",$_COOKIE['intentos']+1);
}   
if (isset($_POST['enviar'])) {     
    $usuario = $_POST['usuario'];         
    $password = $_POST['password'];   
    if (empty($usuario) || empty($password))  //Si el usuario o contraseña están vacíos...          
        $error = "Debes introducir un nombre de usuario y una contraseña";         
    else {                        
        // Conecxión a la base de datos             
        $conexion = conectarDB();           
        // Comprobación de credenciales             
        $sql = "SELECT usuario FROM usuarios " .         
        "WHERE usuario='$usuario' " .             
        "AND contrasena='" . md5($password) . "'";             
        if($resultado = ejecutarConsulta($conexion,$sql)) {                 
            $fila = $resultado->fetch();   
            if ($fila != null) { //Si la fila no está vacía (Existe el usuario)...             
                session_start(); //Iniciamos la sesión
                $_SESSION['usuario']=$usuario;  //Guardamos el usuario para comprobar más adelante que está logueado
                setcookie("intentos","",time()-1000);                  
                header("Location: productos.php"); //Redirigimos a la página de productos
            }                 
            else {                     
                // Si las credenciales no son válidas, se vuelven a pedir                     
                $error = "Introduce usuario y contraseña";                 
            }                 
        }                      
    }    
} 
?>
<html>
    <head>  
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">   
        <title>Ejercicio PHP</title>
        <link href="style.css" rel="stylesheet" type="text/css"> 
    </head>
    <body>
        <?php include("cabecera.php")?>
        <div id="main">
            <p><?php if(isset($_COOKIE["intentos"])) echo $_COOKIE["intentos"] ?></p>
            <div id='formulario'>
                <form id="form" action='index.php' method='post'>     
                    <!--Si se ha producido algún error se muestra -->
                    <span class='error'><?php if(isset($error)) echo $error; ?></span>
                    <p>             
                        <label for='usuario' >Usuario:</label><br/>             
                        <input type='text' name='usuario' id='usuario' maxlength="50" /><br/> 
                    </p> 
                    <p>
                        <label for='password' >Contraseña:</label><br/>
                        <input type='password' name='password' id='password'/><br/> 
                    </p> 
                    <p class='botones'>
                        <input type='submit' name='enviar' value='Enviar'/>
                    </p> 
                </form> 
            </div>
        </div>
    </body>
</html>
<?php 
include("funciones.php");
session_start();  // Recuperamos la información de la sesión 
if (!isset($_SESSION['usuario'])) { //Si el usuario no está autentificado...
    die("Error - debe <a href='index.php'>identificarse</a>.<br />");     
}
?> 
<html> 
    <head>   
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">   
    <title>Ejercicio PHP, Página de compra</title>
    <link href="style.css" rel="stylesheet" type="text/css"> 
    </head> 
    <body>
        <?php include("cabecera.php")?>
        <div class="productos">
            <div id="cesta">
                <h1><img src="cesta.png" alt="Cesta" width="32" height="28">Cesta</h1>
                <?php
                if (isset($_POST['vaciar'])) { //Si se ha pulsado el botón de vaciar la cesta...
                    unset($_SESSION['cesta']);     
                }      
                if (isset($_POST['enviar'])) {   //Si se ha pulsado el botón de compra... 
                    // Creamos un array con los datos del producto         
                    $producto['nombre'] = $_POST['nombre'];     
                    $producto['precio'] = $_POST['precio'];
                    $producto['codigo'] = $_POST['producto'];
                    if(isset($_SESSION['cesta'][$_POST['producto']])){ //Si ya está añadido el producto, sumamos una unidad a la cantidad
                        $producto['cantidad'] = $_SESSION['cesta'][$_POST['producto']]['cantidad'] + 1;
                    }
                    else{
                        $producto['cantidad'] = 1;
                    }
                    //  y lo añadimos         
                    $_SESSION['cesta'][$_POST['producto']] = $producto;
                }   
                    if (!isset($_SESSION['cesta'])) { // Si la cesta está vacía, mostramos un mensaje
                        print "<p>La cesta está vacía</p>";
                        $cesta_vacia = true; 
                    }
                    else {
                        print "<table><tr><th>Producto<th>Cantidad</th></tr>";
                        foreach ($_SESSION['cesta'] as $producto){   
                            print "<tr>";
                            print "<td>".$producto['nombre']."</td>";
                            print "<td>".$producto['cantidad']."</td>";
                            print "</tr>";
                        }
                        print "</table>";    
                        $cesta_vacia = false;
                    } 
                // Si no está vacía, mostrar su contenido    
                ?>
            <form id='vaciar' action='productos.php' method='post'>    
                <input type='submit' name='vaciar' value='Vaciar Cesta' <?php if($cesta_vacia) echo "disabled='true'"?>/>
            </form> 
            <form id='comprar' action='cesta.php' method='post'> 
                <input type='submit' name='comprar' value='Comprar' <?php if($cesta_vacia) echo "disabled='true'"?>/>
            </form>
            </div>
            <div id="productos">
            <h1>Listado de productos</h1>
            <div id="tabla"> 
            <?php
                $conexion = conectarDB(); //Conectamos a la base de datos para mostrar los productos
            if ($conexion) {
                $sql = "SELECT cod, nombre_corto, PVP FROM producto"; 
                $resultado = ejecutarConsulta($conexion,$sql);    
                if($resultado) { //Si existen productos...    
                    // Creamos un formulario por cada producto obtenido   
                    $row = $resultado->fetch();          
                    while ($row) { 
                        echo "<p><form action='productos.php' method='post'>";                 
                        // Metemos ocultos los datos de los productos
                        echo "<input type='hidden' name='producto' value='".$row['cod']."'/>"; 
                        echo "<input type='hidden' name='nombre' value='".$row['nombre_corto']."'/>";        
                        echo "<input type='hidden' name='precio' value='".$row['PVP']."'/>";
                        echo "<b>Nombre:</b>\n";       
                        echo " ${row['nombre_corto']}";  
                        echo "<br>";
                        echo "<b>Precio:</b>".$row['PVP']." euros.";
                        echo "<input type='submit' name='enviar' value='Añadir'/>";           
                        echo "</form>";                 
                        echo "</p>";
                        $row = $resultado->fetch();        
                    }
                }
                else{
                    echo "No hay productos en STOCK";
                }
            }
            ?>
            </div>    
            </div>
        </div>
            <br class="divisor" />   
            <div id="pie"> 
                <form action='desconectar.php' method='post'> 
                    <input   type='submit'   name='desconectar'   value='Desconectar'/> 
                </form>         
            </div> 
    </body>
</html>

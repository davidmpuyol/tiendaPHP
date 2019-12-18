<?php
    session_start(); // Recuperamos la información de la sesión 
    if (!isset($_SESSION['usuario'])) { //Comprobamos usuario autentificado 
        die("Error - debe <a href='index.php'>identificarse</a>.<br />"); 
    }
?> 
<html>
    <head>  
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">   
    <title>Ejercicio PHP, CESTA</title>   
    <link href="style.css" rel="stylesheet" type="text/css"> 
    </head>
    <body>
    <?php include("cabecera.php") ?>
        <div id="carrito">
            <div id="encabezado"> 
                <h1>Cesta de la compra</h1>   
            </div>  
            <div id="productos"> 
                <?php
                    $total = 0; //Inicializamos el total
                    echo "<table><tr><th>Nombre Producto</th><th>Cantidad</th><th>Precio</th></tr>";
                    foreach($_SESSION['cesta'] as $producto) {
                        echo "<tr>";
                        echo "<td>".$producto['nombre']."</td>";
                        echo "<td>".$producto['cantidad']."</td>";
                        echo "<td>".$producto['precio']*$producto['cantidad']."</td>";
                        $total += $producto['precio']*$producto['cantidad']; //Sumamos el precio de cada producto al total
                    }
                    echo "</table>";
                ?>       
                <p><b>Precio total: <?php print $total; ?> €</b></p> <!-- Mostramos el precio total de la factura -->
                <form action='pagar.php' method='post'>
                    <p>                                   
                        <input type='submit' name='pagar' value='Pagar'/> <!-- Mostramos el botón de pagar -->                
                    </p> 
                </form>                     
            </div>
        </div> 
    </body>
</html>
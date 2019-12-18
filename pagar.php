<?php
    include("funciones.php");
    // Recuperamos la información de la sesión 
    session_start();
    $conexion = conectarDB();
    $sql = "SELECT count(idPedido) from pedido"; //Comprobamos el número de pedidos para calcular el código del próximo pedido
    $resultado = ejecutarConsulta($conexion,$sql);
    $fila = $resultado->fetch();
    $idPedido = intval($fila["count(idPedido)"])+1; //El código del nuevo pedido es el número de pedidos existentes más 1
    $usuario = $_SESSION['usuario'];
    $fecha = date("Y-m-d, H:i:s");
    $sql = "INSERT INTO PEDIDO VALUES($idPedido,'$fecha','$usuario')";
    //Comenzamos la transacción
    try {
        $conexion->beginTransaction();
        $conexion->exec($sql); //Creamos el pedido
        $sentencia = $conexion->prepare("insert into lineaspedido values($idPedido,:cod,:cantidad)");
        $sentencia->bindParam(':cod', $codProducto);
        $sentencia->bindParam(':cantidad', $cantidad);
        foreach($_SESSION['cesta'] as $producto) {
            $codProducto = $producto['codigo'];
            $cantidad = $producto['cantidad'];
            $sentencia->execute();
        }
        $conexion->commit();
        $mensaje = "<b>Gracias por su compra.<br/><a href='productos.php'> Comprar de nuevo</a></b>";
    } catch(Exception $e) {
        $mensaje = "Error al insertar en la base de datos";
        $conexion->rollBack();
    }
    //Iniciamos la transacción para añadir a la base de datos el pedido y las líneas de pedido
    unset($_SESSION['cesta']); //vaciamos la cesta 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>EJERCICIO PHP,PAGAR</title>
</head>
<body>
    <?php include("cabecera.php")?>
    <div id="main">
        <?=$mensaje?>
    </div>
</body>
</html>

<?php
    function conectarDB(){
        try {
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");                 
            $dsn = "mysql:host=localhost;dbname=dwes";                 
            $dwes = new PDO($dsn, "davidmpuyol", "davidmpuyol", $opc);
            return $dwes;            
        }             
        catch (PDOException $e) {                 
            die("Error: " . $e->getMessage());  
        }   
    }

    function ejecutarConsulta($conexion,$sql){
        $resultado = $conexion->query($sql);
        return $resultado;
    }
?>
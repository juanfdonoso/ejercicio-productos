<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Directorio</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script src="https://kit.fontawesome.com/5637dd924f.js" crossorigin="anonymous"></script>
    <script src="./scripts.js"></script> 
</head>
<body>
<div class="header">
<h1>Productos</h1>
<button type="button" onClick="abrirModal()">Nuevo Producto</button>
</div>

<?php 
include "conexion.php";
?>


<section class="listaResultados">
    <div class = "contenedor" id="contenedor">
        <div class="titulo">Producto</div>
        <div class="titulo">Descripción</div>
        <div class="titulo">Precio</div>
        <div class="titulo">Fotos</div>
        
        
        
        
        <?php

        $sql = "select juanf_productos.*, juanf_fotoProductos.foto from juanf_productos LEFT join juanf_fotoProductos on juanf_productos.idProducto = juanf_fotoProductos.idProducto";

        $rs = ejecutar($sql);
        $k = 1;
        while ($datos = mysqli_fetch_array($rs)){
            if ($k % 2 == 0){
                echo '<div class="claro">'.$datos["producto"].'</div>';
                echo '<div class="claro">'.$datos["descripcion"].'</div>';
                echo '<div class="claro">$'.$datos["precio"].'</div>';
                echo '<div class="claro"><button type="button" class="boton"><i class="fas fa-plus-circle"></i></button>';
                echo $datos["foto"].'</div>';
            }else{
                echo '<div class="oscuro">'.$datos["producto"].'</div>';
                echo '<div class="oscuro">'.$datos["descripcion"].'</div>';
                echo '<div class="oscuro">$'.$datos["precio"].'</div>';
                echo '<div class="oscuro"><button type="button" class="boton"><i class="fas fa-plus-circle"></i></button>';
                echo $datos["foto"].'</div>';

            }
            $k++;
           
        }
        
        ?>  
        
    </div>

</section>




 
</body>
</html>
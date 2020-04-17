<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script src="https://kit.fontawesome.com/5637dd924f.js" crossorigin="anonymous"></script>
    <script src="./scripts.js"></script> 
</head>
<body>
<?php
if (isset($_SESSION["administrador"])){
?>
<div class="header">
<h1>Editar Producto</h1>
</div>

<?php
if (isset($_REQUEST["id"])){
    $id = $_REQUEST["id"];
    include "../conexion.php";
    $sql = "select * from juanf_productos where idProducto = ".$id;
    $sql2 = "select * from juanf_fotoProductos where idProducto = ".$id;
    $rs = ejecutar($sql);
    $rs2 = ejecutar($sql2);
    
    $dato = mysqli_fetch_array($rs);
   
?>
<div class="contenedorFormularioProducto">
    <form id="f1" method="post" action="editarProducto_xt.php" enctype="multipart/form-data">
    <input type="hidden" name="idProducto" value="<?php echo $id; ?>" />
    <div class="formularioProducto">
        <div class="titulos">Producto:</div>
        <div class="datos"><input type="text" class="campos" name="producto" id="producto" value="<?php echo $dato["producto"];?>" /></div> 
        
        <div class="titulos">Descripcion:</div>
        <div class="datos"><textarea class="campos" name="descripcion" rows="10" id="descripcion"><?php echo $dato["descripcion"];?></textarea> </div>
        
        <div class="titulos">Precio:</div>
        <div class="datos"><input type="text" class="campos" name="precio" id="precio" value="<?php echo $dato["precio"];?>" /> </div>
        
        <div class="titulos">Fotos:</div>
        <div class="datos">
            <?php
            while($fotos = mysqli_fetch_array($rs2)){
                echo '<img src="'.$ruta.$fotos["foto"].'" width="100" align="center"> ';
                echo '<input type="file" name="fotos[]" multiple="multiple"/> ';
                echo '<input type="hidden" name="idFotos[]" value="'.$fotos["idFotoProductos"].'" />';
                echo '<button type="button" class="boton" onClick="eliminarFoto('.$fotos["idFotoProductos"].')"><img src="iconos/trash.png"></button>';
                echo '<br><br>';
            }
            ?>
        </div>
        <div class="titulos"></div>
        <div class="datos">
            <button type="button" class="botonSubirFoto" onClick="validarEdicion()">Ingresar</button>
            <button type="button" class="botonSubirFoto" onClick="cancelarEdicion()">Cancelar</button>
        </div>
</div>


<?php
}else{
    echo "<script language='javascript'>";
    echo "window.location.assign('index.php');";
    echo "</script>";
}
?>
<?php
}else{
    echo '<script language="javascript">';
    echo 'window.location.assign("../admin/index.php");';
    echo '</script>';
}
?>    
</body>
</html>
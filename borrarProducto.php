<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if (isset($_SESSION["administrador"])){
    //checamos si se ha enviado el qs con el id del producto
    if (isset($_REQUEST['id'])){
        $idProducto = $_REQUEST["id"];
        include "../conexion.php";
        //buscamos todas las imágenes que tenga este producto
        $sql = "select * from juanf_fotoProductos where idProducto = ".$idProducto;
        $rs = ejecutar($sql);

        $errores = array();
        $j = 0;

        while($datos =  mysqli_fetch_array($rs)){
            //eliminamos el archivo del servidor
            if (unlink($ruta.$datos["foto"])){
                //eliminamos el registro de la foto de la BD
                $sql = "delete from juanf_fotoProductos where idFotoProductos = ".$datos["idFotoProductos"];
                $nada = ejecutar($sql);
                $errores[$j] = 0;

            }else{
                $errores[$j] = 1;
            }
            $j++;
        }

        // checamos si hay algún error el momento de eliminar las fotos del producto. En caso que exista
        // no podemos eliminar el producto de la BD
        $flag = true;
        for ($i = 0; $i < count($errores); $i++){
            if ($errores[$i] == 1) $flag = false;
        }

        //checamos la bandera para eliminar el producto de la BD
        if ($flag){
             //no hubo errores. Ahora checamos si se han realizado ventas con este producto. En caso que haya ventas
            //previas con el producto, éste no se puede eliminar de la BD
            $sql = "select * from juanf_ventas where idProducto = ".$idProducto;
            $rs = ejecutar($sql);

            if (mysqli_num_rows($rs) != 0){
                //hay ventas de este producto. No se puede eliminar. Redireccionamos la página a index con un QS de error
                echo "<script language='javascript'>";
                echo "window.location.assign('index.php?error=productoVenta');";
                echo "</script>";

            }else{
                //no hubo errores. No hay ventas. Eliminamos el producto
                $sql = "delete from juanf_productos where idProducto = ".$idProducto;
                $nada = ejecutar($sql);
                //redireccionamos a productos con un QS indicando que se eliminó el producto
                
                echo "<script language='javascript'>";
                echo "window.location.assign('index.php?edicion=borrar');";
                echo "</script>";
            }

        }else{
            //hubo errores, redireccionamos a producto con un QS
            echo "<script language='javascript'>";
            echo "window.location.assign('index.php?error=producto');";
            echo "</script>";

        }




    }else{
        //redireccionamos a productos
        echo "<script language='javascript'>";
        echo "window.location.assign('index.php');";
        echo "</script>";
    }

}else{
    echo '<script language="javascript">';
    echo 'window.location.assign("../admin/index.php");';
    echo '</script>';
}
?>    
</body>
</html>
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
    //checamos si se ha enviado el id de la foto a eliminar
    if (isset($_REQUEST['id'])){
        $idFotoEliminar = $_REQUEST["id"];
        //rescatamos el nombre de la foto
        include "../conexion.php";
        $sql = "select foto from juanf_fotoProductos where idFotoProductos = ".$idFotoEliminar;
        $rs = ejecutar($sql);
        $dato = mysqli_fetch_array($rs);
        $fotoEliminar = $dato["foto"];

        //eliminamos la imagen del servidor
        if (unlink($ruta.$fotoEliminar)){
            //borramos el registro de la foto de la BD
            $sql = "delete from juanf_fotoProductos where idFotoProductos = ".$idFotoEliminar;
            $nada = ejecutar($sql);

            //redireccionamos a productos con un querystring indicando que se eliminó la foto
            echo "<script language='javascript'>";
            echo "window.location.assign('index.php?foto=borrar');";
            echo "</script>";

        }



    }else{
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
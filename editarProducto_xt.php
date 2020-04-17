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
    //checamos si se envió el formulario
    if (isset($_POST["idProducto"])){
        include "../conexion.php";

        $idProducto = $_POST["idProducto"];
        $producto = $_POST["producto"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];

        //veamos cuántas fotos se enviaron
        $nFotos = count($_FILES["fotos"]["name"]);

        /* 1. actualizamos los textos del producto */
        $sql = "update juanf_productos set producto = '$producto', descripcion = '$descripcion', precio = $precio where idProducto = ".$idProducto;
        $nada = ejecutar($sql);

        /* 2 checamos si hay imágenes enviadas, y las recuperamos almacenando los datos en arreglos */
        if ($nFotos == 0){
            // nada más que hacer, redireccionamos a productos con un querystring que indique que se editó el producto
            echo "<script language='javascript'>";
            echo "window.location.assign('index.php?edicion=yes');";
            echo "</script>";
            
        }else{
            //leemos las fotos enviadas y almacenamos sus datos y los ids de las fotos a reemplazar en arreglos
            $idFotos = array();
            $nombreFotos = array();
            $tipoFotos = array();
            $tamanoFotos = array();
            $tempFotos = array();

            //declaramos una variable índice para controlar cómo se guardan los datos en estos arreglos
            $j = 0;

            for ($i=0; $i<$nFotos; $i++){
                if ($_FILES["fotos"]["name"][$i] != ""){
                    $idFotos[$j] = $_POST["idFotos"][$i];
                    $nombreFotos[$j] = $_FILES["fotos"]["name"][$i];
                    $tipoFotos[$j] = $_FILES["fotos"]["type"][$i];
                    $tamanoFotos[$j] = round($_FILES["fotos"]["size"][$i]/1024);
                    $tempFotos[$j] = $_FILES["fotos"]["tmp_name"][$i];
                    

                    $j++;
                }
            }

            /* revisamos cada archivo enviado para ver si cumple las condiciones para subir al servidor. De lo contrario
            generamos un error*/
            $error = array();

            for ($i=0; $i <  $j; $i++){
                $error[$i] = 0;

                //checamos tipo
                if ($tipoFotos[$i] != "image/jpeg" && $tipoFotos[$i] != "image/jpg" && $tipoFotos[$i] != "image/png"){
                    $error[$i] = 1;
                }

                //checamos tamaño
                if ($tamanoFotos[$i] > 500000){
                    $error[$i] = 2;
                }

                //checamos que no haya errores
                if ($error[$i] == 0){
                    // todo bien. Buscamos el nombre del archivo a reemplazaro
                    $sql = "select foto from juanf_fotoProductos where idFotoProductos = ".$idFotos[$i];
                    $rs = ejecutar($sql);
                    $d = mysqli_fetch_array($rs);
                    $fotoParaCambiar = $d["foto"];

                    //borramos la foto del servidor
                    if (unlink($ruta.$fotoParaCambiar)){
                        //borramos el nombre del archivo de la BD
                        $sql = "delete from juanf_fotoProductos where idFotoProductos = ".$idFotos[$i];
                        $nada = ejecutar($sql);

                        //subimos ahora la nueva foto al servidor
                        $nombreFinal = $idProducto."_".$nombreFotos[$i];
                        $archivoFinal = $ruta.$nombreFinal;
                        if (move_uploaded_file($tempFotos[$i], $archivoFinal)){
                            $sql = "insert into juanf_fotoProductos(idProducto, foto) values($idProducto, '$nombreFinal')";
                            $nada = ejecutar($sql);

                        }else{
                            //no se pudo subir
                            $error[$i] = 4;
                        }

                    }else{
                        // no se pudo borrar, generamos un error
                        $error[$i] = 3;
                    }
                }


            } /* aquí termina el lazo for que checa cada archivo enviado y lo reemplaza en el servidor y BD */

            /* checar el arreglo de errores para mandar un cierto mensaje como qs a la página de productos */
            $flag = true;
            for ($i=0; $i < $j; $i++){
                if ($error[$i] != 0){
                    $flag = false;
                    $e = $error[$i];
                } 
            }

            if ($flag){
                //reenviamos a la página de productos indicando que el proceso fue exitoso
                echo "<script language='javascript'>";
                echo "window.location.assign('index.php?edicion=yes');";
                echo "</script>";

            }else{
                //reenviamos la páginaa de productos indicando que hubo algún error en algún archivo
                echo "<script language='javascript'>";
                echo "window.location.assign('index.php?error=varios&e=".$e."');";
                echo "</script>";

            }
  

        }

    }else{
        //reenviamos al página de productos
        echo "<script language='javascript'>";
        echo "window.location.assign('index.php');";
        echo "</script>";
    }

}else{
    //redireccionamos a la págin de login
    echo "<script language='javascript'>";
    echo "window.location.assign('../admin/index.php');";
    echo "</script>";
}
?>
</body>
</html>
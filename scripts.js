function abrirModal(){
    document.getElementById("vModal").style.display="block";
    ocument.getElementById("msj").innerHTML="";

}

function cerrarModal(){
    document.getElementById("vModal").style.display="none";

}

function subirFoto(id){
    // ir a la página subirFotos.php con el id del producto como querystring
    window.location.assign("subirFotos.php?id="+id);
}

function editarProducto(id){
    // ir a la página editarProducto.php con el id del producto como querystring
    window.location.assign("editarProducto.php?id="+id);
}

function borrarProducto(id){
    var r = confirm("Desear realmente borrar este producto y sus fotos de la BD?");
    if (r){
        // ir a la página borrarProducto.php con el id del producto como querystring
        window.location.assign("borrarProducto.php?id="+id); 
    } 
}

function cancelarEdicion(){
    //redireccina a la página index
    window.location.assign("index.php");
}

function eliminarFoto(id){
    var r = confirm("Desea realmente eliminar esta foto del producto?");
    if (r){
        //redireccionamos a la página eliminarFoto_xt.php
        window.location.assign("eliminarFoto_xt.php?id="+id);
    }
}

function validarEdicion(){
    //leemos los campos obligatorios del formulario
    var producto = document.getElementById("producto").value;
    var descripcion = document.getElementById("descripcion").value;
    var precio = document.getElementById("precio").value;

    if (producto == "" || descripcion == "" || precio == ""){
        alert("El nombre del producto, su descripción y el precio son obligatorios");
    }else{
        //hacemos submit
        document.getElementById("f1").submit();
    }

}

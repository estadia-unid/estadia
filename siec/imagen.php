<?php
date_default_timezone_set('America/Mexico_City');
  $conecta =  mysqli_connect('localhost', 'root', 'ctpalm2113', 'rescatare');
  if(!$conecta){
      die('no pudo conectarse:' . mysqli_connect_error());
   }
if (!mysqli_set_charset($conecta,'utf8')) {
 die('No pudo conectarse: ' . mysqli_error($conecta));
 }

$idImagen = "9AVB3";
$resultado = mysqli_query($conecta, "SELECT imagen FROM imagenes WHERE idImagen = '$idImagen'");

if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);
    $foto = $fila['imagen']; // 'imagen' debe ser el nombre correcto de la columna

    if ($foto) {
        // https://www.php.net/manual/en/function.imagejpeg.php
        // https://www.superprof.es/blog/tecnica-codigo-foto-programacion/
        // https://www.php.net/manual/es/function.header.php 
        header("Content-Type: image/jpeg");
        echo $foto;
    } else {
        echo "No imagen.";
    }
} else {
    echo "error chavo" . mysqli_error($conecta);
}
mysqli_close($conecta);
?>

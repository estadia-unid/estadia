<?php
include ".../conexion.php";
if(mysqli_set_charset($conecta,'utf8')){
    echo "si se conecto uwu";
    }
else{
  echo "no se conecto";
}
echo "hola";

?>
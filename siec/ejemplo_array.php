<?php
function hola() {
    echo "hola";
}
function adios() {
    echo "adios";
}
function palta(){
    echo "palta";
}
$var = "hola";
switch($var){
    case "hola":
        hola();
        break;
    
    case "adios":
        adios();
        break;

    case "palta":
        palta();
        break;
        
    default:
    echo "lo siento, esta vacia";        
    }

?>
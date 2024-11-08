<?php
$contraseña = "uwu";
$contraseñaCifrada = password_hash($contraseña, PASSWORD_BCRYPT);
echo $contraseñaCifrada;
?>
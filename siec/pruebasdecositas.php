<?php
$contraseña = "123R";
$contraseñaCifrada = password_hash($contraseña, PASSWORD_BCRYPT);

echo 'contraseña sin cifrar:     '. $contraseña . '<br>';
echo 'contraseña cifrada:    ' . $contraseñaCifrada;
?>
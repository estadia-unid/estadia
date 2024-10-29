<?php
spl_autoload_register(function ($nombre_clase) {
    include $nombre_clase . '.php';
});
// https://www.php.net/manual/es/language.oop5.autoload.php
?>
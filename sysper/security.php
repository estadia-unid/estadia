<?php
session_start();

// Asegurarse de que la cookie de sesi車n est谷 activa por 24 horas
setcookie(session_name(), session_id(), time() + 86400, "/");

// Verificar si la sesi車n est芍 autenticada
if (!isset($_SESSION["autentica"]) || $_SESSION["autentica"] != "SIP") {
    // Redirigir al usuario a la p芍gina de inicio de sesi車n si no est芍 autenticado
    header("Location: login.php");
    exit();
}
?>

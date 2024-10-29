<?php
include_once "conexion.php";

class ControlSesiones{
    public $usuario;
    public $contraseña;

    function __construct($usuario, $contraseña) {
        $this->usuario = $usuario;
        $this->contraseña = $contraseña; 
    }

    function iniciar_sesion($conecta) {
        $sesion = "SELECT `rpe` FROM `usuarios` WHERE `rpe` = :usuario AND `clave` = :clave";
        $resultado_sesion = $conecta->prepare($sesion);
        
        $resultado_sesion->execute([
            ':usuario' => $this->usuario,
            ':clave' => $this->contraseña
        ]);
        
        if ($rpe = $resultado_sesion->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['rpe'] = $rpe['rpe'];
            header("Location: principal.php");
            die();
        } else {
            echo "No se encontró un usuario que coincida con los datos ingresados.<br>";
        }
    }

    function cerrar_sesion() {
        session_unset();
        session_destroy();
        echo "Sesión cerrada.";
    }
}
?>

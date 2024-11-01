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
        $sesion = "SELECT * FROM `usuarios` WHERE `rpe` = :usuario";

        $resultado_sesion = $conecta->prepare($sesion);
        
        $resultado_sesion->execute([':usuario' => $this->usuario]);
        $hash = $resultado_sesion->fetch(PDO::FETCH_ASSOC);
            if (password_verify($this->contraseña, $hash['clave'])) {
                $_SESSION['rpe'] = $hash['rpe'];
                header("Location: principal.php");
                die();
            } else {
                 echo 'Invalid password.';
            }
    }

    function cerrar_sesion() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        die();
    }

    function agregar_usuario(){
        echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT)."\n";
        if (password_verify('Alfredo Paz', $password_base_datos)) {
            echo 'Es correcta tu contraseña';
        } else {
            echo 'No es correcta tu contraseña';
        }
    }
}
if(isset($_GET['cerrarSesion'])){
    $controlSesion = new ControlSesiones(null, null);
    $controlSesion->cerrar_sesion();
}
// https://www.php.net/manual/es/function.password-verify.php
// https://www.php.net/manual/es/function.password-hash.php
?>

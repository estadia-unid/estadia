<?php
    session_start();
     $_SESSION['rpe'] = $_POST['rpe'];
        if(isset($_SESSION['rpe'])){
            echo "sesion iniciada como:". $_SESSION['rpe'];
            header("Location: imagen.php");
            exit();
    }
?>
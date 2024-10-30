<?php
echo password_hash('rasmuslerdorf', PASSWORD_DEFAULT)."\n";

$hash = '$2y$10$d.WS6U2JG5zo9SjRc6dZ0eMNirAFAhBmT1F4/VfdEIETMaG4sl1vK';
if (password_verify('rasmuslerdorf', $hash)) {
    header("Location: principal.php");
    die();
} else {
    echo 'Invalid password.';
}
?>
<?php
// logout.php
session_start();

// Limpiamos todas las variables de la sesión
$_SESSION = array();

// Destruimos la sesión
session_destroy();

// Redirigimos al usuario a la pantalla de login
header('Location: login.php');
exit;
?>
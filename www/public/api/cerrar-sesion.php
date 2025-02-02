<?php
require_once "../../private/usuarioController.php";

if (isset($_SESSION['id'])) {
    cerrar_sesion();
} else {
    header("Location: ../login.php");
    exit();
}
?>
<?php
require_once "../../private/usuarioController.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        if (!empty($_POST["email"]) && !empty($_POST["password"])) {
            if (iniciar_sesion($_POST["email"], $_POST["password"])) {
                header("Location: ../index.php");
                exit();
            } else {
                header("Location: ../login.php?error=2");
                exit();
            }
        } else {
            header("Location: ../login.php?error=1");
            exit();
        }
    } else {
        header("Location: ../login.php?error=1");
        exit();
    }
} else {
    header("Location: ../login.php?error=1");
    exit();
}
?>
<?php
include('utilities.php');
$debug = false;
$pdo = conexion();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = trim($_POST["email"]);
        $contrasena = trim($_POST["password"]);

        if (empty($email) || empty($contrasena)) {
            header("Location: login.php?error=1");
            exit();
        }

        // Uso de consultas preparadas para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE email = :email AND password = SHA2( :password, 256) LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $contrasena, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($user) {
            ini_set('session.gc_maxlifetime', 6*3600);
            session_set_cookie_params(6*3600);
            session_start();
            //session_name($user[0]["nombre"]."login");

            $_SESSION['last_access'] = date("Y-m-d H:i:s");
            $_SESSION['autentica']="TRUE";
            $_SESSION['id'] = $user[0]["id_usuario"];
            $_SESSION['nombre'] = $user[0]["nombre"];
            $_SESSION['correo'] = $email;
            $_SESSION['password'] = $user[0]["password"];
            $_SESSION['rol'] = $user[0]['rol'];
            $_SESSION['f_reg'] = $user[0]['fecha_registro'];

            if ($debug) {
                echo $_SESSION['nombre'];
                echo $_SESSION['correo'];
                echo $_SESSION['password'];
                echo $_SESSION['rol'];
                echo $_SESSION['f_reg'];
            }

            header("Location: index2.php");
            exit();
        } else {
            header("Location: login.php?error=2");
            exit();
        }
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
?>
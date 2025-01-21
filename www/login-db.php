<?php
include('utilities.php');
$debug = false;
$pdo = conexion();
session_start();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = trim($_POST["email"]);
        $contrasena = trim($_POST["password"]);

        if (empty($email) || empty($contrasena)) {
            header("Location: login.php?error=1");
            exit();
        }
        try {
            $sql = "SELECT * FROM Usuarios WHERE email = :email AND password = SHA2( :password, 256) LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $contrasena, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                //ini_set('session.gc_maxlifetime', 6*3600);
                //session_set_cookie_params(6*3600);
                
                $_SESSION['last_access'] = date("Y-m-d H:i:s");
                $_SESSION['id'] = $user["id_usuario"];
                $_SESSION['user'] = $user["nombre"];
                $_SESSION['correo'] = $email;
                $_SESSION['password'] = $user["password"];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['f_reg'] = $user['fecha_registro'];
    
                if ($debug) {
                    echo $_SESSION['user'];
                    echo $_SESSION['correo'];
                    echo $_SESSION['password'];
                    echo $_SESSION['rol'];
                    echo $_SESSION['f_reg'];
                }
    
                header("Location: index.php");
                exit();
            } else {
                header("Location: login.php?error=2");
                exit();
            }
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
?>
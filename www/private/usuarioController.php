<?php
include('databaseController.php');

function imprimeUsuarios($pdo)
{
    $users = $pdo->query("SELECT * FROM Usuarios")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users as $user) {
        echo $user["nombre"] . "<br>";
        echo $user["email"] . "<br>";
        echo $user["password"] . "<br><br>";
    }
}


function iniciar_sesion($correo, $cont)
{
    $pdo = conexion();
    $email = trim($correo);
    $contrasena = trim($cont);

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

            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        cerrarConexion($pdo);
        echo "Error: " . $e->getMessage();
    }
}
function cerrar_sesion()
{
    session_start();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

function registro_usuario()
{
    $pdo = conexion();
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["nombre"])) {
            $nombre = trim($_POST["nombre"]);
            $email = trim($_POST["email"]);
            $contrasena = trim($_POST["password"]);

            if (empty($nombre)) { // Para un mejor fronted separar por caso y dar mensaje para cada validacion
                header("Location: ../registro.php?error=1");
                exit();
            }
            if (empty($email)) {
                header("Location: ../registro.php?error=2");
                exit();
            }
            if (empty($contrasena)) {
                header("Location: ../registro.php?error=3");
                exit();
            }

            $sql = "SELECT COUNT(*) FROM Usuarios WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                header("Location: ../registro.php?error=4");
                exit();
            }

            try {
                $sql = "INSERT INTO Usuarios (nombre, email, password, rol) VALUES (:nombre, :email, SHA2( :password, 256), 'cliente')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $contrasena, PDO::PARAM_STR);
                $stmt->execute();


                header("Location: ../login.php");

            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    echo "El correo ya está registrado";
                } else {
                    echo "Error: " . $e->getMessage();
                }
            }
        } else {
            header("Location: ../registro.php?error=1");
            exit();
        }
    }
}
?>
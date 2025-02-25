<?php
session_start();
include 'db_connection.php'; // Incluir el archivo de conexión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirigir si no está autenticado
    exit();
}

// Suponiendo que has almacenado el ID del usuario en la sesión
$userId = $_SESSION['user_id']; // Asegúrate de haber guardado esto al iniciar sesión

// Consulta para obtener los datos del usuario desde la tabla Usuario
$sql = "SELECT * FROM Usuario WHERE id_Usuario = ? LIMIT 1"; // Corregido aquí
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId); // Asegúrate de que estás vinculando correctamente el tipo

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc(); // Obtener datos del usuario
} else {
    echo "No se encontraron datos para este usuario.";
    exit();
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($userData['nombre']); ?></h1>
        <p>Correo: <?php echo htmlspecialchars($userData['correo']); ?></p>
        <!-- Puedes agregar más información aquí -->
    </div>
</body>
</html>

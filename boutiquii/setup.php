<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'boutique';
$user = 'root';
$password = '';

try {
    // Conexión inicial sin seleccionar la base de datos para crearla si no existe
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);
    echo "Base de datos '$dbname' creada exitosamente.<br>";

    // Conectar a la base de datos 'boutique' una vez creada
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creación de tablas
    $queries = [
        "CREATE TABLE IF NOT EXISTS clientes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100),
            telefono VARCHAR(15),
            email VARCHAR(100)
        )",
        "CREATE TABLE IF NOT EXISTS proveedores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100),
            telefono VARCHAR(15),
            direccion VARCHAR(255)
        )",
        "CREATE TABLE IF NOT EXISTS inventarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            producto VARCHAR(100),
            cantidad INT,
            precio DECIMAL(10,2)
        )",
        "CREATE TABLE IF NOT EXISTS pedidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            producto VARCHAR(255) NOT NULL,
            cantidad INT NOT NULL,
            precio DECIMAL(10, 2) NOT NULL,
            fecha_pedido DATE NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS ventas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            producto VARCHAR(255) NOT NULL,
            cantidad INT NOT NULL,
            precio DECIMAL(10, 2) NOT NULL,
            fecha_venta DATE NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    // Ejecutar cada consulta para crear las tablas
    foreach ($queries as $query) {
        $pdo->exec($query);
    }

    echo "Tablas creadas exitosamente.<br>";

    // Inserta usuarios de ejemplo con contraseñas encriptadas
    $usuarios = [
        'nikte' => 'PUDINESDEFRESA',
        'josueito' => 'PANESDEPUDIN'
    ];

    foreach ($usuarios as $username => $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password, role) VALUES ('$username', '$hashed_password', 'admin')";
        $pdo->exec($sql);
        echo "Usuario $username creado correctamente.<br>";
    }
} catch (PDOException $e) {
    echo "Error en la conexión o creación de la base de datos: " . $e->getMessage();
}
?>

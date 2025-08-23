<?php
header("Content-Type: application/json");

// Получение переменных окружения
$appName = getenv('APP_NAME') ?: 'PHP Application';
$environment = getenv('ENVIRONMENT') ?: 'development';
$version = getenv('VERSION') ?: '1.0.0';

// Подключение к MySQL
$dbHost = getenv('DB_HOST') ?: 'mysql';
$dbName = getenv('DB_NAME') ?: 'appdb';
$dbUser = getenv('DB_USER') ?: 'appuser';
$dbPass = getenv('DB_PASS') ?: 'password';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание таблицы если не существует
    $pdo->exec("CREATE TABLE IF NOT EXISTS visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45),
        user_agent TEXT,
        visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Запись посещения
    $stmt = $pdo->prepare("INSERT INTO visits (ip_address, user_agent) VALUES (?, ?)");
    $stmt->execute([$_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
    
    // Получение количества посещений
    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM visits");
    $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $dbStatus = "connected";
} catch (PDOException $e) {
    $dbStatus = "disconnected";
    $count = 0;
}

// Формирование ответа
$response = [
    'app_name' => $appName,
    'environment' => $environment,
    'version' => $version,
    'database' => $dbStatus,
    'visits_count' => $count,
    'hostname' => gethostname(),
    'timestamp' => date('c'),
    'client_ip' => $_SERVER['REMOTE_ADDR']
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
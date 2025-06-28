<?php
// db.php - Arquivo de conexão com o banco de dados

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'deposito');
define('DB_USER', 'usuario_deposito'); // Não use 'root' em produção!
define('DB_PASS', 'senha_segura_aqui'); // Use uma senha forte

// Tentativa de conexão
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
        ]
    );
} catch (PDOException $e) {
    // Log do erro (em produção, registre em um arquivo de log)
    error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
    
    // Mensagem amigável para o usuário
    die("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Função para sanitizar entradas
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Função para redirecionamento seguro
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

// Verificar se o banco de dados está configurado corretamente
function checkDatabaseSetup($pdo) {
    try {
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (empty($tables)) {
            die("O banco de dados está vazio. Execute o script SQL de criação das tabelas.");
        }
    } catch (PDOException $e) {
        die("Erro ao verificar as tabelas do banco de dados: " . $e->getMessage());
    }
}

// Executar verificação (opcional)
checkDatabaseSetup($pdo);

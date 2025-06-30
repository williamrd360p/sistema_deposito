<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    echo "ID do pedido não fornecido.";
    exit;
}

$id_pedido = $_GET['id'];

// Verifica se o pedido existe
$stmt = $pdo->prepare("SELECT * FROM tb_pedido WHERE id_pedido = ?");
$stmt->execute([$id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Atualiza o status do pedido para "Cancelado"
$updateStmt = $pdo->prepare("UPDATE tb_pedido SET nm_status_pedido = 'Cancelado' WHERE id_pedido = ?");
$updateStmt->execute([$id_pedido]);

// Redireciona para a lista de pedidos
header("Location: pedidos.php");
exit;
?>

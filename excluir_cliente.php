<?php
include 'conexao.php';
 
if (!isset($_GET['id'])) {
    header('Location: clientes.php');
    exit;
}
 
$id = $_GET['id'];
 
try {
    // Verificar se o cliente tem pedidos associados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_pedido WHERE fk_cliente = ?");
    $stmt->execute([$id]);
    $pedidos = $stmt->fetchColumn();
 
    if ($pedidos > 0) {
        header('Location: clientes.php?error=O cliente possui pedidos associados e não pode ser excluído');
        exit;
    }
 
    // Se não tiver pedidos, excluir o cliente
    $stmt = $pdo->prepare("DELETE FROM tb_cliente WHERE id_cliente = ?");
    $stmt->execute([$id]);
    header('Location: clientes.php?success=1');
    exit;
} catch (PDOException $e) {
    header('Location: clientes.php?error=' . urlencode($e->getMessage()));
    exit;
}
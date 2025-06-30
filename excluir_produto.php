<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    header('Location: produtos.php');
    exit;
}

$id = $_GET['id'];

try {
    // Verificar se o produto está associado a pedidos NÃO cancelados
    $stmtPedidos = $pdo->prepare("
        SELECT COUNT(*) 
        FROM fk_produtos_pedidos fpp
        JOIN tb_pedido p ON fpp.fk_pedido = p.id_pedido
        WHERE fpp.fk_produto = ? AND p.nm_status_pedido != 'Cancelado'
    ");
    $stmtPedidos->execute([$id]);
    $emPedidosNaoCancelados = $stmtPedidos->fetchColumn();

    if ($emPedidosNaoCancelados > 0) {
        header('Location: produtos.php?error=O produto está em pedidos ativos/concluídos e não pode ser excluído');
        exit;
    }

    // Verificar se o produto está associado a clientes (caso você ainda use isso)
    $stmtClientes = $pdo->prepare("SELECT COUNT(*) FROM fk_produtos_clientes WHERE fk_produto = ?");
    $stmtClientes->execute([$id]);
    $emClientes = $stmtClientes->fetchColumn();

    if ($emClientes > 0) {
        header('Location: produtos.php?error=O produto está associado a clientes e não pode ser excluído');
        exit;
    }

    $pdo->beginTransaction();

    // Excluir do estoque
    $stmtEstoque = $pdo->prepare("DELETE FROM tb_estoque WHERE fk_produto = ?");
    $stmtEstoque->execute([$id]);

    // Excluir movimentações
    $stmtMov = $pdo->prepare("DELETE FROM tb_mov_estoque WHERE fk_produto = ?");
    $stmtMov->execute([$id]);

    // Excluir o relacionamento com pedidos cancelados (se desejar limpar isso também)
    $stmtVinculosPedidos = $pdo->prepare("
        DELETE fpp FROM fk_produtos_pedidos fpp
        JOIN tb_pedido p ON fpp.fk_pedido = p.id_pedido
        WHERE fpp.fk_produto = ? AND p.nm_status_pedido = 'Cancelado'
    ");
    $stmtVinculosPedidos->execute([$id]);

    // Excluir produto
    $stmt = $pdo->prepare("DELETE FROM tb_produtos WHERE id_produto = ?");
    $stmt->execute([$id]);

    $pdo->commit();
    header('Location: produtos.php?success=1');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: produtos.php?error=' . urlencode($e->getMessage()));
    exit;
}

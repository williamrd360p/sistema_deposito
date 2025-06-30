<?php 
include 'conexao.php';

// Verifique se o ID do pedido foi passado na URL
if (!isset($_GET['id'])) {
    echo "Pedido não encontrado.";
    exit;
}

$id_pedido = $_GET['id'];

// Obtenha os dados do pedido usando o ID
$stmt = $pdo->prepare("SELECT p.*, c.nm_cliente, c.id_cliente 
                       FROM tb_pedido p
                       JOIN tb_cliente c ON p.fk_cliente = c.id_cliente 
                       WHERE p.id_pedido = ?");
$stmt->execute([$id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifique se o pedido foi encontrado
if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Se o formulário for enviado, atualize os dados do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'];
    $status = $_POST['status'];
    $total = $_POST['total'];
    
    // Atualize os dados no banco de dados
    $updateStmt = $pdo->prepare("UPDATE tb_pedido SET fk_cliente = ?, nm_status_pedido = ?, nr_total_pedidos = ? WHERE id_pedido = ?");
    $updateStmt->execute([$cliente, $status, $total, $id_pedido]);

    // Redirecionar para a lista de pedidos após a edição
    header("Location: pedidos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Pedido</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Editar Pedido</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="text-primary font-medium">Pedidos</a>
</nav>
</header>

<!-- Formulário de Edição -->
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<form method="POST">
    <div class="mb-4">
        <label for="cliente" class="block text-sm font-medium text-gray-300">Cliente</label>
        <select name="cliente" id="cliente" class="w-full p-2 bg-gray-700 text-white rounded">
            <?php
            // Obter lista de clientes
            $clientesStmt = $pdo->query("SELECT * FROM tb_cliente ORDER BY nm_cliente");
            while ($cliente = $clientesStmt->fetch(PDO::FETCH_ASSOC)):
            ?>
                <option value="<?= $cliente['id_cliente'] ?>" <?= $cliente['id_cliente'] == $pedido['fk_cliente'] ? 'selected' : '' ?>>
                    <?= $cliente['nm_cliente'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
        <select name="status" id="status" class="w-full p-2 bg-gray-700 text-white rounded">
            <option value="Pendente" <?= $pedido['nm_status_pedido'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
            <option value="Concluído" <?= $pedido['nm_status_pedido'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
            <option value="Cancelado" <?= $pedido['nm_status_pedido'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="total" class="block text-sm font-medium text-gray-300">Total</label>
        <input type="text" name="total" id="total" value="<?= number_format($pedido['nr_total_pedidos'], 2, ',', '.') ?>" class="w-full p-2 bg-gray-700 text-white rounded" required>
    </div>

    <div class="flex justify-between">
        <button type="submit" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Salvar Alterações</button>
        <a href="pedidos.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Cancelar</a>
    </div>
</form>
</div>
</div>
</body>
</html>

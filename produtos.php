<?php 
include 'conexao.php';

// feedbackzin
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;

// Fazer busca dos produtos com informações de fornecedores
$stmt = $pdo->query("SELECT p.*, f.nm_fornecedor 
                    FROM tb_produtos p 
                    JOIN tb_fornecedores f ON p.id_fornecedor = f.id_fornecedor
                    ORDER BY p.nm_produto");
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Depósito</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Produtos</h1>
            <nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
                <a href="index.php" class="hover:text-primary transition">Home</a>
                <a href="clientes.php" class="hover:text-primary transition">Clientes</a>
                <a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
                <a href="produtos.php" class="text-primary font-medium">Produtos</a>
                <a href="estoque.php" class="hover:text-primary transition">Estoque</a>
                <a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
            </nav>
        </header>

        <?php if ($success): ?>
            <div class="bg-green-900 text-green-300 p-3 mb-4 rounded">
                Operação realizada com sucesso!
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-900 text-red-300 p-3 mb-4 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold">Lista de Produtos</h2>
            <a href="adicionar_produto.php" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Adicionar Produto</a>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-left py-2">ID</th>
                            <th class="text-left py-2">Nome</th>
                            <th class="text-left py-2">Descrição</th>
                            <th class="text-left py-2">Fornecedor</th>
                            <th class="text-left py-2">Estoque</th>
                            <th class="text-left py-2">Valor Unit.</th>
                            <th class="text-left py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="py-2"><?= $produto['id_produto'] ?></td>
                            <td class="py-2"><?= $produto['nm_produto'] ?></td>
                            <td class="py-2"><?= $produto['ds_produto'] ?></td>
                            <td class="py-2"><?= $produto['nm_fornecedor'] ?></td>
                            <td class="py-2 <?= $produto['qt_estoque_produto'] < 5 ? 'text-yellow-400' : '' ?>">
                                <?= $produto['qt_estoque_produto'] ?>
                                <?php if ($produto['qt_estoque_produto'] < 5): ?>
                                    <span class="text-xs text-red-400">(ta com baixo estoque)</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2">R$ <?= number_format($produto['vl_unitario_produto'], 2, ',', '.') ?></td>
                            <td class="py-2">
                                <a href="editar_produto.php?id=<?= $produto['id_produto'] ?>" class="text-yellow-400 hover:underline mr-3">Editar</a>
                                <a href="excluir_produto.php?id=<?= $produto['id_produto'] ?>" class="text-red-400 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

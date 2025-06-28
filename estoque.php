<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Estoque - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Estoque</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="text-primary font-medium">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <div class="mb-6 flex justify-between items-center">
<h2 class="text-2xl font-semibold">Gerenciamento de Estoque</h2>
<div class="flex space-x-4">
<a href="entrada_estoque.php" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Entrada</a>
<a href="saida_estoque.php" class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded transition">Saída</a>
</div>
</div>
 
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h3 class="text-lg font-semibold text-primary mb-2">Produtos em Estoque</h3>
<?php
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_produtos");
                $total = $stmt->fetch()['total'];
                ?>
<p class="text-3xl font-bold"><?= $total ?></p>
</div>
 
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h3 class="text-lg font-semibold text-primary mb-2">Itens com Baixo Estoque</h3>
<?php
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_produtos WHERE qt_estoque_produto < 5");
                $total = $stmt->fetch()['total'];
                ?>
<p class="text-3xl font-bold"><?= $total ?></p>
</div>
 
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h3 class="text-lg font-semibold text-primary mb-2">Última Movimentação</h3>
<?php
                $stmt = $pdo->query("SELECT m.*, p.nm_produto FROM tb_mov_estoque m JOIN tb_produtos p ON m.fk_produto = p.id_produto ORDER BY dt_movimentacao DESC LIMIT 1");
                $last = $stmt->fetch();
                if ($last):
                ?>
<p class="text-sm">
<?= date('d/m/Y H:i', strtotime($last['dt_movimentacao'])) ?><br>
<?= $last['tipo_movimentacao'] ?> de <?= $last['qt_movimentada'] ?> <?= $last['nm_produto'] ?>
</p>
<?php else: ?>
<p class="text-sm">Nenhuma movimentação registrada</p>
<?php endif; ?>
</div>
</div>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h3 class="text-xl font-semibold text-primary mb-4">Produtos com Baixo Estoque</h3>
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">Produto</th>
<th class="text-left py-2">Quantidade</th>
<th class="text-left py-2">Fornecedor</th>
<th class="text-left py-2">Ação</th>
</tr>
</thead>
<tbody>
<?php
                        $stmt = $pdo->query("SELECT p.*, f.nm_fornecedor 
                                            FROM tb_produtos p 
                                            JOIN tb_fornecedores f ON p.id_fornecedor = f.id_fornecedor
                                            WHERE p.qt_estoque_produto < 5
                                            ORDER BY p.qt_estoque_produto ASC");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $row['nm_produto'] ?></td>
<td class="py-2 <?= $row['qt_estoque_produto'] < 3 ? 'text-red-400' : 'text-yellow-400' ?>"><?= $row['qt_estoque_produto'] ?></td>
<td class="py-2"><?= $row['nm_fornecedor'] ?></td>
<td class="py-2">
<a href="entrada_estoque.php?produto=<?= $row['id_produto'] ?>" class="text-primary hover:underline">Repor Estoque</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</div>
</body>
</html>
<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedidos - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Pedidos</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="text-primary font-medium">Pedidos</a>
</nav>
</header>
 
        <div class="mb-6 flex justify-between items-center">
<h2 class="text-2xl font-semibold">Lista de Pedidos</h2>
<a href="novo_pedido.php" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Novo Pedido</a>
</div>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">ID</th>
<th class="text-left py-2">Data</th>
<th class="text-left py-2">Cliente</th>
<th class="text-left py-2">Status</th>
<th class="text-left py-2">Total</th>
<th class="text-left py-2">Ações</th>
</tr>
</thead>
<tbody>
<?php
                        $stmt = $pdo->query("SELECT p.*, c.nm_cliente 
                                            FROM tb_pedido p 
                                            JOIN tb_cliente c ON p.fk_cliente = c.id_cliente
                                            ORDER BY p.dt_pedido DESC");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $row['id_pedido'] ?></td>
<td class="py-2"><?= date('d/m/Y', strtotime($row['dt_pedido'])) ?></td>
<td class="py-2"><?= $row['nm_cliente'] ?></td>
<td class="py-2">
<span class="px-2 py-1 rounded-full text-xs <?= 
                                    $row['nm_status_pedido'] === 'Concluído' ? 'bg-green-900 text-green-300' : 
                                    ($row['nm_status_pedido'] === 'Pendente' ? 'bg-yellow-900 text-yellow-300' : 'bg-gray-700 text-gray-300')
                                ?>">
<?= $row['nm_status_pedido'] ?>
</span>
</td>
<td class="py-2">R$ <?= number_format($row['nr_total_pedidos'], 2, ',', '.') ?></td>
<td class="py-2">
<a href="detalhes_pedido.php?id=<?= $row['id_pedido'] ?>" class="text-blue-400 hover:underline mr-3">Detalhes</a>
<a href="editar_pedido.php?id=<?= $row['id_pedido'] ?>" class="text-yellow-400 hover:underline mr-3">Editar</a>
<a href="cancelar_pedido.php?id=<?= $row['id_pedido'] ?>" class="text-red-400 hover:underline" onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">Cancelar</a>
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
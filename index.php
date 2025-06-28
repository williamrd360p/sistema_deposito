<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6b21a8',
                        dark: '#0f172a'
                    }
                }
            }
        }
</script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-10">
<h1 class="text-4xl font-bold text-primary mb-2">Depósito Materiais Construção</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <main class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h2 class="text-xl font-semibold text-primary mb-4">Clientes</h2>
<?php
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_cliente");
                $total = $stmt->fetch()['total'];
                ?>
<p class="text-3xl font-bold"><?= $total ?></p>
<p class="text-gray-400">Clientes cadastrados</p>
<a href="clientes.php" class="inline-block mt-4 text-primary hover:underline">Ver todos</a>
</div>
 
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h2 class="text-xl font-semibold text-primary mb-4">Produtos</h2>
<?php
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_produtos");
                $total = $stmt->fetch()['total'];
                ?>
<p class="text-3xl font-bold"><?= $total ?></p>
<p class="text-gray-400">Produtos em estoque</p>
<a href="produtos.php" class="inline-block mt-4 text-primary hover:underline">Ver todos</a>
</div>
 
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<h2 class="text-xl font-semibold text-primary mb-4">Pedidos</h2>
<?php
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_pedido");
                $total = $stmt->fetch()['total'];
                ?>
<p class="text-3xl font-bold"><?= $total ?></p>
<p class="text-gray-400">Pedidos realizados</p>
<a href="pedidos.php" class="inline-block mt-4 text-primary hover:underline">Ver todos</a>
</div>
 
            <div class="md:col-span-3 bg-gray-800 p-6 rounded-lg shadow-lg">
<h2 class="text-xl font-semibold text-primary mb-4">Últimas Movimentações</h2>
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">Produto</th>
<th class="text-left py-2">Tipo</th>
<th class="text-left py-2">Quantidade</th>
<th class="text-left py-2">Data</th>
</tr>
</thead>
<tbody>
<?php
                        $stmt = $pdo->query("SELECT m.*, p.nm_produto FROM tb_mov_estoque m JOIN tb_produtos p ON m.fk_produto = p.id_produto ORDER BY dt_movimentacao DESC LIMIT 5");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $row['nm_produto'] ?></td>
<td class="py-2"><?= $row['tipo_movimentacao'] ?></td>
<td class="py-2"><?= $row['qt_movimentada'] ?></td>
<td class="py-2"><?= date('d/m/Y H:i', strtotime($row['dt_movimentacao'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</main>
</div>
</body>
</html>
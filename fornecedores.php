<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fornecedores - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Fornecedores</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="text-primary font-medium">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <div class="mb-6 flex justify-between items-center">
<h2 class="text-2xl font-semibold">Lista de Fornecedores</h2>
<a href="adicionar_fornecedor.php" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Adicionar Fornecedor</a>
</div>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">ID</th>
<th class="text-left py-2">Nome</th>
<th class="text-left py-2">CNPJ</th>
<th class="text-left py-2">Endereço</th>
<th class="text-left py-2">Produtos</th>
<th class="text-left py-2">Ações</th>
</tr>
</thead>
<tbody>
<?php
                        $stmt = $pdo->query("SELECT f.*, COUNT(p.id_produto) as total_produtos 
                                            FROM tb_fornecedores f 
                                            LEFT JOIN tb_produtos p ON f.id_fornecedor = p.id_fornecedor
                                            GROUP BY f.id_fornecedor");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $row['id_fornecedor'] ?></td>
<td class="py-2"><?= $row['nm_fornecedor'] ?></td>
<td class="py-2"><?= $row['cnpj_fornecedor'] ?></td>
<td class="py-2"><?= $row['nm_endereco_fornecedor'] ?></td>
<td class="py-2"><?= $row['total_produtos'] ?></td>
<td class="py-2">
<a href="editar_fornecedor.php?id=<?= $row['id_fornecedor'] ?>" class="text-yellow-400 hover:underline mr-3">Editar</a>
<a href="excluir_fornecedor.php?id=<?= $row['id_fornecedor'] ?>" class="text-red-400 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
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
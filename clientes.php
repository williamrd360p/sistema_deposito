<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Clientes</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="text-primary font-medium">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <div class="mb-6 flex justify-between items-center">
<h2 class="text-2xl font-semibold">Lista de Clientes</h2>
<a href="adicionar_cliente.php" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Adicionar Cliente</a>
</div>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">ID</th>
<th class="text-left py-2">Nome</th>
<th class="text-left py-2">CPF</th>
<th class="text-left py-2">Email</th>
<th class="text-left py-2">Telefone</th>
<th class="text-left py-2">Endereço</th>
<th class="text-left py-2">Ações</th>
</tr>
</thead>
<tbody>
<?php
                        $stmt = $pdo->query("SELECT * FROM tb_cliente");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $row['id_cliente'] ?></td>
<td class="py-2"><?= $row['nm_cliente'] ?></td>
<td class="py-2"><?= $row['cpf_cliente'] ?></td>
<td class="py-2"><?= $row['nm_email_cliente'] ?></td>
<td class="py-2"><?= $row['nr_telefone_cliente'] ?></td>
<td class="py-2"><?= $row['nm_endereco_cliente'] ?>, <?= $row['nr_endereco_cliente'] ?></td>
<td class="py-2">
<a href="editar_cliente.php?id=<?= $row['id_cliente'] ?>" class="text-yellow-400 hover:underline mr-3">Editar</a>
<a href="excluir_cliente.php?id=<?= $row['id_cliente'] ?>" class="text-red-400 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
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
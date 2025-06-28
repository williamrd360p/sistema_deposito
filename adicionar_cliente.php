<?php
include 'conexao.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome']);
    $cpf = sanitize($_POST['cpf']);
    $email = sanitize($_POST['email']);
    $telefone = sanitize($_POST['telefone']);
    $endereco = sanitize($_POST['endereco']);
    $numero = sanitize($_POST['numero']);
 
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_cliente (cpf_cliente, nm_cliente, nm_email_cliente, nr_telefone_cliente, nm_endereco_cliente, nr_endereco_cliente) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cpf, $nome, $email, $telefone, $endereco, $numero]);
        header('Location: clientes.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Erro ao adicionar cliente: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adicionar Cliente - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Adicionar Cliente</h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
<form method="POST">
<?php if (isset($error)): ?>
<div class="bg-red-900 text-red-300 p-3 mb-4 rounded">
<?= $error ?>
</div>
<?php endif; ?>
 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="mb-4">
<label class="block text-gray-400 mb-2">Nome</label>
<input type="text" name="nome" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4">
<label class="block text-gray-400 mb-2">CPF</label>
<input type="text" name="cpf" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4">
<label class="block text-gray-400 mb-2">Email</label>
<input type="email" name="email" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4">
<label class="block text-gray-400 mb-2">Telefone</label>
<input type="text" name="telefone" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4 md:col-span-2">
<label class="block text-gray-400 mb-2">Endereço</label>
<div class="flex space-x-4">
<input type="text" name="endereco" placeholder="Rua/Avenida" required 
                                   class="flex-1 bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
<input type="number" name="numero" placeholder="Nº" required 
                                   class="w-24 bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
</div>
</div>
 
                <div class="flex justify-end space-x-4 mt-6">
<a href="clientes.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Cancelar</a>
<button type="submit" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Salvar</button>
</div>
</form>
</div>
</div>
</body>
</html>
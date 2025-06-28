<?php
include 'conexao.php';
 
$fornecedores = $pdo->query("SELECT * FROM tb_fornecedores")->fetchAll();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome']);
    $descricao = sanitize($_POST['descricao']);
    $fornecedor = sanitize($_POST['fornecedor']);
    $estoque = sanitize($_POST['estoque']);
    $valor = sanitize($_POST['valor']);
 
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_produtos (id_fornecedor, nm_produto, ds_produto, qt_estoque_produto, vl_unitario_produto) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fornecedor, $nome, $descricao, $estoque, $valor]);
        $idProduto = $pdo->lastInsertId();
        // Criar registro no estoque
        $stmtEstoque = $pdo->prepare("INSERT INTO tb_estoque (qt_atual_produtos, fk_produto) VALUES (?, ?)");
        $stmtEstoque->execute([$estoque, $idProduto]);
        header('Location: produtos.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Erro ao adicionar produto: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adicionar Produto - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Adicionar Produto</h1>
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
<label class="block text-gray-400 mb-2">Fornecedor</label>
<select name="fornecedor" required
                                class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
<option value="">Selecione...</option>
<?php foreach ($fornecedores as $fornecedor): ?>
<option value="<?= $fornecedor['id_fornecedor'] ?>"><?= $fornecedor['nm_fornecedor'] ?></option>
<?php endforeach; ?>
</select>
</div>
 
                    <div class="mb-4 md:col-span-2">
<label class="block text-gray-400 mb-2">Descrição</label>
<textarea name="descricao" rows="3" required
                                  class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white"></textarea>
</div>
 
                    <div class="mb-4">
<label class="block text-gray-400 mb-2">Quantidade em Estoque</label>
<input type="number" name="estoque" required min="0"
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4">
<label class="block text-gray-400 mb-2">Valor Unitário (R$)</label>
<input type="number" name="valor" step="0.01" required min="0"
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
</div>
 
                <div class="flex justify-end space-x-4 mt-6">
<a href="produtos.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Cancelar</a>
<button type="submit" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Salvar</button>
</div>
</form>
</div>
</div>
</body>
</html>
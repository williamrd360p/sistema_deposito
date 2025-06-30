<?php
// entrada_estoque.php
include 'conexao.php';
 
$produtos = $pdo->query("SELECT p.id_produto, p.nm_produto, f.nm_fornecedor 
                        FROM tb_produtos p 
                        JOIN tb_fornecedores f ON p.id_fornecedor = f.id_fornecedor
                        ORDER BY p.nm_produto")->fetchAll();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtoId = sanitize($_POST['produto']);
    $quantidade = sanitize($_POST['quantidade']);
    $motivo = sanitize($_POST['motivo']);
 
    try {
        $pdo->beginTransaction();
 
        // Atualizar quantidade no produto
        $stmt = $pdo->prepare("UPDATE tb_produtos SET qt_estoque_produto = qt_estoque_produto + ? WHERE id_produto = ?");
        $stmt->execute([$quantidade, $produtoId]);
 
        // Atualizar estoque
        $stmtEstoque = $pdo->prepare("UPDATE tb_estoque SET qt_atual_produtos = qt_atual_produtos + ? WHERE fk_produto = ?");
        $stmtEstoque->execute([$quantidade, $produtoId]);
 
        // Registrar movimento
        $stmtMov = $pdo->prepare("INSERT INTO tb_mov_estoque (fk_produto, fk_estoque, tipo_movimentacao, qt_movimentada) 
                                 VALUES (?, (SELECT id_estoque FROM tb_estoque WHERE fk_produto = ?), 'ENTRADA', ?)");
        $stmtMov->execute([$produtoId, $produtoId, $quantidade]);
 
        $pdo->commit();
        header('Location: estoque.php?success=1');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Erro ao registrar entrada: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrada no Estoque - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Entrada no Estoque</h1>
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
 
                <div class="mb-4">
<label class="block text-gray-400 mb-2">Produto</label>
<select name="produto" required
                            class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
<option value="">Selecione um produto...</option>
<?php foreach ($produtos as $produto): ?>
<option value="<?= $produto['id_produto'] ?>"><?= $produto['nm_produto'] ?> (<?= $produto['nm_fornecedor'] ?>)</option>
<?php endforeach; ?>
</select>
</div>
 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="mb-4">
<label class="block text-gray-400 mb-2">Quantidade</label>
<input type="number" name="quantidade" required min="1"
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
 
                    <div class="mb-4 md:col-span-2">
<label class="block text-gray-400 mb-2">Motivo</label>
<input type="text" name="motivo" placeholder="Compra, Devolução, etc."
                               class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
</div>
</div>
 
                <div class="flex justify-end space-x-4 mt-6">
<a href="estoque.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Cancelar</a>
<button type="submit" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Registrar Entrada</button>
</div>
</form>
</div>
</div>
</body>
</html>
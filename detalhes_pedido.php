<?php
include 'conexao.php';
 
if (!isset($_GET['id'])) {
    header('Location: pedidos.php');
    exit;
}
 
$pedidoId = $_GET['id'];
 
// Buscar informações do pedido
$stmtPedido = $pdo->prepare("SELECT p.*, c.nm_cliente, c.cpf_cliente 
                             FROM tb_pedido p 
                             JOIN tb_cliente c ON p.fk_cliente = c.id_cliente
                             WHERE p.id_pedido = ?");
$stmtPedido->execute([$pedidoId]);
$pedido = $stmtPedido->fetch();
 
if (!$pedido) {
    header('Location: pedidos.php');
    exit;
}
 
// Buscar itens do pedido
$stmtItens = $pdo->prepare("SELECT pp.*, p.nm_produto, p.vl_unitario_produto, p.ds_produto 
                           FROM fk_produtos_pedidos pp 
                           JOIN tb_produtos p ON pp.fk_produto = p.id_produto
                           WHERE pp.fk_pedido = ?");
$stmtItens->execute([$pedidoId]);
$itens = $stmtItens->fetchAll();
 
// Calcular valor total
$valorTotal = 0;
foreach ($itens as $item) {
    $valorTotal += $item['vl_unitario_produto'] * $item['qt_produto'];
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalhes do Pedido - Depósito</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
<header class="mb-8">
<h1 class="text-3xl font-bold text-primary mb-2">Detalhes do Pedido #<?= $pedido['id_pedido'] ?></h1>
<nav class="flex space-x-6 bg-gray-800 p-4 rounded-lg">
<a href="index.php" class="hover:text-primary transition">Home</a>
<a href="clientes.php" class="hover:text-primary transition">Clientes</a>
<a href="fornecedores.php" class="hover:text-primary transition">Fornecedores</a>
<a href="produtos.php" class="hover:text-primary transition">Produtos</a>
<a href="estoque.php" class="hover:text-primary transition">Estoque</a>
<a href="pedidos.php" class="hover:text-primary transition">Pedidos</a>
</nav>
</header>
 
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
<div>
<h3 class="text-lg font-semibold text-primary mb-2">Informações do Pedido</h3>
<div class="space-y-2">
<p><span class="text-gray-400">Número:</span> <?= $pedido['id_pedido'] ?></p>
<p><span class="text-gray-400">Data:</span> <?= date('d/m/Y', strtotime($pedido['dt_pedido'])) ?></p>
<p>
<span class="text-gray-400">Status:</span> 
<span class="px-2 py-1 rounded-full text-xs <?= 
                                $pedido['nm_status_pedido'] === 'Concluído' ? 'bg-green-900 text-green-300' : 
                                ($pedido['nm_status_pedido'] === 'Pendente' ? 'bg-yellow-900 text-yellow-300' : 'bg-gray-700 text-gray-300')
                            ?>">
<?= $pedido['nm_status_pedido'] ?>
</span>
</p>
<p><span class="text-gray-400">Total de Itens:</span> <?= $pedido['nr_total_pedidos'] ?></p>
</div>
</div>
 
                <div>
<h3 class="text-lg font-semibold text-primary mb-2">Cliente</h3>
<div class="space-y-2">
<p><span class="text-gray-400">Nome:</span> <?= $pedido['nm_cliente'] ?></p>
<p><span class="text-gray-400">CPF:</span> <?= $pedido['cpf_cliente'] ?></p>
</div>
</div>
</div>
 
            <h3 class="text-lg font-semibold text-primary mb-4">Itens do Pedido</h3>
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="border-b border-gray-700">
<th class="text-left py-2">Produto</th>
<th class="text-left py-2">Descrição</th>
<th class="text-left py-2">Quantidade</th>
<th class="text-left py-2">Valor Unitário</th>
<th class="text-left py-2">Subtotal</th>
</tr>
</thead>
<tbody>
<?php foreach ($itens as $item): ?>
<tr class="border-b border-gray-700 hover:bg-gray-700">
<td class="py-2"><?= $item['nm_produto'] ?></td>
<td class="py-2"><?= $item['ds_produto'] ?></td>
<td class="py-2"><?= $item['qt_produto'] ?></td>
<td class="py-2">R$ <?= number_format($item['vl_unitario_produto'], 2, ',', '.') ?></td>
<td class="py-2">R$ <?= number_format($item['vl_unitario_produto'] * $item['qt_produto'], 2, ',', '.') ?></td>
</tr>
<?php endforeach; ?>
<tr class="border-t-2 border-gray-600 font-bold">
<td colspan="4" class="py-2 text-right">Total:</td>
<td class="py-2">R$ <?= number_format($valorTotal, 2, ',', '.') ?></td>
</tr>
</tbody>
</table>
</div>
 
            <div class="flex justify-end space-x-4 mt-6">
<a href="pedidos.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Voltar</a>
<?php if ($pedido['nm_status_pedido'] === 'Pendente'): ?>
<a href="concluir_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded transition">Concluir Pedido</a>
<?php endif; ?>
<a href="editar_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Editar</a>
</div>
</div>
</div>
</body>
</html>
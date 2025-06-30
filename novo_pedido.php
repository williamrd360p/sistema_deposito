<?php
include 'conexao.php';
 
$clientes = $pdo->query("SELECT * FROM tb_cliente ORDER BY nm_cliente")->fetchAll();
$produtos = $pdo->query("SELECT p.id_produto, p.nm_produto, p.vl_unitario_produto, p.qt_estoque_produto, f.nm_fornecedor
                        FROM tb_produtos p JOIN tb_fornecedores f ON p.id_fornecedor = f.id_fornecedor
                        WHERE p.qt_estoque_produto > 0
                        ORDER BY p.nm_produto")->fetchAll();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = sanitize($_POST['cliente']);
    $produtosSelecionados = $_POST['produtos'] ?? [];
    $quantidades = $_POST['quantidades'] ?? [];
    $status = 'Pendente';
   
    try {
        $pdo->beginTransaction();
       
        // Calcular total de itens
        $totalItens = array_sum($quantidades);
       
        // Criar pedido
        $stmtPedido = $pdo->prepare("INSERT INTO tb_pedido (dt_pedido, nm_status_pedido, nr_total_pedidos, fk_cliente)
                                    VALUES (CURDATE(), ?, ?, ?)");
        $stmtPedido->execute([$status, $totalItens, $clienteId]);
        $pedidoId = $pdo->lastInsertId();
       
        // Adicionar produtos ao pedido
        foreach ($produtosSelecionados as $index => $produtoId) {
            $quantidade = $quantidades[$index];
           
            // Verificar estoque
            $stmtEstoque = $pdo->prepare("SELECT qt_estoque_produto FROM tb_produtos WHERE id_produto = ? FOR UPDATE");
            $stmtEstoque->execute([$produtoId]);
            $estoqueAtual = $stmtEstoque->fetchColumn();
           
            if ($estoqueAtual < $quantidade) {
                throw new Exception("Estoque insuficiente para o produto ID: $produtoId");
            }
           
            // Registrar produto no pedido
            $stmtItem = $pdo->prepare("INSERT INTO fk_produtos_pedidos (fk_produto, fk_pedido, qt_produto)
                                      VALUES (?, ?, ?)");
            $stmtItem->execute([$produtoId, $pedidoId, $quantidade]);
           
            // Atualizar estoque
            $stmtAtualiza = $pdo->prepare("UPDATE tb_produtos SET qt_estoque_produto = qt_estoque_produto - ? WHERE id_produto = ?");
            $stmtAtualiza->execute([$quantidade, $produtoId]);
           
            // Registrar movimento no estoque
            $stmtMov = $pdo->prepare("INSERT INTO tb_mov_estoque (fk_produto, fk_estoque, tipo_movimentacao, qt_movimentada)
                                    VALUES (?, (SELECT id_estoque FROM tb_estoque WHERE fk_produto = ?), 'SAÍDA', ?)");
            $stmtMov->execute([$produtoId, $produtoId, $quantidade]);
        }
       
        $pdo->commit();
        header('Location: pedidos.php?success=1');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erro ao criar pedido: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Pedido - Depósito</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-dark text-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Novo Pedido</h1>
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
            <form method="POST" id="formPedido">
                <?php if (isset($error)): ?>
                    <div class="bg-red-900 text-red-300 p-3 mb-4 rounded">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
 
                <div class="mb-6">
                    <label class="block text-gray-400 mb-2">Cliente</label>
                    <select name="cliente" required
                            class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
                        <option value="">Selecione um cliente...</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nm_cliente'] ?> - <?= $cliente['cpf_cliente'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-4">Produtos do Pedido</h3>
               
               <div id="produtos-container">
                   <div class="produto-item grid grid-cols-12 gap-4 mb-4 items-end">
                       <div class="col-span-5">
                           <label class="block text-gray-400 mb-2">Produto</label>
                           <select name="produtos[]" class="produto-select w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
                               <option value="">Selecione...</option>
                               <?php foreach ($produtos as $produto): ?>
                                   <option value="<?= $produto['id_produto'] ?>"
                                           data-preco="<?= $produto['vl_unitario_produto'] ?>"
                                           data-estoque="<?= $produto['qt_estoque_produto'] ?>">
                                       <?= $produto['nm_produto'] ?> (<?= $produto['nm_fornecedor'] ?>) - R$ <?= number_format($produto['vl_unitario_produto'], 2, ',', '.') ?>
                                   </option>
                               <?php endforeach; ?>
                           </select>
                       </div>
                       <div class="col-span-3">
                           <label class="block text-gray-400 mb-2">Quantidade</label>
                           <input type="number" name="quantidades[]" min="1" value="1"
                                  class="quantidade w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">
                           <div class="estoque-disponivel text-xs text-gray-400"></div>
                       </div>
                       <div class="col-span-3">
                           <label class="block text-gray-400 mb-2">Subtotal</label>
                           <div class="subtotal bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white">R$ 0,00</div>
                       </div>
                       <div class="col-span-1">
                           <button type="button" class="remover-produto bg-red-600 hover:bg-red-800 text-white w-full h-10 rounded flex items-center justify-center">
                               ✕
                           </button>
                       </div>
                   </div>
               </div>

               <div class="flex justify-between mt-4">
                   <button type="button" id="adicionar-produto" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">
                       + Adicionar Produto
                   </button>
               </div>

               <div class="mt-8 pt-4 border-t border-gray-700">
                   <div class="flex justify-end">
                       <div class="text-right">
                           <span class="text-gray-400">Total de Itens: </span>
                           <span id="total-itens" class="font-bold">0</span>
                       </div>
                   </div>
               </div>

               <div class="flex justify-end space-x-4 mt-6">
                   <a href="pedidos.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">Cancelar</a>
                   <button type="submit" class="bg-primary hover:bg-purple-900 text-white px-4 py-2 rounded transition">Salvar Pedido</button>
               </div>
           </form>
       </div>
   </div>

   <script>
       $(document).ready(function() {
           // Adicionar novo produto
           $('#adicionar-produto').click(function() {
               const newItem = $('.produto-item:first').clone();
               newItem.find('select').val('');
               newItem.find('input').val('1');
               newItem.find('.subtotal').text('R$ 0,00');
               newItem.find('.estoque-disponivel').text('');
               $('#produtos-container').append(newItem);
               atualizarTotais();
           });

           // Remover produto
           $(document).on('click', '.remover-produto', function() {
               if ($('.produto-item').length > 1) {
                   $(this).closest('.produto-item').remove();
                   atualizarTotais();
               }
           });

           // Calcular subtotal quando selecionar produto ou alterar quantidade
           $(document).on('change', '.produto-select', function() {
               const item = $(this).closest('.produto-item');
               const preco = parseFloat($(this).find('option:selected').data('preco')) || 0;
               const estoque = parseInt($(this).find('option:selected').data('estoque')) || 0;
               const quantidade = parseInt(item.find('.quantidade').val()) || 0;
              
               item.find('.estoque-disponivel').text(`Disponível: ${estoque}`);
               item.find('.quantidade').attr('max', estoque);
              
               if (quantidade > estoque) {
                   item.find('.quantidade').val(estoque > 0 ? estoque : 1);
               }
              
               calcularSubtotal(item);
               atualizarTotais();
           });

           $(document).on('input', '.quantidade', function() {
               const item = $(this).closest('.produto-item');
               calcularSubtotal(item);
               atualizarTotais();
           });

           function calcularSubtotal(item) {
               const preco = parseFloat(item.find('.produto-select option:selected').data('preco')) || 0;
               const quantidade = parseInt(item.find('.quantidade').val()) || 0;
               const subtotal = preco * quantidade;
               item.find('.subtotal').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
           }

           function atualizarTotais() {
               let totalItens = 0;
              
               $('.quantidade').each(function() {
                   totalItens += parseInt($(this).val()) || 0;
               });
              
               $('#total-itens').text(totalItens);
           }
       });
   </script>
</body>
</html>
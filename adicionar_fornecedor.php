<?php
include 'conexao.php';

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tb_fornecedores (nm_fornecedor, cnpj_fornecedor, nm_endereco_fornecedor)
                               VALUES (:nome, :cnpj, :endereco)");
        $stmt->execute([
            'nome' => $nome,
            'cnpj' => $cnpj,
            'endereco' => $endereco
        ]);

        // Redireciona para a lista de fornecedores
        header("Location: fornecedores.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao adicionar fornecedor: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Adicionar Fornecedor</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-primary">Adicionar Fornecedor</h1>
    <form method="post" class="bg-gray-800 p-6 rounded-lg shadow-md max-w-lg">
        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium">Nome</label>
            <input type="text" id="nome" name="nome" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label for="cnpj" class="block text-sm font-medium">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label for="endereco" class="block text-sm font-medium">Endereço</label>
            <input type="text" id="endereco" name="endereco" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="flex justify-between">
            <a href="fornecedores.php" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-white">Cancelar</a>
            <button type="submit" class="bg-primary hover:bg-purple-900 px-4 py-2 rounded text-white">Salvar</button>
        </div>
    </form>
</div>
</body>
</html>

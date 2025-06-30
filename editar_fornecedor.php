<?php
include 'conexao.php';

// Verifica se um ID foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de fornecedor inválido.";
    exit;
}

$id = (int)$_GET['id'];

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];

    try {
        $stmt = $pdo->prepare("UPDATE tb_fornecedores 
                               SET nm_fornecedor = :nome, cnpj_fornecedor = :cnpj, nm_endereco_fornecedor = :endereco 
                               WHERE id_fornecedor = :id");
        $stmt->execute([
            'nome' => $nome,
            'cnpj' => $cnpj,
            'endereco' => $endereco,
            'id' => $id
        ]);

        // Redireciona para a lista de fornecedores
        header("Location: fornecedores.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar fornecedor: " . $e->getMessage();
    }
}

// Busca os dados do fornecedor atual
$stmt = $pdo->prepare("SELECT * FROM tb_fornecedores WHERE id_fornecedor = :id");
$stmt->execute(['id' => $id]);
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fornecedor) {
    echo "Fornecedor não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Fornecedor</title>
<link rel="stylesheet" href="css/style.css">
<style>
    .head{
       display: flex    ;
       align-items: center;
       justify-content: center;
    }
</style>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-dark text-gray-100">
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-primary">Editar Fornecedor</h1>
    <form method="post" class="bg-gray-800 p-6 rounded-lg shadow-md max-w-lg">
        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($fornecedor['nm_fornecedor']) ?>" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label for="cnpj" class="block text-sm font-medium">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?>" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label for="endereco" class="block text-sm font-medium">Endereço</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($fornecedor['nm_endereco_fornecedor']) ?>" required class="w-full mt-1 px-3 py-2 rounded bg-gray-700 text-white">
        </div>
        <div class="flex justify-between">
            <a href="fornecedores.php" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-white">Cancelar</a>
            <button type="submit" class="bg-primary hover:bg-purple-900 px-4 py-2 rounded text-white">Salvar</button>

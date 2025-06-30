<?php
include 'conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Verifica se existem produtos vinculados
        $stmtProdutos = $pdo->prepare("SELECT COUNT(*) FROM tb_produtos WHERE id_fornecedor = :id");
        $stmtProdutos->execute(['id' => $id]);
        $totalProdutos = $stmtProdutos->fetchColumn();

        if ($totalProdutos > 0) {
            echo "Não é possível excluir. Existem produtos vinculados a este fornecedor.";
            echo "<br><a href='fornecedores.php'>Voltar</a>";
            exit;
        }

        // Exclui o fornecedor
        $stmt = $pdo->prepare("DELETE FROM tb_fornecedores WHERE id_fornecedor = :id");
        $stmt->execute(['id' => $id]);

        header("Location: fornecedores.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao excluir o fornecedor: " . $e->getMessage();
    }
} else {
    echo "ID inválido.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1, initial-scale=1.0">
    <title>Document</title>
    <a href="CSS/style.css"></a>
</head>
<body>
    
</body>
</html>
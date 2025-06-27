<?php include 'db.php'; ?>
   <!DOCTYPE html>
   <html lang="pt-BR">
   <head>
       <meta charset="UTF-8">
       <title>Clientes</title>
   </head>
   <body>
       <h1>Gerenciar Clientes</h1>
       <a href="clientes.php?acao=novo">Novo Cliente</a>
       <table>
           <tr>
               <th>ID</th>
               <th>Nome</th>
               <th>Email</th>
               <th>Ações</th>
           </tr>
           <?php
           // Listar clientes
           $stmt = $pdo->query("SELECT * FROM tb_cliente");
           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               echo "<tr>
                       <td>{$row['id_cliente']}</td>
                       <td>{$row['nm_cliente']}</td>
                       <td>{$row['nm_email_cliente']}</td>
                       <td>
                           <a href='clientes.php?acao=editar&id={$row['id_cliente']}'>Editar</a>
                           <a href='clientes.php?acao=deletar&id={$row['id_cliente']}'>Deletar</a>
                       </td>
                   </tr>";
           }
           ?>
       </table>
       <?php
       // Adicionar, editar ou deletar cliente
       if (isset($_GET['acao'])) {
           if ($_GET['acao'] == 'novo' || $_GET['acao'] == 'editar') {
               // Formulário para adicionar ou editar cliente
               $id = $_GET['id'] ?? null;
               $cliente = null;
               if ($id) {
                   $stmt = $pdo->prepare("SELECT * FROM tb_cliente WHERE id_cliente = ?");
                   $stmt->execute([$id]);
                   $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
               }
               ?>
               <form method="POST" action="clientes.php">
                   <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?? '' ?>">
                   <label>Nome:</label>
                   <input type="text" name="nm_cliente" value="<?= $cliente['nm_cliente'] ?? '' ?>" required>
                   <label>Email:</label>
                   <input type="email" name="nm_email_cliente" value="<?= $cliente['nm_email_cliente'] ?? '' ?>" required>
                   <label>Telefone:</label>
                   <input type="text" name="nr_telefone_cliente" value="<?= $cliente['nr_telefone_cliente'] ?? '' ?>" required>
                   <label>Endereço:</label>
                   <input type="text" name="nm_endereco_cliente" value="<?= $cliente['nm_endereco_cliente'] ?? '' ?>" required>
                   <label>Número:</label>
                   <input type="number" name="nr_endereco_cliente" value="<?= $cliente['nr_endereco_cliente'] ?? '' ?>" required>
                   <button type="submit"><?= $id ? 'Atualizar' : 'Adicionar' ?></button>
               </form>
               <?php
           } elseif ($_GET['acao'] == 'deletar') {
               $id = $_GET['id'];
               $stmt = $pdo->prepare("DELETE FROM tb_cliente WHERE id_cliente = ?");
               $stmt->execute([$id]);
               header("Location: clientes.php");
           }
       }

       // Processar o formulário
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           $id_cliente = $_POST['id_cliente'] ?? null;
           $nm_cliente = $_POST['nm_cliente'];
           $nm_email_cliente = $_POST['nm_email_cliente'];
           $nr_telefone_cliente = $_POST['nr_telefone_cliente'];
           $nm_endereco_cliente = $_POST['nm_endereco_cliente'];
           $nr_endereco_cliente = $_POST['nr_endereco_cliente'];

           if ($id_cliente) {
               // Atualizar cliente
               $stmt = $pdo->prepare("UPDATE tb_cliente SET nm_cliente = ?, nm_email_cliente = ?, nr_telefone_cliente = ?, nm_endereco_cliente = ?, nr_endereco_cliente = ? WHERE id_cliente = ?");
               $stmt->execute([$nm_cliente, $nm_email_cliente, $nr_telefone_cliente, $nm_endereco_cliente, $nr_endereco_cliente, $id_cliente]);
           } else {
               // Adicionar cliente
               $stmt = $pdo->prepare("INSERT INTO tb_cliente (nm_cliente, nm_email_cliente, nr_telefone_cliente, nm_endereco_cliente, nr_endereco_cliente) VALUES (?, ?, ?, ?, ?)");
               $stmt->execute([$nm_cliente, $nm_email_cliente, $nr_telefone_cliente, $nm_endereco_cliente, $nr_endereco_cliente]);
           }
           header("Location: clientes.php");
       }
       ?>
   </body>
   </html>
   
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : null;

$host = "127.0.0.1";
$usuario = "root";
$senha = "";
$db = "p1_dev_web";

$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_produto = $conn->real_escape_string($_POST['nome_produto']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = $conn->real_escape_string($_POST['preco']);
    $estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;

    $sql = "INSERT INTO produtos (nome_produto, descricao, preco, estoque) 
            VALUES ('$nome_produto', '$descricao', '$preco', '$estoque')";

    if ($conn->query($sql) === TRUE) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o produto: " . $conn->error;
    }
}

$sql = "SELECT id, nome_produto, descricao, preco, estoque, data_adicao FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
   
</head>
<body>
    <div class="container">
        <h1>Cadastro de Produto</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome_produto" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="number" id="preco" name="preco" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="estoque">Quantidade em Estoque</label>
                <input type="number" id="estoque" name="estoque" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn-submit">Cadastrar Produto</button>
                <button type="button" class="btn-submit" onclick="window.location.href='produtos.php'">Ver Produtos</button>
            </div>
        </form>

        <h2>Lista de Produtos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Data de Adição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome_produto']; ?></td>
                        <td><?php echo $row['descricao']; ?></td>
                        <td><?php echo $row['preco']; ?></td>
                        <td><?php echo $row['estoque']; ?></td>
                        <td><?php echo $row['data_adicao']; ?></td>
                        <td class="action-buttons">
                            <button class="edit-btn">Editar</button>
                            <button class="delete-btn">Excluir</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nenhum produto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

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

// Consultar produtos
$sql = "SELECT id, nome_produto, descricao, preco, estoque, data_adicao FROM produtos";
$result = $conn->query($sql);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar o cadastro do produto
    $nome_produto = $_POST['nome_produto'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $sql_insert = "INSERT INTO produtos (nome_produto, descricao, preco, estoque) VALUES ('$nome_produto', '$descricao', $preco, $estoque)";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar produto: " . $conn->error;
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($_GET['action']) && $_GET['action'] == 'cadastrar' ? 'Cadastrar Produto' : 'Produtos'; ?></title>
</head>
<body>
    <div>
        <div>
            <?php if ($usuarioLogado): ?>
                <p>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?>!</p>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="cadastro_form.php">Cadastro</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['action']) && $_GET['action'] == 'cadastrar'): ?>
            <h1>Cadastrar Produto</h1>
            <form method="POST" action="">
                <label for="nome_produto">Nome do Produto:</label>
                <input type="text" id="nome_produto" name="nome_produto" required><br><br>

                <label for="descricao">Descrição:</label>
                <input id="descricao" name="descricao"><br><br>

                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" required><br><br>

                <label for="estoque">Quantidade em Estoque:</label>
                <input type="number" id="estoque" name="estoque" required><br><br>

                <input type="submit" value="Cadastrar Produto">
            </form>
            <br>
            <a href="?"><input type="button" value="Ver produtos cadastrados"></a>
        <?php else: ?>
            <h1>Produtos</h1>
            <table class="table">
                <thead>
                    <tr>
                        <?php if ($usuarioLogado): ?>
                            <th scope="col">ID</th>
                        <?php endif; ?>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Preço</th>
                        <?php if ($usuarioLogado): ?>
                            <th scope="col">Quantidade em Estoque</th>
                            <th scope="col">Data de Cadastro</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Deletar</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            if ($usuarioLogado) {
                                echo "<td>" . $row["id"] . "</td>";
                            }
                            echo "<td>" . $row["nome_produto"] . "</td>";
                            echo "<td>" . $row["descricao"] . "</td>";
                            echo "<td>" . $row["preco"] . "</td>";
                            if ($usuarioLogado) {
                                echo "<td>" . $row["estoque"] . "</td>";
                                echo "<td>" . $row["data_adicao"] . "</td>";
                                // Adicionando o botão de edição com link para a página edit_produto.php
                                echo "<td><a href='edit_produto.php?id=" . $row["id"] . "'>Editar</a></td>";
                                
                                // Formulário com confirmação de deleção
                                echo "<td>
                                    <form method='POST' action='deletar_produto.php' onsubmit=\"return confirm('Tem certeza que deseja deletar este produto?');\">
                                        <input type='hidden' name='id' value='" . $row["id"] . "'>
                                        <input type='submit' value='Deletar'>
                                    </form>
                                </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Nenhum produto encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php if ($usuarioLogado): ?>
                <div>
                    <a href="?action=cadastrar"><input type="button" value="Cadastrar novo produto"></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

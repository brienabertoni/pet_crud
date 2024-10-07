<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : null;

$host = "127.0.0.1";
$usuario = "root";
$senha = "";
$db = "p1_dev_web";

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar os dados do produto pelo ID
    $sql = "SELECT * FROM produtos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
        exit;
    }
}

// Verificar se o formulário foi enviado para atualizar o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_produto = $conn->real_escape_string($_POST['nome_produto']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = $conn->real_escape_string($_POST['preco']);
    $estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;

    // Atualizar o produto no banco de dados
    $sql_update = "UPDATE produtos SET nome_produto = '$nome_produto', descricao = '$descricao', preco = $preco, estoque = $estoque WHERE id = $id";

    if ($conn->query($sql_update) === TRUE) {
        // Redirecionar para a página index.php após a atualização
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar o produto: " . $conn->error;
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Produto</title>
</head>
<body>
    <h1>Editar Produto</h1>
    <form method="POST" action="">
        <label for="nome_produto">Nome do Produto:</label>
        <input type="text" id="nome_produto" name="nome_produto" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required><br><br>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea><br><br>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" step="0.01" required><br><br>

        <label for="estoque">Quantidade em Estoque:</label>
        <input type="number" id="estoque" name="estoque" value="<?php echo htmlspecialchars($produto['estoque']); ?>" required><br><br>

        <input type="submit" value="Atualizar Produto">
    </form>

    <br>
    <a href="index.php">Voltar para a lista de produtos</a>
</body>
</html>

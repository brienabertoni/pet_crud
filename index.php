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


$sql = "SELECT id, nome_produto, descricao, preco, estoque, data_adicao FROM produtos";
$result = $conn->query($sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
<header class="header">
    <?php if ($usuarioLogado): ?>
        <p class="textboas">Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?>!</p> <!-- Texto "Bem-vindo" aqui -->
    <?php endif; ?>

    <div class="logo">
        <a href="index.php">
            <img src="./midia/Brivajhylogo.png" alt="Logo do Site" class="logo-img" width="200" height="auto">
        </a>
    </div>
    
    <?php if ($usuarioLogado): ?>
        <a class="btn" href="logout.php">Logout</a>
    <?php else: ?>
        <a class="btn" href="cadastro_form.php">Cadastro</a>
        <a class="btn" href="login.php">Login</a>
    <?php endif; ?>
</header>

                
        <?php if (isset($_GET['action']) && $_GET['action'] == 'cadastrar'): ?>
            <div class="cadastro">
                <h1>Cadastrar Produto</h1>
                <form method="POST" action="" class="form">
                    <label for="nome_produto">Nome do Produto:</label>
                    <input type="text" id="nome_produto" name="nome_produto" required>

                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="5"></textarea>

                    <label for="preco">Preço:</label>
                    <input type="number" id="preco" name="preco" step="0.01" required>

                    <label for="estoque">Quantidade em Estoque:</label>
                    <input type="number" id="estoque" name="estoque" required>

                    <input class="btn-submit" type="submit" value="Cadastrar Produto">
                </form>
                <a class="btn" href="?">Ver produtos cadastrados</a>
            </div>
        <?php else: ?>
            <h1>Produtos</h1>
            <table class="table">
                <thead>
                    <tr>
                        <?php if ($usuarioLogado): ?>
                            <th>ID</th>
                        <?php endif; ?>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <?php if ($usuarioLogado): ?>
                            <th>Quantidade em Estoque</th> 
                            <th>Data de Cadastro</th>
                            <th>Editar</th>
                            <th>Deletar</th>
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

                                
                                echo "<td><a class='btn-table' href='edit_produto.php?id=" . $row["id"] . "'>Editar</a></td>"; // Botão Editar
                                
                                
                                echo "<td><a class='btn-table' href='deletar_produto.php?id=" . $row["id"] . "'>Deletar</a></td>"; // Botão Deletar
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
                    <a class="btn" href="?action=cadastrar">Cadastrar novo produto</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    </div> 

</body>
</html>


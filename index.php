<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : null;

$host = "127.0.0.1";
$usuario = "root";
$senha= "";
$db="p1_dev_web";

$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$sql = "SELECT id, nome_produto, descricao, preco, estoque, data_adicao FROM produtos";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produtos</title>
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
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
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
                            echo "<td><a href='?id=" . $row["id"] . "'>Editar</a></td>";
                            echo "<td><a href='?id=" . $row["id"] . "'>Deletar</a></td>";
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
                <a href="#">Cadastrar Novo Produto</a>
            </div>
        <?php endif; ?>
    </div>
  </body>
</html>

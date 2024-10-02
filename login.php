<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nome_usuario'])) {
    header('Location: index.php');
    exit;
}

$host = "127.0.0.1";
$usuario = "root";
$senha= "";
$db="p1_dev_web";

$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) 
{
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) 
        {
            // Armazena o nome do usuário na sessão
            $_SESSION['nome_usuario'] = $user['nome'];
            header('Location: index.php');
            exit;
        } 
        else 
        {
            echo "Senha incorreta!";
        }
    } 
    else 
    {
        echo "Usuário não encontrado!";
    }
}

$conn->close();
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
  </head>
  <body>
    <div>
        <form action="login.php" method="post">
            <div>
              <h1>Login</h1>
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
            </div>
        </form>
    </div>
    <div>
        <button onclick="window.location.href='cadastro_form.php'">Ir para Cadastro</button>
    </div>
  </body>
</html>


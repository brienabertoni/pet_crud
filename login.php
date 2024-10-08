<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    
    if ($usuario == 'admin' && $senha == 'senha') {
        $_SESSION['nome_usuario'] = $usuario; 
        header("Location: index.php"); 
        exit();
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="POST" action="" class="form">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <input class="btn-submit" type="submit" value="Entrar">
        </form>
        <p>Ainda não tem uma conta? <a href="cadastro_forms.php">Cadastre-se</a></p>
    </div>
    
</body>
</html>

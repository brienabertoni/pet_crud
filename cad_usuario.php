<?php

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
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $tipo_usuario = isset($_POST['tipo_usuario']) ? $conn->real_escape_string($_POST['tipo_usuario']) : 'usuario';
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES ('$nome', '$email', '$senha', '$tipo_usuario')";

    if ($conn->query($sql) === TRUE) 
    {
        header('Location: login.php');
        exit;
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
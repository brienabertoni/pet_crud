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
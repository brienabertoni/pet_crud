<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "127.0.0.1";
$usuario = "root";
$senha= "";
$db="p1_dev_web";

$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = $conn->real_escape_string($_POST['id']);
    
   
    $sql_check = "SELECT * FROM produtos WHERE id = '$id_produto'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        
        $sql_delete = "DELETE FROM produtos WHERE id = '$id_produto'";
        if ($conn->query($sql_delete) === TRUE) {
            echo "Produto deletado com sucesso!";
        } else {
            echo "Erro ao deletar produto: " . $conn->error;
        }
    } else {
        echo "Produto não encontrado.";
    }
}

$conn->close();


header('Location: index.php');
exit;
?>
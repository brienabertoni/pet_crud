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


if (isset($_GET['id'])) {
    $id = $_GET['id'];

  
    $sql = "SELECT * FROM produtos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_produto = $conn->real_escape_string($_POST['nome_produto']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = $conn->real_escape_string($_POST['preco']);
    $estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;

    
    $sql_update = "UPDATE produtos SET nome_produto = '$nome_produto', descricao = '$descricao', preco = $preco, estoque = $estoque WHERE id = $id";

    if ($conn->query($sql_update) === TRUE) {
        
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
    <style>
        body {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
             font-style: normal;
             background-image: url('./midia/back-wallp\ \(1\).png'); 
            margin: 0;
            padding: 20px;
            color: #e97b37;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #e97b37;
        }

        .campo-editar {
            margin-bottom: 15px;
        }

        .label-editar {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .input-editar, .textarea-editar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .textarea-editar {
            resize: vertical;
            height: 100px;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            background-color: #e97b37;
            font-size: 16px;
        }

        .btn-submit:hover {
            background-color: #e97b37;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="body-editar">
    <div class="container">
        <h1>Editar Produto</h1>
        <form method="POST" action="">
            <div class="campo-editar">
                <label for="nome_produto" class="label-editar">Nome do Produto:</label>
                <input type="text" id="nome_produto" name="nome_produto" class="input-editar" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>
            </div>

            <div class="campo-editar">
                <label for="descricao" class="label-editar">Descrição:</label>
                <textarea id="descricao" name="descricao" class="textarea-editar"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="campo-editar">
                <label for="preco" class="label-editar">Preço:</label>
                <input type="number" id="preco" name="preco" class="input-editar" value="<?php echo htmlspecialchars($produto['preco']); ?>" step="0.01" required>
            </div>

            <div class="campo-editar">
                <label for="estoque" class="label-editar">Quantidade em Estoque:</label>
                <input type="number" id="estoque" name="estoque" class="input-editar" value="<?php echo htmlspecialchars($produto['estoque']); ?>" required>
            </div>

            <input type="submit" value="Atualizar Produto" class="btn-submit">
        </form>

        <a href="index.php" class="btn-back">Voltar para a lista de produtos</a>
    </div>
</body>
</html>

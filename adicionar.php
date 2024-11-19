<?php

$conn = new mysqli('localhost', 'root', '', 'gestao_alunos');

session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
}

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];

    if (empty($nome) || empty($telefone)) {
        echo "Todos os campos são obrigatórios.";
    } else {

        $stmt = $conn->prepare("INSERT INTO contactos (nome, telefone) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $telefone);  

        if ($stmt->execute()) {

            header('Location: index.php');
            exit();
        } else {
            echo "Erro ao inserir o contacto: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar contacto</title>
    <?php include 'linkstylesheet.php'; ?>
</head>
<body>
    <div class="container">
    <?php include 'navbar.php'; ?>
        <h1>Adicionar Novo Contacto</h1>

        <form method="post" action="adicionar.php">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" id="nome" required>

            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" class="form-control" id="telefone" required>
            <input type="submit" value="Adicionar" class="btn btn-success btn-primary">
        </form>

        <a class="back-link" href="index.php">Voltar à lista de contactos</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php

$conn->close();
?>
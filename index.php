<?php
$conn = new mysqli('localhost', 'root', '', 'gestao_alunos');

session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
}

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Count the total number of registered phone numbers
$phoneCountResult = $conn->query("SELECT COUNT(telefone) AS totalPhones FROM contactos WHERE telefone IS NOT NULL AND telefone != ''");
$phoneCount = $phoneCountResult->fetch_assoc()['totalPhones'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Lista de Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php include 'linkstylesheet.php'; ?>
</head>
<body>
<div class="container">
    <?php include 'navbar.php'; ?>


<?php
// Capture the category and search term from the form
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$searchTerm = filter_input(INPUT_POST, 'searchTerm', FILTER_SANITIZE_STRING);
?>


<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="w-100">
    <div class="row">
        <!-- Category Select with 4-column width -->
        <div class="col-md-3">
            <label for="category">Categoria:</label>
            <select class="form-select" name="category" id="category">
                <option value="" selected>--- Escolha uma categoria ---</option>
                <option value="id" <?php echo ($category === 'id') ? 'selected' : ''; ?>>ID</option>
                <option value="nome" <?php echo ($category === 'nome') ? 'selected' : ''; ?>>Nome</option>
                <option value="telefone" <?php echo ($category === 'telefone') ? 'selected' : ''; ?>>Telefone</option>
            </select>
        </div>
        
        <!-- Search Input with 8-column width -->
        <div class="col-md-9">
            <label for="searchTerm">Pesquisa:</label>
            <input type="text" name="searchTerm" id="searchTerm" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" class="form-control" placeholder="Insira um Termo de Pesquisa">
        </div>
    </div>
    
    <!-- Search Button with top padding -->
    <div class="row" style="padding-top: 10px;">
        <div class="col-md-12">
            <input class="btn btn-success btn-primary" type="submit" name="search" value="Pesquisar">
        </div>
    </div>
</form>

<table class="table table-striped table-hover mt-3">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>
    
    <?php
// Base query to get all results if no filter is applied
$sql = "SELECT * FROM contactos";

// Check if a search term or category was provided
if ($searchTerm || $category) {
    // Build WHERE conditions based on the provided inputs
    $conditions = [];
    $params = [];
    $paramTypes = "";
    
    if ($category && $searchTerm) {
        // Search in the selected category with the provided term
        $conditions[] = "$category LIKE ?";
        $params[] = "%" . $searchTerm . "%";
        $paramTypes .= "s";
    } elseif ($searchTerm) {
        // Search across all columns if no category is selected
        $conditions[] = "(id LIKE ? OR nome LIKE ? OR telefone LIKE ?)";
        $params = array_fill(0, 3, "%" . $searchTerm . "%");
        $paramTypes = "sss";
    }
    
    // Append conditions to SQL query
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    if ($paramTypes) {
        $stmt->bind_param($paramTypes, ...$params);
    }
    $stmt->execute();
    $categoryresult = $stmt->get_result();
} else {
    // Execute the base query without filtering
    $categoryresult = $conn->query($sql);
}

// Display results
if ($categoryresult->num_rows > 0) {
    while ($linha = $categoryresult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($linha['id']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['telefone']) . "</td>";
        echo "<td>
        <a href='editar.php?id=" . htmlspecialchars($linha['id']) . "'>Editar</a> | 
        <a href='eliminar.php?id=" . htmlspecialchars($linha['id']) . "' onclick='return confirm(\"Tens a certeza que desejas eliminar este registo?\")'>Eliminar</a>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum registo encontrado.</td></tr>";
}

if (isset($stmt)) {
    $stmt->close();
}
?>
</table>

<div style="text-align: center;">
    <a class="btn btn-success btn-primary" style="text-decoration:none;" href="adicionar.php">Adicionar Novo Contacto</a>
</div>
<!-- Display the phone count summary -->
<div class="alert alert-info mt-3">
    <strong>Contactos Registados:</strong> <?php echo $phoneCount; ?>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close();
?>

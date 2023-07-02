<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/../Classes/Usuarios.php';
require_once __DIR__ . '/../Classes/Cartegorias.php';

session_start();

if (!isset($_SESSION['user']) || (!in_array(unserialize($_SESSION['user'])->getUserType(), ['admin', 'grant_admin']))) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $categoryId = $_POST['category'];
    $price = $_POST['price'];
    $images = $_POST['images'];

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, category_id, price, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssiss', $title, $description, $date, $time, $location, $categoryId, $price, $images);

    if ($stmt->execute()) {
        $event_id = $stmt->insert_id;
        echo "Event inserted successfully. Event ID: " . $event_id;
    } else {
        echo "Error inserting event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Adicionar Eventos</title>
    <link rel="stylesheet" type="text/css" href="../CSS/addeventos.css">
</head>

<body>
    <header>
        <h1>Adicionar Eventos</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../home.php">Início</a></li>
            <?php if ($user instanceof Usuarios && in_array($user->getUserType(), ['admin', 'grant_admin'])): ?>
                <li><a href="../Pages/adicionarEventos.php">Adicionar evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios): ?>
                <li><a href="../Pages/paginaDeInscricaoDeEventos.php">Registrar evento</a></li>
                <li><a href="../Pages/perfil.php">Perfil</a></li>
                <li><a href="../Services/Deslogar.php">Sair</a></li>
            <?php else: ?>
                <li><a href="../Pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section>
        <h2>Adicionar Evento</h2>
        <!-- Formulário para adicionar um evento -->
        <form action="./adicionarEventos.php" method="POST">
            <div>
                <label for="title">Título:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div>
                <label for="date">Data:</label>
                <input type="date" name="date" id="date" required>
            </div>
            <div>
                <label for="time">Horário:</label>
                <input type="time" name="time" id="time" required>
            </div>
            <div>
                <label for="location">Localização:</label>
                <input type="text" name="location" id="location" required>
            </div>
            <div>
                <label for="category">Categoria:</label>

                <?php
                require_once __DIR__ . '/../Data/conexao.php';

                $categories = Categoria::getAll();

                if (!empty($categories)) {
                    echo "<select name='category' id='category' required>";
                    echo "<option value=''>Select a category</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>Nenhuma categoria encontrada.</p>";
                }
                ?>
            </div>
            <div>
                <label for="price">Preço:</label>
                <input type="number" name="price" id="price" step="0.01" required>
            </div>
            <div>
                <label for="images">Link Imagem:</label>
                <input type="url" name="images" id="images" required>
            </div>
            <div>
                <input id="input-btn" type="submit" value="Adicionar Evento">
            </div>
        </form>
    </section>

    <footer>

    </footer>
</body>

</html>
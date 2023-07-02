<?php
require_once __DIR__ . '/../Classes/Usuarios.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

// Verificar se o ID do usuário foi fornecido na URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userToEdit = Usuarios::getById($userId);
}

// Verificar se o formulário de edição foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $userType = $_POST['user_type'];

    // Atualizar os dados do usuário
    $userToEdit = new Usuarios($userId, $name, $email, $password, $userType);
    $userToEdit->save();

    // Redirecionar de volta para a página de administração de usuários
    header('Location: ./paginaAdmin');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../CSS/editaruser.css">
</head>

<body>
    <header>
        <h1>Editar Usuário</h1>
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

    <h1>Editar Usuário</h1>

    <?php if ($userToEdit): ?>
        <form action="./editarUsuario.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $userToEdit->getId(); ?>">
            <label for="name">Nome:</label>
            <input type="text" name="name" value="<?php echo $userToEdit->getName(); ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $userToEdit->getEmail(); ?>" required><br>
            <label for="user_type">Tipo de Usuário:</label>
            <select name="user_type" required>
                <option value="admin" <?php if ($userToEdit->getUserType() === 'admin')
                    echo 'selected'; ?>>Administrador
                </option>
                <option value="grant_admin" <?php if ($userToEdit->getUserType() === 'grant_admin')
                    echo 'selected'; ?>>
                    Administrador Master</option>
                <option value="user" <?php if ($userToEdit->getUserType() === 'user')
                    echo 'selected'; ?>>Usuário</option>
            </select><br>
            <button class="input-btn" type="submit">Salvar</button>
        </form>
    <?php else: ?>
        <p>Usuário não encontrado.</p>
    <?php endif; ?>

    <footer>
    </footer>
</body>

</html>
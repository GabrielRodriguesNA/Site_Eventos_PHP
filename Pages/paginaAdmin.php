<?php
require_once __DIR__ . '/../Classes/Usuarios.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if (!isset($_SESSION['user']) || ($user->getUserType() !== 'admin' && $user->getUserType() !== 'grant_admin')) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

// Verificar se a barra de pesquisa foi submetida
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = Usuarios::getUsers($search);
} else {
    $users = Usuarios::getUsers();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Administrador</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <header>
        <h1>Administrador</h1>
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

    <h1>Usuários</h1>

    <form action="./paginaAdmin.php" method="get">
        <input type="text" name="search" placeholder="Pesquisar por nome">
        <button class="input-btn" type="submit">Pesquisar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo de Usuário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?php echo $user->getName(); ?>
                    </td>
                    <td>
                        <?php echo $user->getEmail(); ?>
                    </td>
                    <td>
                        <?php echo $user->getUserType(); ?>
                    </td>
                    <td>
                        <a class="link" href="./editarUsuario?id=<?php echo $user->getId(); ?>">Editar |</a>
                        <a class="link" href="../Services/DeletarUsuario.php?id=<?php echo $user->getId(); ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Lista de eventos</h2>
    <a class="link" href="./listaDeEventos.php">Ver Lista de Eventos</a>
</body>

</html>
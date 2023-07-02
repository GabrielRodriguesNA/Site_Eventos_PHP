<?php
session_start();
require_once '../Classes/Usuarios.php';
require_once '../Classes/RegistrarEventos.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$registeredEvents = [];
if ($user) {
    $registeredEvents = Registrar::getRegisteredEvents($user->getId());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Perfil</title>
    <link rel="stylesheet" href="../css/perfil.css">
</head>

<body>
    <header>
        <h1>Perfil</h1>
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
        <h2>Perfil de usuário</h2>
        <?php if ($user): ?>
            <p>Nome:
                <?php echo $user->getName(); ?>
            </p>
            <p>Email:
                <?php echo $user->getEmail(); ?>
            </p>
            <p>Tipo de usuário:
                <?php echo $user->getUserType(); ?>
            </p>
            <?php if ($user instanceof Usuarios && $user->getUserType() === 'grant_admin'): ?>
                <a href="./paginaAdmin.php">Administrador</a>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios && $user->getUserType() === 'admin'): ?>
                <a href="./listaDeEventos.php">Lista de Eventos</a>
            <?php endif; ?>
            <br>
            <h3>Eventos registrados</h3>
            <?php if (!empty($registeredEvents)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Título do evento</th>
                            <th>Data do evento</th>
                            <th>Comentários</th>
                            <th>Excluir evento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registeredEvents as $event): ?>
                            <tr>
                                <td>
                                    <?php echo $event->getTitle(); ?>
                                </td>
                                <td>
                                    <?php echo $event->getDate(); ?>
                                </td>
                                <td><a class="link" href="./comentarios.php?event_id=<?php echo $event->getId(); ?>">Reviews</a>
                                </td>
                                <td>
                                    <form action="../Services/DeletarEventos.php" method="post">
                                        <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
                                        <button class="input-btn" type="submit">Excluir Evento</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No registered events.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>You are not logged in.</p>
        <?php endif; ?>
    </section>

</body>

</html>
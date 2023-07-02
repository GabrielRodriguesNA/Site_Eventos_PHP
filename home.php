<?php
session_start();

require_once './Classes/Usuarios.php';
require_once './Classes/Eventos.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$events = Eventos::getAll();

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $events = Eventos::searchEvents($query);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eventos Online - Início</title>
    <link rel="stylesheet" type="text/css" href="./css/index.css">
</head>

<body>
    <header>
        <h1>Eventos Online</h1>
    </header>

    <nav>
        <ul>
            <li><a href="./home.php">Início</a></li>
            <?php if ($user instanceof Usuarios && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                <li><a href="./Pages/adicionarEventos.php">Adicionar evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios): ?>
                <li><a href="./Pages/paginaDeInscricaoDeEventos.php">Registrar evento</a></li>
                <li><a href="./Pages/perfil.php">Perfil</a></li>
                <li><a href="./Services/Deslogar.php">Sair</a></li>
            <?php else: ?>
                <li><a href="./Pages/login.php">Entrar</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <form class="search-form" action="./home.php" method="GET">
        <input type="text" name="query" placeholder="Search events"
            value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
        <button class="search" type="submit">Pesquisar</button>
    </form>

    <section class="events">
        <br>
        <?php
        if (!empty($events)) {
            foreach ($events as $event) {
                $eventId = $event->getId();
                $eventDetailsUrl = "./Pages/detalhesEvento.php?id=$eventId";

                echo "<a href='$eventDetailsUrl' class='event'>";
                echo "<h3>" . $event->getTitle() . "</h3>";
                echo "<img class='img-evento' src='" . $event->getImages() . "' alt='Event Image'>";
                echo "<p>" . $event->getDescription() . "</p>";
                echo "<p>Date: " . $event->getDate() . "</p>";
                echo "<p>Time: " . $event->getTime() . "</p>";
                echo "<p>Location: " . $event->getLocation() . "</p>";
                echo "</a>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </section>

    <footer>

    </footer>
</body>

</html>
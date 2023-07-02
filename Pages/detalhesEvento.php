<?php
session_start();

require_once '../Classes/Usuarios.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Detalhes do Evento</title>
    <link rel="stylesheet" type="text/css" href="../css/detalhes.css">
</head>

<body>
    <header>
        <h1>Detalhes</h1>
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

    <section class="event-details">
        <?php
        require_once __DIR__ . '/../Data/conexao.php';
        require_once '../Classes/Eventos.php';


        if (isset($_GET['id'])) {
            $eventId = $_GET['id'];
            $event = Eventos::getById($eventId);

            if ($event) {
                echo "<h2>{$event->getTitle()}</h2>";
                echo "<img src='{$event->getImages()}' alt='Event Image'>";
                echo "<p>Description: {$event->getDescription()}</p>";
                echo "<p>Date: {$event->getDate()}</p>";
                echo "<p>Time: {$event->getTime()}</p>";
                echo "<p>Location: {$event->getLocation()}</p>";

                // Botão para redirecionar para process_registration.php
                echo "<form action='../Pages/paginaDeInscricaoDeEventos.php' method='post'>";
                echo "<input type='hidden' name='event_id' value='{$eventId}'>";
                echo "<input class='input-btn' type='submit' name='payment' value='Fazer Inscrição'>";
                echo "</form>";
            } else {
                echo "<p>Event not found.</p>";
            }
        } else {
            echo "<p>Invalid event ID.</p>";
        }
        ?>
    </section>
    <br>

</body>

</html>
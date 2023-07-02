<?php
session_start();

require_once '../Classes/Usuarios.php';
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/Eventos.php';

if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'grant_admin')) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Lista de Eventos</title>
    <link rel="stylesheet" type="text/css" href="../css/listaeventos.css">
</head>

<body>
    <header>
        <h1>Lista de Eventos</h1>
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

    <section class="event-list">
        <?php


        // Verificar se a barra de pesquisa foi submetida
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $events = Eventos::searchEvents($search);
        } else {
            $events = Eventos::getAll();
        }

        echo "<div class='search-bar'>";
        echo "<form action='listaDeEventos.php' method='GET'>";
        echo "<input type='text' name='search' placeholder='Pesquisar evento'>";
        echo "<button class='input-btn' type='submit'>Pesquisar</button>";
        echo "</form>";
        echo "</div>";

        if (!empty($events)) {
            echo "<h2>Event List</h2>";
            echo "<div class='event-container'>";

            foreach ($events as $event) {
                $eventId = $event->getId();
                $eventTitle = $event->getTitle();
                $eventImage = $event->getImages();

                echo "<div class='event'>";
                echo "<a href='./editarEvento.php?id={$eventId}'>";
                echo "<img src='{$eventImage}' alt='Event Image'>";
                echo "<h3>{$eventTitle}</h3>";
                echo "</a>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </section>

    <footer>
    </footer>
</body>

</html>
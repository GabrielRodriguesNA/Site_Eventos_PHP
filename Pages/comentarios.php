<?php
session_start();
require_once '../Classes/Usuarios.php';
require_once '../Classes/Eventos.php';
require_once '../Classes/Comentarios.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$event_id = null;
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
}

$event = null;
$reviews = [];
if ($event_id) {
    $event = Eventos::getById($event_id);
    $reviews = Comentarios::getReviewsByEventId($event_id);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Avaliações do Evento</title>
    <link rel="stylesheet" type="text/css" href="../CSS/comentarios.css">
</head>

<body>
    <header>
        <h1>Avaliações do Evento</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../home.php">Início</a></li>
            <?php if ($user instanceof Usuarios && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                <li><a href="../Pages/adicionarEventos.php">Adicionar Evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios): ?>
                <li><a href="../Pages/paginaDeInscricaoDeEventos.php">Registrar Evento</a></li>
                <li><a href="../Pages/perfil.php">Perfil</a></li>
                <li><a href="../Services/Deslogar.php">Sair</a></li>
            <?php else: ?>
                <li><a href="../Pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section>
        <h2>Avaliações do Evento</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <?php
                $reviewer = Usuarios::getById($review['user_id']);
                ?>
                <div class="review">
                    <p>Usuário:
                        <?php echo $reviewer->getName(); ?>
                    </p>
                    <p>Avaliação:
                        <?php echo $review['score']; ?>
                    </p>
                    <p>Comentário:
                        <?php echo $review['comment']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma avaliação disponível para este evento.</p>
        <?php endif; ?>
        <br>
        <?php if ($user): ?>
            <h3>Adicionar Avaliação</h3>
            <form class="add-review-form" action="../Services/AdicionarComentario.php" method="post">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <label for="score">Avaliação:</label>
                <select name="score" id="score">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <label for="comment">Comentário:</label>
                <textarea name="comment" id="comment" rows="4"></textarea>
                <button class="input-btn" type="submit">Enviar Avaliação</button>
            </form>
            <a href="./perfil.php">voltar</a>
        <?php else: ?>
            <p>Você precisa estar logado para adicionar uma avaliação.</p>
        <?php endif; ?>
    </section>

    <footer>
    </footer>
</body>

</html>
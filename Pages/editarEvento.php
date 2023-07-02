<?php
session_start();

require_once '../Classes/Usuarios.php';
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/RegistrarEventos.php';


if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'grant_admin')) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = $_GET['id'];


    $event = Eventos::getById($eventId);

    if ($event) {

        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Editar Evento</title>
            <link rel="stylesheet" type="text/css" href="../CSS/editarevento.css">
        </head>

        <body>
            <header>
                <h1>Editar Evento</h1>
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
                <h2>Editar Evento</h2>
                <form action="../Services/AtualizarEvento.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                    <label>Título:</label>
                    <input type="text" name="title" value="<?php echo $event->getTitle(); ?>"><br>
                    <label>Descrição:</label>
                    <textarea name="description"><?php echo $event->getDescription(); ?></textarea><br>
                    <label>Data:</label>
                    <input type="text" name="date" value="<?php echo $event->getDate(); ?>"><br>
                    <label>Hora:</label>
                    <input type="text" name="time" value="<?php echo $event->getTime(); ?>"><br>
                    <label>Localização:</label>
                    <input type="text" name="location" value="<?php echo $event->getLocation(); ?>"><br>
                    <label>Categoria:</label>
                    <input type="text" name="category" value="<?php echo $event->getCategoryId(); ?>"><br>
                    <label>Preço':</label>
                    <input type="text" name="price" value="<?php echo $event->getPrice(); ?>"><br>
                    <label>Imagens:</label>
                    <input type="text" name="images" value="<?php echo $event->getImages(); ?>"><br>

                    <button class="input-btn" type="submit">Atualizar</button>
                </form>

                <br>

                <h2>Excluir Evento</h2>
                <form action="../Services/DeletarRegistrarEventos.php" method="POST" onsubmit="return confirmDelete();">
                    <input type="hidden" name="id" value="<?php echo $eventId; ?>">
                    <button class="input-btn" type="submit">Excluir</button>
                </form>
            </section>

            <script>
                function confirmDelete() {
                    return confirm("Tem certeza que deseja deletar o evento?");
                }
            </script>


            <footer>

            </footer>
        </body>

        </html>
        <?php
    } else {
        echo "<p>Evento não encontrado.</p>";
    }
} else {
    echo "<p>404.</p>";
}
?>
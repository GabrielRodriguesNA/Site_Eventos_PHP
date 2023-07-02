<?php
session_start();
require_once '../Classes/Usuarios.php';
require_once '../Classes/Cartegorias.php';
require_once '../Classes/Eventos.php';
require_once '../Classes/RegistrarEventos.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
    $userId = $user->getId();
} else {
    header('Location: ../home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id'];
    $paymentStatus = isset($_POST['payment']) ? 'paid' : 'pending';

    $registrationId = Registrar::createRegistration($userId, $eventId, $paymentStatus);

    if ($registrationId) {
        header('Location: ../Pages/perfil.php');
        exit();
    } else {
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gerenciar Eventos</title>
    <link rel="stylesheet" type="text/css" href="../CSS/inscricaoeventos.css">
</head>

<body>
    <header>
        <h1>Gerenciar Eventos</h1>
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
                <li><a href="../Pages/login.php">Entrar</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section>
        <h2>Registro</h2>
        <div>
            <input type="text" id="searchInput" placeholder="Buscar eventos">
            <button id="searchButton">Buscar</button>
        </div>
        <form method="POST" action="">
            <div>
                <label id="categoria" for="category">Categoria:</label>
                <?php
                $categories = Categoria::getAll();

                if (!empty($categories)) {
                    echo "<select name='category' id='category'>";
                    echo "<option value=''>Selecione uma categoria</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>Nenhuma categoria encontrada.</p>";
                }
                ?>
            </div>

            <div id="eventsContainer">

            </div>

            <label for="payment">Pagamento:</label>
            <input type="checkbox" id="payment" name="payment">

            <input type="hidden" id="event_id" name="event_id">

            <button id="input-btn" type="submit">Registrar</button>
        </form>
    </section>

    <footer>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#category').change(function () {
                var categoryId = $(this).val();

                if (categoryId !== '') {
                    $.ajax({
                        url: '../Services/GetEventos.php',
                        method: 'POST',
                        data: { category_id: categoryId },
                        success: function (response) {
                            $('#eventsContainer').html(response);
                        }
                    });
                } else {
                    $('#eventsContainer').html('');
                }
            });

            $(document).on('click', '.event-link', function (e) {
                e.preventDefault();
                var eventId = $(this).data('event-id');
                var eventDetails = $(this).siblings('.event-details');
                eventDetails.toggleClass('hidden');
                $('#event_id').val(eventId);
            });

            // Função para realizar a pesquisa
            function performSearch(searchText) {
                $.ajax({
                    url: '../Services/PesquisaEventos.php',
                    method: 'POST',
                    data: { search_text: searchText },
                    success: function (response) {
                        $('#eventsContainer').html(response);
                    }
                });
            }

            // Lidar com o clique no botão de pesquisa
            $('#searchButton').click(function (e) {
                e.preventDefault();
                var searchText = $('#searchInput').val();
                performSearch(searchText);
            });

            // Lidar com a tecla Enter pressionada no campo de pesquisa
            $('#searchInput').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var searchText = $(this).val();
                    performSearch(searchText);
                }
            });
        });
    </script>
</body>

</html>
<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/Eventos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eventId = $_POST['id'];

    // Recupere os campos do formulário
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $categoryId = $_POST['category'];
    $price = $_POST['price'];
    $images = $_POST['images'];

    // Valide os campos conforme necessário

    // Atualize o evento no banco de dados
    $event = Eventos::getById($eventId);
    if ($event) {
        $event->setTitle($title);
        $event->setDescription($description);
        $event->setDate($date);
        $event->setTime($time);
        $event->setLocation($location);
        $event->setCategoryId($categoryId);
        $event->setPrice($price);
        $event->setImages($images);

        // Salve as alterações no banco de dados
        $event->save();

        // Redirecione para a página de visualização do evento atualizado
        header("Location: ../Pages/detalhesEvento.php?id={$eventId}");
        exit();
    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}

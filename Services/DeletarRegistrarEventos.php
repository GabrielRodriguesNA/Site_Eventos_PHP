<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/Eventos.php';
require_once '../Classes/RegistrarEventos.php';
require_once '../Classes/Comentarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eventId = $_POST['id'];


        // Excluir todas as registrations relacionadas ao evento
        Registrar::deleteById($eventId);
        Comentarios::deleteById($eventId);
        Eventos::deleteById($eventId);

        // Redirecionar para a página principal ou outra página adequada
        header("Location: ../home.php");
        exit();

}
?>

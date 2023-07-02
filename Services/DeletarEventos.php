<?php
session_start();
require_once '../Classes/RegistrarEventos.php';

if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    
    // Verificar se o usuário tem permissão para excluir o evento (adicionar lógica apropriada aqui)
    
    // Excluir o evento
    Registrar::deleteById($eventId);

    // Redirecionar de volta para o perfil do usuário
    header('Location: ../Pages/perfil.php');
    exit();
} else {
    // Se não foi fornecido o ID do evento, redirecionar para o perfil do usuário
    header('Location: ../Pages/perfil.php');
    exit();
}
?>
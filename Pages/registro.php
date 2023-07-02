<?php
require_once '../Classes/Usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = 'regular';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $user = new Usuarios($id, $name, $email, $hashedPassword, $userType);
    $user->save();

    header('Location: ./login.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cadastro</title>
    <link rel="stylesheet" type="text/css" href="../css/registro.css">
</head>


<body>
    <header>
        <h1>Cadastre-se agora</h1>
    </header>

    <section>
        <h2>Login</h2>
        <form action="registro.php" method="POST">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <input class="input-btn" type="submit" value="Register">
        </form>
        <br>
        <p>Já tem conta?<a href="./login.php"> Entre agora</a></p>
    </section>
</body>

</html>
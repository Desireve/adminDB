<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Считывание логина и пароля из файла
    list($stored_login, $stored_password) = explode(':', trim(file_get_contents('pass.txt')));

    // Сравнение введенных логина и пароля с данными из файла
    if ($login == $stored_login && $password == $stored_password) {
        // Перенаправление на страницу secure.php
        header('Location: secure.php');
        exit;
    } else {
        // Вместо перенаправления, отправляем сообщение об ошибке
        echo "<script>alert('Неверный логин или пароль!');</script>";
        // Подключаем файл index.html, чтобы пользователь оставался на странице входа
        include('index.html');
        exit;
    }
}
?>

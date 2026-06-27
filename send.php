<?php
session_start();

// если пользователь не вошёл — отправляем на логин
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

try {
    $db = new PDO('sqlite:database.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка БД: " . $e->getMessage());
}

// получаем данные пользователя
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Пользователь не найден");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>

     <header class="header">
        <div class="container header__container">
            <a href="index.html" class="logo">Кино<span>Поиск</span></a>
            <nav><ul class="nav-list">
                <li><a href="index.html" class="nav-link">Главная</a></li>
                <li><a href="search.html" class="nav-link">Поиск</a></li>
                <li><a href="favorites.html" class="nav-link">Избранное</a></li>
                <li><a href="send.php" class="nav-link active">Профиль</a></li>
                
            </ul></nav>
        </div>
    </header>

    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f2f4f8;
    color: #222;
}

.container {
    max-width: 600px;
    margin: 60px auto;
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}

.item {
    background: #f7f9fc;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 10px;
    border-left: 4px solid #3498db;
}

.label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #34495e;
}

.value {
    color: #555;
}

.btn {
    display: block;
    text-align: center;
    margin-top: 25px;
    padding: 12px;
    background: #e74c3c;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.3s;
}

.btn:hover {
    background: #c0392b;
} 
    </style>

</head>
<body>

<div class="container">

    <h1>Мой профиль</h1>

    <div class="item">
        <div class="label">ФИО:</div>
        <div class="value"><?= htmlspecialchars($user['full_name']) ?></div>
    </div>

    <div class="item">
        <div class="label">Email:</div>
        <div class="value"><?= htmlspecialchars($user['email']) ?></div>
    </div>

    <div class="item">
        <div class="label">Телефон:</div>
        <div class="value"><?= htmlspecialchars($user['phone'] ?? 'не указан') ?></div>
    </div>

    <div class="item">
        <div class="label">ID пользователя:</div>
        <div class="value"><?= $user['id'] ?></div>
    </div>

    <a class="btn" href="logout.php">Выйти</a>

</div>

</body>
</html>
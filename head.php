<?php
include_once('connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userPhone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LuxRent — премиальный прокат</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta name="description" content="LuxRent — премиальный прокат автомобилей в Санкт-Петербурге." />
</head>
<body class="page">
    <header class="site-header header">
        <div class="container header-inner header__inner">
            <a class="brand logo" href="index.php">
                <span class="brand-logo">LR</span>
                <span>
                    <span class="brand-title">LuxRent</span>
                    <span class="brand-subtitle">Прокат автомобилей в Санкт-Петербурге</span>
                </span>
            </a>
            <nav class="nav">
                <a href="index.php">Главная</a>
                <a href="information.php">Информация</a>
                <a href="cataloge.php">Каталог</a>
                <a href="rental-terms.php">Условия</a>
                <a href="contacts.php">Контакты</a>
                <a class="nav-highlight" href="booking.php">Забронировать</a>
            </nav>
            <div class="auth-links header__actions">
                <?php if ($userPhone): ?>
                    <a class="auth-user" href="profile.php">
                        <?php echo htmlspecialchars($userPhone); ?>
                    </a>
                    <form method="post" action="logout.php">
                        <button class="btn btn-outline" type="submit">Выйти</button>
                    </form>
                <?php else: ?>
                    <a href="login.php">Вход</a>
                    <a class="btn btn-primary" href="registration.php">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="main-content">

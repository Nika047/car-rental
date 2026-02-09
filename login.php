<?php
session_start();
include('head.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

	    // "SELECT * FROM sem8.арендатор WHERE Логин = '$login' AND Пароль = $password");

    $phoneSafe = mysqli_real_escape_string($link, $phone);
    $query = "SELECT * FROM sem8.арендатор WHERE Телефон = '$phoneSafe' LIMIT 1";
    $result = mysqli_query($link, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hash = $row['Пароль'];
        if (password_verify($password, $hash)) {
            $_SESSION['user_phone'] = $phone;
            header('Location: profile.php');
            exit();
        }
    }
    $message = 'Неверный телефон или пароль.';
}
?>

<section class="section">
    <h2>Вход</h2>
    <div class="card">
        <?php if (!empty($message)): ?>
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post" class="form-grid">
            <div>
                <label>Телефон</label>
                <input type="text" name="phone" required />
            </div>
            <div>
                <label>Пароль</label>
                <input type="password" name="password" required />
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Войти</button>
                <a class="btn btn-outline" href="registration.php">Регистрация</a>
            </div>
        </form>
    </div>
</section>

<?php include('foot.php'); ?>

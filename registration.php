<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('connection.php');

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if (!empty($phone) && !empty($password) && !empty($confirm)) {
        if ($password !== $confirm) {
            $message = 'Пароли не совпадают. Проверьте ввод.';
        } else {
            $check_stmt = $link->prepare("SELECT Телефон FROM арендатор WHERE Телефон = ? LIMIT 1");
            if ($check_stmt) {
                $check_stmt->bind_param('s', $phone);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    $message = 'Аккаунт с таким номером телефона уже существует!';
                    $check_stmt->close();
                } else {
                    $check_stmt->close();
                    
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $link->prepare("INSERT INTO арендатор(Фамилия, Имя, Отчество, Телефон, Паспорт, Логин, Пароль) VALUES (?,?,?,?,?,?,?)"); 
                    if ($stmt) {
                        $empty = '';

                        $stmt->bind_param('sssssss', $empty, $empty, $empty, $phone, $empty, $phone, $hashed);
                        if ($stmt->execute()) {
                            $_SESSION['user_phone'] = $phone;
                            $stmt->close();
                            header('Location: index.php');
                            exit();
                        } else {
                            $message = 'Ошибка при регистрации. Попробуйте позже.';
                        }
                        $stmt->close();
                    }
                }
            } else {
                $message = 'Ошибка подготовки запроса. Попробуйте позже.';
            }
        }
    } else {
        $message = 'Заполните все поля.';
    }
}
?>

<?php include('head.php'); ?>

<section class="section">
    <h2>Регистрация</h2>
    <div class="card">
        <?php if (!empty($message)): ?>
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post" class="form-grid">
            <div>
                <label>Телефон</label>
                <input type="text" name="phone" placeholder="+7 (999) 000-00-00" required />
            </div>
            <div class="input-row">
                <label>Пароль</label>
                <div class="input-wrapper">
                    <input type="password" name="password" required />
                    <button class="toggle-password" type="button" data-target="password">Показать</button>
                </div>
            </div>
            <div class="input-row">
                <label>Подтвердите пароль</label>
                <div class="input-wrapper">
                    <input type="password" name="confirm_password" required />
                    <button class="toggle-password" type="button" data-target="confirm_password">Показать</button>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Создать аккаунт</button>
                <a class="btn btn-outline" href="login.php">У меня уже есть аккаунт</a>
            </div>
        </form>
        <script>
            document.querySelectorAll('.toggle-password').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = button.parentElement.querySelector('input');
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Скрыть' : 'Показать';
                });
            });
        </script>
    </div>
</section>

<?php include('foot.php'); ?>

<?php
include('head.php');

$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
$date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
$car = isset($_POST['car_id']) ? $_POST['car_id'] : '';

$loginID = null;

if (isset($_SESSION['user_phone'])) {
  $login = $_SESSION['user_phone'];
  $loginID = mysqli_query($link, "SELECT `Код арендатора` FROM `арендатор` WHERE `Логин`='$login'");
}
else {
  $login = $phone;
  $loginID = mysqli_query($link, "SELECT `Код арендатора` FROM `арендатор` WHERE `Телефон`='$phone'");
}


if ($loginID && mysqli_num_rows($loginID) > 0) {
    $loginID = mysqli_fetch_row($loginID);
    $renterId = $loginID[0];
    
    $newRent = mysqli_query($link, "INSERT INTO sem8.аренда(`Начало проката`, `Конец проката`, `Код арендатора`) VALUES ('$date_start', '$date_end', $renterId)");
    
    if ($newRent) {
        $rentID = mysqli_query($link, "SELECT `Код аренды` FROM `аренда` WHERE `Код арендатора` = $renterId ORDER BY `Код аренды` DESC LIMIT 1");
        
        if ($rentID && mysqli_num_rows($rentID) > 0) {
            $rentID = mysqli_fetch_row($rentID);
            $rentId = $rentID[0];
            
            $sql = mysqli_query($link, "INSERT INTO `прокат автомобиля`(`Код машины`, `Код аренды`) VALUES ($car, $rentId)");
            
            if ($sql) {
                echo "<div class='alert alert-success'>Бронирование успешно оформлено!</div>";
            } else {
                echo "<div class='alert alert-danger'>Ошибка при добавлении автомобиля в прокат</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Не удалось получить ID аренды</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Ошибка при создании аренды</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Пользователь не найден</div>";
}
?>


<section class="section">
    <h2>Заявка отправлена</h2>
    <div class="alert">
        <p><strong>Спасибо за использование сервиса LuxRent!</strong></p>
        <p>Ваша заявка успешно принята и передана менеджеру.</p>
        <p>Мы свяжемся с вами в ближайшее время, чтобы подтвердить детали.</p>
        <br>
        <p><strong>Данные заявки</strong></p>
        <p><strong>Телефон:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Период аренды:</strong> <?php echo htmlspecialchars($date_start); ?> — <?php echo htmlspecialchars($date_end); ?></p>
        <p><strong>Выбранный вариант:</strong> <?php echo htmlspecialchars($car); ?></p>
        <br>
        <p>Если потребуется внести изменения, просто оформите новую заявку или свяжитесь с нами по телефону.</p>
    </div>
    <div class="form-actions" style="margin-top: 16px;">
        <a class="btn btn-primary" href="index.php">Вернуться на главную</a>
        <a class="btn btn-outline" href="booking.php">Новая заявка</a>
    </div>
</section>

<?php include('foot.php'); ?>

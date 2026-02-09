<?php
session_start();
include('head.php');

if (!isset($_SESSION['user_phone'])) {
    header('Location: login.php');
    exit();
}

$phone = $_SESSION['user_phone'];
$renter_query = mysqli_query($link, "SELECT `Код арендатора` FROM `арендатор` WHERE `Телефон` = '$phone'");

if (!$renter_query || mysqli_num_rows($renter_query) == 0) {
    echo "<p>Ошибка: арендатор не найден</p>";
    include('foot.php');
    exit();
}

$renter_result = mysqli_fetch_assoc($renter_query);
$renter_id = $renter_result['Код арендатора'];

$history = mysqli_query($link, 
    "SELECT `Начало проката`, `Конец проката`, Номер, Залог, `Стоимость за сутки`, Модель 
     FROM `аренда` 
     INNER JOIN арендатор ON аренда.`Код арендатора` = арендатор.`Код арендатора` 
     INNER JOIN `прокат автомобиля` ON аренда.`Код аренды` = `прокат автомобиля`.`Код аренды` 
     INNER JOIN машина ON `прокат автомобиля`.`Код машины` = машина.`Код машины` 
     INNER JOIN модель ON машина.`Код модели` = модель.`Код модели` 
     WHERE арендатор.`Код арендатора` = $renter_id");

$bookings = [];

if ($history) {
    while ($row = mysqli_fetch_assoc($history)) {
        $bookings[] = [
            'start' => $row['Начало проката'],
            'end' => $row['Конец проката'],
            'model' => $row['Модель'],
            'number' => $row['Номер'],
            'deposit' => $row['Залог'],
            'price_per_day' => $row['Стоимость за сутки']
        ];
    }
}
?>

<section class="section">
    <h2>Личный кабинет</h2>
    <div class="card">
        <p>Вы вошли как: <strong><?php echo htmlspecialchars($phone); ?></strong></p>
        <form method="post" action="logout.php" style="margin-top: 12px;">
            <button class="btn btn-outline" type="submit">Выйти</button>
        </form>
    </div>
</section>

<section class="section">
    <h2>Ваши бронирования</h2>
    <div class="card">
        <?php if (count($bookings) > 0): ?>
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>Дата начала</th>
                        <th>Дата окончания</th>
                        <th>Модель</th>
                        <th>Номер</th>
                        <th>Залог</th>
                        <th>Стоимость за сутки</th>
                    </tr>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['start']); ?></td>
                            <td><?php echo htmlspecialchars($booking['end']); ?></td>
                            <td><?php echo htmlspecialchars($booking['model']); ?></td>
                            <td><?php echo htmlspecialchars($booking['number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['deposit']); ?> ₽</td>
                            <td><?php echo htmlspecialchars($booking['price_per_day']); ?> ₽</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <p>У вас пока нет бронирований.</p>
        <?php endif; ?>
    </div>
</section>

<?php include('foot.php'); ?>

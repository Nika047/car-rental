<?php
session_start();

include('head.php');

$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
$date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
$price_filter = isset($_POST['price_filter']) ? $_POST['price_filter'] : '';

$conditions = [];
if (!empty($price_filter)) {
    $conditions[] = "`Стоимость за сутки` <= " . intval($price_filter);
}

$query = "SELECT `Код машины`, Номер, `Цвет машины`, Стоимость, Залог, `Стоимость за сутки`, Двигатель, Модель, Фото FROM машина INNER JOIN модель ON машина.`Код модели` = модель.`Код модели` INNER JOIN `технические характеристики` ON машина.`Код ТХ` = `технические характеристики`.`Код ТХ`";
if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($link, $query);
?>

<section class="section">
    <h2>Подходящие варианты</h2>
    <div class="card">
        <p>Телефон: <strong><?php echo htmlspecialchars($phone); ?></strong></p>
        <p>Период: <strong><?php echo htmlspecialchars($date_start); ?></strong> — <strong><?php echo htmlspecialchars($date_end); ?></strong></p>
    </div>
</section>

<section class="section">
    <div class="catalog-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    $car_id = htmlspecialchars($row["Код машины"]);
                    $model = htmlspecialchars($row["Модель"]);
                    $color = htmlspecialchars($row["Цвет машины"]);
                    $engine = htmlspecialchars($row["Двигатель"]);
                    $price = htmlspecialchars($row["Стоимость за сутки"]);
                    $deposit = htmlspecialchars($row["Залог"]);
                    $image = htmlspecialchars($row["Фото"]);
                ?>
                <article class="catalog-card">
                    <div class="catalog-image">
                        <img src="<?php echo $image; ?>" alt="Фото <?php echo $model; ?>" />
                    </div>
                    <div class="catalog-body">
                        <div class="catalog-title">
                            <h3><?php echo $model; ?></h3>
                            <span class="chip"><?php echo $color; ?></span>
                        </div>
                        <div class="catalog-meta">
                            <span>Двигатель: <?php echo $engine; ?></span>
                            <span>Залог: <?php echo $deposit; ?> ₽</span>
                        </div>
                        <div class="catalog-price"><?php echo $price; ?> ₽ / сутки</div>
                        <div class="catalog-actions">
                            <form method="post" action="booking-confirmation.php">
                                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car_id); ?>" />
                                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>" />
                                <input type="hidden" name="date_start" value="<?php echo htmlspecialchars($date_start); ?>" />
                                <input type="hidden" name="date_end" value="<?php echo htmlspecialchars($date_end); ?>" />
                                <input type="hidden" name="car" value="<?php echo $model; ?>" />
                                <button class="btn btn-primary" type="submit">Выбрать</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="card">Подходящих авто не найдено.</div>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="card">
        <h3>Неважно какая машина?</h3>
        <form method="post" action="booking-confirmation.php">
            <input type="hidden" name="car_id" value="1" />
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>" />
            <input type="hidden" name="date_start" value="<?php echo htmlspecialchars($date_start); ?>" />
            <input type="hidden" name="date_end" value="<?php echo htmlspecialchars($date_end); ?>" />
            <input type="hidden" name="car" value="Любая машина" />
            <button class="btn btn-outline" type="submit">Любая машина</button>
        </form>
    </div>
</section>

<?php include('foot.php'); ?>

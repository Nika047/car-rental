<?php
if (isset($_POST["Сбросить"])) {
    header('Location: cataloge.php');
    exit();
}

include('head.php');

function outputTable($result)
{
    echo "<div class='catalog-grid'>";

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $model = htmlspecialchars($row["Модель"]);
            $color = htmlspecialchars($row["Цвет машины"]);
            $engine = htmlspecialchars($row["Двигатель"]);
            $price = htmlspecialchars($row["Стоимость за сутки"]);
            $deposit = htmlspecialchars($row["Залог"]);
            $image = htmlspecialchars($row["Фото"]);

            echo "<article class='catalog-card'>";
            echo "<div class='catalog-image'><img src='{$image}' alt='Фото {$model}'></div>";
            echo "<div class='catalog-body'>";
            echo "<div class='catalog-title'>";
            echo "<h3>{$model}</h3>";
            echo "<span class='chip'>{$color}</span>";
            echo "</div>";
            echo "<div class='catalog-meta'>";
            echo "<span>Двигатель: {$engine}</span>";
            echo "<span>Залог: {$deposit} ₽</span>";
            echo "</div>";
            echo "<div class='catalog-price'>{$price} ₽ / сутки</div>";
            echo "<div class='catalog-actions'>";
            echo "<a class='btn btn-primary' href='booking.php?from=catalog'>Забронировать</a>";
            echo "<a class='btn btn-outline' href='booking.php?from=catalog'>Оставить заявку</a>";
            echo "</div>";
            echo "</div>";
            echo "</article>";
        }
    } else {
        echo "<div class='card'>Подходящих автомобилей не найдено.</div>";
    }

    echo "</div>";
}

$result = mysqli_query($link, "SELECT Номер, `Цвет машины`, Стоимость, Залог, `Стоимость за сутки`, Двигатель, Модель, Фото FROM машина INNER JOIN модель ON машина.`Код модели` = модель.`Код модели` INNER JOIN `технические характеристики` ON машина.`Код ТХ` = `технические характеристики`.`Код ТХ`");

$filterForm = isset($_POST["Фильтрация"]);
$sortForm = isset($_POST["Сортировка"]);

if (isset($_POST["применитьФильтрацию"])) {
    $color = $_POST["color"];
    $result = mysqli_query($link, "SELECT Номер, `Цвет машины`, Стоимость, Залог, `Стоимость за сутки`, Двигатель, Модель, Фото FROM машина INNER JOIN модель ON машина.`Код модели` = модель.`Код модели` INNER JOIN `технические характеристики` ON машина.`Код ТХ` = `технические характеристики`.`Код ТХ` WHERE `Цвет машины` = '" . $color . "'");
}

if (isset($_POST["применитьСортировку"])) {
    $sort = $_POST["cost"];
    $param = ($sort == "Возрастание") ? "ASC" : "DESC";
    $result = mysqli_query($link, "SELECT Номер, `Цвет машины`, Стоимость, Залог, `Стоимость за сутки`, Двигатель, Модель, Фото FROM машина INNER JOIN модель ON машина.`Код модели` = модель.`Код модели` INNER JOIN `технические характеристики` ON машина.`Код ТХ` = `технические характеристики`.`Код ТХ` ORDER BY `Стоимость за сутки` $param");
}
?>

<section class="section">
    <h2>Каталог автомобилей</h2>
    <form method="post" class="form-actions">
        <button class="btn btn-outline" type="submit" name="Фильтрация">Фильтрация</button>
        <button class="btn btn-outline" type="submit" name="Сортировка">Сортировка</button>
        <button class="btn btn-outline" type="submit" name="Сбросить">Сбросить</button>
    </form>

    <?php if ($filterForm): ?>
        <form method="post" class="form-actions" style="margin-top: 12px;">
            <select name="color">
                <option selected value="Белый">Белый</option>
                <option value="Черный">Черный</option>
                <option value="Серый">Серый</option>
                <option value="Красный">Красный</option>
            </select>
            <button class="btn btn-primary" type="submit" name="применитьФильтрацию">Применить</button>
            <button class="btn btn-outline" type="submit" name="Сбросить">Сбросить</button>
        </form>
    <?php endif; ?>

    <?php if ($sortForm): ?>
        <form method="post" class="form-actions" style="margin-top: 12px;">
            <label><input type="radio" name="cost" value="Возрастание"> По возрастанию</label>
            <label><input type="radio" name="cost" value="Убывание"> По убыванию</label>
            <button class="btn btn-primary" type="submit" name="применитьСортировку">Применить</button>
            <button class="btn btn-outline" type="submit" name="Сбросить">Сбросить</button>
        </form>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <?php outputTable($result); ?>
    </div>
</section>

<?php include('foot.php'); ?>

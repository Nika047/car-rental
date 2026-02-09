<?php include('head.php'); 

$isLoggedIn = isset($_SESSION['user_phone']);
$login = $isLoggedIn ? $_SESSION['user_phone'] : '';
$fromCatalog = isset($_GET['from']) && $_GET['from'] === 'catalog';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_start = $_POST['date_start'] ?? '';
    $date_end = $_POST['date_end'] ?? '';
    
    if (!empty($date_start) && !empty($date_end)) {
        if (strtotime($date_end) <= strtotime($date_start)) {
            $error = 'Дата окончания должна быть позже даты начала';
        } else {
            header('Location: booking-results.php?' . http_build_query($_POST));
            exit;
        }
    }
}
?>

<section class="section">
    <h2>Заявка на бронирование</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="card">
        <form class="form-grid" method="post" action="booking-results.php">
            <?php if (!$isLoggedIn): ?>
                <div>
                    <label>Телефон</label>
                    <input type="text" name="phone" placeholder="+7 (999) 000-00-00" required />
                </div>
            <?php else: ?>
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($login); ?>" />
            <?php endif; ?>
            <div>
                <label>Дата начала</label>
                <input type="date" name="date_start" required />
            </div>
            <div>
                <label>Дата возврата</label>
                <input type="date" name="date_end" required />
            </div>
            <?php if (!$fromCatalog): ?>
                <div>
                    <label>Фильтр по стоимости (до, ₽/сутки)</label>
                    <select name="price_filter">
                        <option value="">Без ограничения</option>
                        <option value="2000">До 2000 ₽</option>
                        <option value="3500">До 3500 ₽</option>
                        <option value="5000">До 5000 ₽</option>
                    </select>
                </div>
            <?php endif; ?>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Показать варианты</button>
                <a class="btn btn-outline" href="cataloge.php">Смотреть весь каталог</a>
            </div>
        </form>
    </div>
</section>

<?php include('foot.php'); ?>

    </main>
    <footer class="site-footer">
        <?php
            $count = 0;
            if (file_exists("count.txt")) {
                $file = file("count.txt");
                $count = (int) implode("", $file);
            }
            $count++;
            $myfile = fopen("count.txt", "w");
            if ($myfile) {
                fputs($myfile, $count);
                fclose($myfile);
            }
        ?>
        <div class="container footer-grid">
            <div>
                <p class="footer-title">LuxRent</p>
                <p>Прокат автомобилей в Санкт-Петербурге</p>
                <p>2024, ООО "Сервис Тур"</p>
                <p>Разработчик: Хон Вероника</p>
            </div>
            <div>
                <p class="footer-title">Контакты</p>
                <p>+7 (812) 970-34-34</p>
                <p>Ежедневно с 08:00 до 21:00 по МСК</p>
                <p>Звонок по России бесплатный</p>
            </div>
            <div>
                <p class="footer-title">Оплата</p>
                <p>Работает система СБП</p>
                <p>К оплате принимаются VISA, MasterCard, МИР</p>
                <p class="footer-counter">Просмотров: <?=$count?></p>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>Спасибо, что выбираете LuxRent.</span>
        </div>
    </footer>
</body>
</html>

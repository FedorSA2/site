<?php
require 'config.php';
$data = read_json(SITE_FILE);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($data['site']['title'] ?? 'Магазин') ?></title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header class="header">
  <div class="container header-inner">
    <a href="index.php" class="logo">
      <span id="logoIcon"></span> <span id="siteTitle"></span>
    </a>
    <nav class="nav">
      <a href="#hero">Главная</a>
      <a href="#about">О нас</a>
      <a href="#advantages">Преимущества</a>
      <a href="#products">Товары</a>
    </nav>
    <a href="admin.php" class="admin-btn">🔒 Админ</a>
  </div>
</header>

<section id="hero" class="hero">
  <div class="container">
    <h1 id="heroTitle"></h1>
    <p id="heroSubtitle"></p>
    <a href="#products" class="btn-primary" id="heroBtn"></a>
  </div>
</section>

<section id="about" class="section">
  <div class="container about-grid">
    <div>
      <h2 id="aboutTitle"></h2>
      <p id="aboutText"></p>
    </div>
    <img id="aboutImage" src="" alt="О нас" />
  </div>
</section>

<section id="advantages" class="section section-alt">
  <div class="container">
    <h2 class="section-title">Наши преимущества</h2>
    <div class="adv-grid" id="advantagesGrid"></div>
  </div>
</section>

<section id="products" class="section">
  <div class="container">
    <h2 class="section-title">Каталог товаров</h2>
    <div class="products-grid" id="productsGrid"></div>
  </div>
</section>

<footer class="footer">
  <div class="container footer-grid">
    <div>
      <h3 id="footerLogo"></h3>
      <p id="footerAddress"></p>
    </div>
    <div>
      <h4>Контакты</h4>
      <p id="footerPhone"></p>
      <p id="footerEmail"></p>
    </div>
    <div>
      <h4>Режим работы</h4>
      <p>Пн–Пт: 10:00–20:00</p>
      <p>Сб–Вс: 11:00–18:00</p>
    </div>
  </div>
  <div class="footer-bottom"><p id="footerCopyright"></p></div>
</footer>

<!-- ===== МОДАЛКА ЗАКАЗА ===== -->
<div id="orderModal" class="modal hidden">
  <div class="modal-content">
    <h2>🛒 Оформление заказа</h2>
    <div id="orderProduct" class="order-product"></div>

    <form id="orderForm">
      <div class="form-group">
        <label>Ваше имя *</label>
        <input type="text" name="name" required />
      </div>
      <div class="form-group">
        <label>Телефон *</label>
        <input type="tel" name="phone" required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" />
      </div>
      <div class="form-group">
        <label>Адрес доставки *</label>
        <textarea name="address" required></textarea>
      </div>
      <div class="form-group">
        <label>Количество</label>
        <input type="number" name="quantity" value="1" min="1" />
      </div>
      <div class="form-group">
        <label>Комментарий</label>
        <textarea name="comment"></textarea>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-primary">✅ Подтвердить заказ</button>
        <button type="button" class="btn-secondary" id="orderCancel">Отмена</button>
      </div>
    </form>
    <p id="orderStatus" class="order-status"></p>
  </div>
</div>

<script>
  // PHP передаёт данные сайта напрямую в JS
  window.SITE_DATA = <?= json_encode($data, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="script.js"></script>
</body>
</html>
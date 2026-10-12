<?php
// ============ НАСТРОЙКИ ============
define('ADMIN_PASSWORD', 'admin123'); // ← смените на свой
define('DATA_DIR', __DIR__ . '');
define('SITE_FILE', DATA_DIR . '/site.json');
define('ORDERS_FILE', DATA_DIR . '/orders.json');

// Автосоздание папки и файлов
if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0777, true);
if (!file_exists(SITE_FILE)) file_put_contents(SITE_FILE, '{}');
if (!file_exists(ORDERS_FILE)) file_put_contents(ORDERS_FILE, '[]');

// Старт сессии для админки
session_start();

// Хелперы
function read_json($file) {
  $content = @file_get_contents($file);
  if (!$content) return [];
  $data = json_decode($content, true);
  return is_array($data) ? $data : [];
}
function write_json($file, $data) {
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
function is_admin() {
  return !empty($_SESSION['is_admin']);
}
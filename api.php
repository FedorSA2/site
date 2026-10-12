<?php
require 'config.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

  // ==================== СОХРАНИТЬ КОНТЕНТ САЙТА ====================
  case 'save_content':
    if (!is_admin()) {
      echo json_encode(['error' => 'Не авторизован']); exit;
    }
    $json = $_POST['data'] ?? '';
    $data = json_decode($json, true);
    if (!is_array($data)) {
      echo json_encode(['error' => 'Неверный формат данных']); exit;
    }
    write_json(SITE_FILE, $data);
    echo json_encode(['success' => true]);
    break;

  // ==================== ПОЛУЧИТЬ КОНТЕНТ ====================
  case 'get_content':
    echo json_encode(read_json(SITE_FILE));
    break;

  // ==================== НОВЫЙ ЗАКАЗ (от клиента) ====================
  case 'new_order':
    $orders = read_json(ORDERS_FILE);
    $order = [
      'id'       => round(microtime(true) * 1000),
      'date'     => date('d.m.Y H:i:s'),
      'product'  => trim($_POST['product'] ?? ''),
      'price'    => trim($_POST['price'] ?? ''),
      'quantity' => (int)($_POST['quantity'] ?? 1),
      'name'     => trim($_POST['name'] ?? ''),
      'phone'    => trim($_POST['phone'] ?? ''),
      'email'    => trim($_POST['email'] ?? ''),
      'address'  => trim($_POST['address'] ?? ''),
      'comment'  => trim($_POST['comment'] ?? ''),
    ];
    if ($order['name'] === '' || $order['phone'] === '' || $order['address'] === '') {
      echo json_encode(['error' => 'Заполните обязательные поля']); exit;
    }
    $orders[] = $order;
    write_json(ORDERS_FILE, $orders);
    echo json_encode(['success' => true, 'id' => $order['id']]);
    break;

  // ==================== ПОЛУЧИТЬ ВСЕ ЗАКАЗЫ (админ) ====================
  case 'get_orders':
    if (!is_admin()) { echo json_encode([]); exit; }
    echo json_encode(read_json(ORDERS_FILE));
    break;

  // ==================== УДАЛИТЬ ЗАКАЗ (админ) ====================
  case 'delete_order':
    if (!is_admin()) { echo json_encode(['error' => 'Не авторизован']); exit; }
    $id = (int)($_POST['id'] ?? 0);
    $orders = read_json(ORDERS_FILE);
    $orders = array_values(array_filter($orders, fn($o) => $o['id'] != $id));
    write_json(ORDERS_FILE, $orders);
    echo json_encode(['success' => true]);
    break;

  // ==================== ОЧИСТИТЬ ВСЕ ЗАКАЗЫ (админ) ====================
  case 'clear_orders':
    if (!is_admin()) { echo json_encode(['error' => 'Не авторизован']); exit; }
    write_json(ORDERS_FILE, []);
    echo json_encode(['success' => true]);
    break;

  // ==================== ВХОД ====================
  case 'login':
    $pass = $_POST['password'] ?? '';
    if ($pass === ADMIN_PASSWORD) {
      $_SESSION['is_admin'] = true;
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => 'Неверный пароль']);
    }
    break;

  // ==================== ВЫХОД ====================
  case 'logout':
    session_destroy();
    echo json_encode(['success' => true]);
    break;

  // ==================== ПРОВЕРКА АВТОРИЗАЦИИ ====================
  case 'check_auth':
    echo json_encode(['auth' => is_admin()]);
    break;

  default:
    echo json_encode(['error' => 'Неизвестное действие']);
}
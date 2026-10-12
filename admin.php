<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Админ-панель</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .orders-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .orders-table th, .orders-table td {
      padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top;
    }
    .orders-table th { background: #f1f5f9; }
    .order-del { background: #dc2626; color: #fff; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; }
    .orders-empty { text-align: center; color: #94a3b8; padding: 40px; }
  </style>
</head>
<body style="background:#f8fafc;">

<!-- ЭКРАН ВХОДА -->
<div id="loginScreen" class="login-screen">
  <h2>🔒 Вход в админку</h2>
  <div class="form-group">
    <input type="password" id="passInput" placeholder="Пароль" />
  </div>
  <button class="btn-primary" id="loginBtn" style="width:100%;">Войти</button>
  <p class="error" id="loginError"></p>
</div>

<!-- ПАНЕЛЬ -->
<div id="adminPanel" class="hidden">
  <div class="admin-header">
    <h2>⚙️ Панель управления</h2>
    <div>
      <button class="btn-primary" id="tabContent">📝 Контент</button>
      <button class="btn-secondary" id="tabOrders">📦 Заказы (<span id="ordersCount">0</span>)</button>
      <button class="btn-secondary" id="logoutBtn">🚪 Выйти</button>
      <a href="index.php" class="btn-secondary">← На сайт</a>
    </div>
  </div>

  <!-- ВКЛАДКА КОНТЕНТА -->
  <div id="contentTab" class="admin-body">
    <div style="text-align:right; margin-bottom:15px;">
      <button class="btn-primary" id="saveBtn">💾 Сохранить</button>
    </div>
    <div id="adminForm"></div>
  </div>

  <!-- ВКЛАДКА ЗАКАЗОВ -->
  <div id="ordersTab" class="admin-body hidden">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap:wrap; gap:10px;">
      <h3 style="margin:0;">Заказы клиентов</h3>
      <div>
        <button class="btn-primary" id="refreshOrders">🔄 Обновить</button>
        <button class="btn-secondary" id="clearOrders" style="background:#dc2626; color:#fff;">🗑️ Очистить все</button>
      </div>
    </div>
    <div id="ordersList"></div>
  </div>

  <div id="saveStatus" class="save-status"></div>
</div>

<script>
let data = null;

// ================== АВТОРИЗАЦИЯ ==================
const loginScreen = document.getElementById('loginScreen');
const adminPanel = document.getElementById('adminPanel');

async function checkAuth() {
  const res = await fetch('api.php?action=check_auth');
  const json = await res.json();
  if (json.auth) {
    loginScreen.classList.add('hidden');
    adminPanel.classList.remove('hidden');
    await loadContent();
    renderForm();
    updateOrdersCount();
  }
}

document.getElementById('loginBtn').onclick = async () => {
  const fd = new FormData();
  fd.append('action', 'login');
  fd.append('password', document.getElementById('passInput').value);
  const res = await fetch('api.php', { method: 'POST', body: fd });
  const json = await res.json();
  if (json.success) {
    checkAuth();
  } else {
    document.getElementById('loginError').textContent = json.error || 'Ошибка';
  }
};

document.getElementById('logoutBtn').onclick = async () => {
  await fetch('api.php?action=logout');
  location.reload();
};

// ================== ЗАГРУЗКА КОНТЕНТА ==================
async function loadContent() {
  const res = await fetch('api.php?action=get_content');
  data = await res.json();
}

// ================== ФОРМА ==================
function renderForm() {
  const f = document.getElementById('adminForm');
  f.innerHTML = `
    <div class="form-section">
      <h3>🏠 Название сайта</h3>
      <div class="form-group"><label>Логотип</label>
        <input value="${esc(data.site?.logo)}" oninput="data.site.logo=this.value" /></div>
      <div class="form-group"><label>Название</label>
        <input value="${esc(data.site?.title)}" oninput="data.site.title=this.value" /></div>
    </div>
    <div class="form-section">
      <h3>🎬 Главный блок</h3>
      <div class="form-group"><label>Заголовок</label>
        <input value="${esc(data.hero?.title)}" oninput="data.hero.title=this.value" /></div>
      <div class="form-group"><label>Подзаголовок</label>
        <textarea oninput="data.hero.subtitle=this.value">${data.hero?.subtitle || ''}</textarea></div>
      <div class="form-group"><label>Текст кнопки</label>
        <input value="${esc(data.hero?.buttonText)}" oninput="data.hero.buttonText=this.value" /></div>
    </div>
    <div class="form-section">
      <h3>ℹ️ О нас</h3>
      <div class="form-group"><label>Заголовок</label>
        <input value="${esc(data.about?.title)}" oninput="data.about.title=this.value" /></div>
      <div class="form-group"><label>Текст</label>
        <textarea rows="5" oninput="data.about.text=this.value">${data.about?.text || ''}</textarea></div>
      <div class="form-group"><label>URL картинки</label>
        <input value="${esc(data.about?.image)}" oninput="data.about.image=this.value" /></div>
    </div>
    <div class="form-section">
      <h3>⭐ Преимущества</h3>
      <div id="advEditor"></div>
      <button class="add-btn" onclick="addAdv()">+ Добавить преимущество</button>
    </div>
    <div class="form-section">
      <h3>📦 Товары</h3>
      <div id="prodEditor"></div>
      <button class="add-btn" onclick="addProd()">+ Добавить товар</button>
    </div>
    <div class="form-section">
      <h3>📞 Подвал</h3>
      <div class="form-group"><label>Адрес</label>
        <input value="${esc(data.footer?.address)}" oninput="data.footer.address=this.value" /></div>
      <div class="form-group"><label>Телефон</label>
        <input value="${esc(data.footer?.phone)}" oninput="data.footer.phone=this.value" /></div>
      <div class="form-group"><label>Email</label>
        <input value="${esc(data.footer?.email)}" oninput="data.footer.email=this.value" /></div>
      <div class="form-group"><label>Копирайт</label>
        <input value="${esc(data.footer?.copyright)}" oninput="data.footer.copyright=this.value" /></div>
    </div>
  `;
  renderAdv();
  renderProd();
}

function renderAdv() {
  const wrap = document.getElementById('advEditor');
  wrap.innerHTML = '';
  data.advantages.forEach((a, i) => {
    wrap.innerHTML += `
      <div class="item-card">
        <button class="remove-btn" onclick="delAdv(${i})">Удалить</button>
        <div class="form-group"><label>Иконка</label>
          <input value="${esc(a.icon)}" oninput="data.advantages[${i}].icon=this.value" /></div>
        <div class="form-group"><label>Заголовок</label>
          <input value="${esc(a.title)}" oninput="data.advantages[${i}].title=this.value" /></div>
        <div class="form-group"><label>Описание</label>
          <textarea oninput="data.advantages[${i}].text=this.value">${a.text}</textarea></div>
      </div>`;
  });
}

function renderProd() {
  const wrap = document.getElementById('prodEditor');
  wrap.innerHTML = '';
  data.products.forEach((p, i) => {
    wrap.innerHTML += `
      <div class="item-card">
        <button class="remove-btn" onclick="delProd(${i})">Удалить</button>
        <div class="form-group"><label>Название</label>
          <input value="${esc(p.name)}" oninput="data.products[${i}].name=this.value" /></div>
        <div class="form-group"><label>Цена</label>
          <input value="${esc(p.price)}" oninput="data.products[${i}].price=this.value" /></div>
        <div class="form-group"><label>URL картинки</label>
          <input value="${esc(p.image)}" oninput="data.products[${i}].image=this.value" /></div>
        <div class="form-group"><label>Описание</label>
          <textarea oninput="data.products[${i}].description=this.value">${p.description}</textarea></div>
      </div>`;
  });
}

function addAdv() {
  data.advantages.push({ icon: '✨', title: 'Новое', text: 'Описание' });
  renderAdv();
}
function delAdv(i) { data.advantages.splice(i, 1); renderAdv(); }
function addProd() {
  data.products.push({ name: 'Новый товар', price: '0 ₽', image: 'https://picsum.photos/400/300', description: 'Описание' });
  renderProd();
}
function delProd(i) { data.products.splice(i, 1); renderProd(); }

// ================== СОХРАНЕНИЕ КОНТЕНТА ==================
document.getElementById('saveBtn').onclick = async () => {
  const fd = new FormData();
  fd.append('action', 'save_content');
  fd.append('data', JSON.stringify(data));
  const res = await fetch('api.php', { method: 'POST', body: fd });
  const json = await res.json();
  if (json.success) showStatus('✅ Сохранено на сервере!');
  else showStatus('❌ ' + (json.error || 'Ошибка'));
};

// ================== ВКЛАДКИ ==================
document.getElementById('tabContent').onclick = () => {
  document.getElementById('contentTab').classList.remove('hidden');
  document.getElementById('ordersTab').classList.add('hidden');
};
document.getElementById('tabOrders').onclick = () => {
  document.getElementById('ordersTab').classList.remove('hidden');
  document.getElementById('contentTab').classList.add('hidden');
  renderOrders();
};

// ================== ЗАКАЗЫ ==================
async function updateOrdersCount() {
  const res = await fetch('api.php?action=get_orders');
  const orders = await res.json();
  document.getElementById('ordersCount').textContent = orders.length;
}

async function renderOrders() {
  const res = await fetch('api.php?action=get_orders');
  const orders = await res.json();
  document.getElementById('ordersCount').textContent = orders.length;

  const wrap = document.getElementById('ordersList');
  if (orders.length === 0) {
    wrap.innerHTML = '<div class="orders-empty">📭 Заказов пока нет</div>';
    return;
  }

  let html = `<table class="orders-table"><thead><tr>
    <th>#</th><th>Дата</th><th>Товар</th><th>Кол-во</th>
    <th>Клиент</th><th>Контакты</th><th>Адрес</th><th>Комм.</th><th></th>
  </tr></thead><tbody>`;

  orders.slice().reverse().forEach((o, idx) => {
    html += `<tr>
      <td>${orders.length - idx}</td>
      <td>${o.date}</td>
      <td><b>${escapeHtml(o.product)}</b><br><small>${o.price}</small></td>
      <td>${o.quantity}</td>
      <td>${escapeHtml(o.name)}</td>
      <td>📞 ${escapeHtml(o.phone)}<br>✉️ ${escapeHtml(o.email || '—')}</td>
      <td>${escapeHtml(o.address)}</td>
      <td><small>${escapeHtml(o.comment || '—')}</small></td>
      <td><button class="order-del" onclick="deleteOrder(${o.id})">✖</button></td>
    </tr>`;
  });
  html += '</tbody></table>';
  wrap.innerHTML = html;
}

window.deleteOrder = async (id) => {
  if (!confirm('Удалить этот заказ?')) return;
  const fd = new FormData();
  fd.append('action', 'delete_order');
  fd.append('id', id);
  await fetch('api.php', { method: 'POST', body: fd });
  renderOrders();
};

document.getElementById('refreshOrders').onclick = renderOrders;

document.getElementById('clearOrders').onclick = async () => {
  if (!confirm('Удалить ВСЕ заказы?')) return;
  const fd = new FormData();
  fd.append('action', 'clear_orders');
  await fetch('api.php', { method: 'POST', body: fd });
  renderOrders();
  showStatus('🗑️ Заказы очищены');
};

// ================== УТИЛИТЫ ==================
function showStatus(text) {
  const s = document.getElementById('saveStatus');
  s.textContent = text;
  s.classList.add('show');
  setTimeout(() => s.classList.remove('show'), 2000);
}
function esc(s) {
  return String(s ?? '').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}
function escapeHtml(s) {
  return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

checkAuth();
</script>
</body>
</html>
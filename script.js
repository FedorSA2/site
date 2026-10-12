const data = window.SITE_DATA || {};
let currentProduct = null;

// ============ ОТРИСОВКА ============
function render() {
  document.getElementById('logoIcon').textContent = data.site?.logo || '⚡';
  document.getElementById('siteTitle').textContent = data.site?.title || '';

  document.getElementById('heroTitle').textContent = data.hero?.title || '';
  document.getElementById('heroSubtitle').textContent = data.hero?.subtitle || '';
  document.getElementById('heroBtn').textContent = data.hero?.buttonText || '';

  document.getElementById('aboutTitle').textContent = data.about?.title || '';
  document.getElementById('aboutText').textContent = data.about?.text || '';
  document.getElementById('aboutImage').src = data.about?.image || '';

  const adv = document.getElementById('advantagesGrid');
  adv.innerHTML = '';
  (data.advantages || []).forEach(a => {
    adv.innerHTML += `
      <div class="adv-card">
        <div class="icon">${a.icon}</div>
        <h3>${esc(a.title)}</h3>
        <p>${esc(a.text)}</p>
      </div>`;
  });

  const prod = document.getElementById('productsGrid');
  prod.innerHTML = '';
  (data.products || []).forEach((p, i) => {
    prod.innerHTML += `
      <div class="product-card">
        <img src="${p.image}" alt="${esc(p.name)}" />
        <div class="product-body">
          <h3>${esc(p.name)}</h3>
          <p class="desc">${esc(p.description)}</p>
          <div class="price-row">
            <div class="price">${esc(p.price)}</div>
            <button class="buy-btn" onclick="openOrderModal(${i})">🛒 Купить</button>
          </div>
        </div>
      </div>`;
  });

  document.getElementById('footerLogo').textContent = `${data.site?.logo || ''} ${data.site?.title || ''}`;
  document.getElementById('footerAddress').textContent = data.footer?.address || '';
  document.getElementById('footerPhone').textContent = '📞 ' + (data.footer?.phone || '');
  document.getElementById('footerEmail').textContent = '✉️ ' + (data.footer?.email || '');
  document.getElementById('footerCopyright').textContent = data.footer?.copyright || '';
}

// ============ ОФОРМЛЕНИЕ ЗАКАЗА ============
const orderModal = document.getElementById('orderModal');
const orderForm = document.getElementById('orderForm');
const orderStatus = document.getElementById('orderStatus');
const orderProduct = document.getElementById('orderProduct');

window.openOrderModal = function (index) {
  currentProduct = data.products[index];
  orderProduct.innerHTML = `
    <img src="${currentProduct.image}" alt="" />
    <div>
      <strong>${esc(currentProduct.name)}</strong>
      <p class="price">${esc(currentProduct.price)}</p>
    </div>`;
  orderForm.reset();
  orderForm.quantity.value = 1;
  orderStatus.textContent = '';
  orderModal.classList.remove('hidden');
};

document.getElementById('orderCancel').onclick = () => {
  orderModal.classList.add('hidden');
  currentProduct = null;
};

orderForm.onsubmit = async (e) => {
  e.preventDefault();
  if (!currentProduct) return;

  const fd = new FormData(orderForm);
  fd.append('action', 'new_order');
  fd.append('product', currentProduct.name);
  fd.append('price', currentProduct.price);

  orderStatus.style.color = '#0ea5e9';
  orderStatus.textContent = '⏳ Отправка...';

  try {
    const res = await fetch('api.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      orderStatus.style.color = '#10b981';
      orderStatus.textContent = '✅ Заказ оформлен! Мы свяжемся с вами.';
      orderForm.reset();
      setTimeout(() => {
        orderModal.classList.add('hidden');
        currentProduct = null;
      }, 2000);
    } else {
      orderStatus.style.color = '#dc2626';
      orderStatus.textContent = '❌ ' + (json.error || 'Ошибка');
    }
  } catch (err) {
    orderStatus.style.color = '#dc2626';
    orderStatus.textContent = '❌ Ошибка сети';
  }
};

function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

render();
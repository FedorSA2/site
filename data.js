// ============================================
//   БАЗА ДАННЫХ САЙТА — редактируется в админке
// ============================================
const SITE_DATA = {
  site: {
    title: "ЭлектронТех",
    logo: "⚡"
  },
  hero: {
    title: "Современная электроника для жизни и работы",
    subtitle: "Смартфоны, ноутбуки, гаджеты и комплектующие — с гарантией и быстрой доставкой по России.",
    buttonText: "Смотреть каталог"
  },
  about: {
    title: "О нас",
    text: "«ЭлектронТех» — команда инженеров, которая с 2012 года помогает выбирать технику без переплат. Работаем напрямую с производителями и даём честные консультации.",
    image: "https://media.istockphoto.com/id/1346125184/ru/%D1%84%D0%BE%D1%82%D0%BE/%D0%B3%D1%80%D1%83%D0%BF%D0%BF%D0%B0-%D1%83%D1%81%D0%BF%D0%B5%D1%88%D0%BD%D0%BE%D0%B9-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE%D0%BD%D0%B0%D1%86%D0%B8%D0%BE%D0%BD%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B9-%D0%B1%D0%B8%D0%B7%D0%BD%D0%B5%D1%81-%D0%BA%D0%BE%D0%BC%D0%B0%D0%BD%D0%B4%D1%8B.jpg?s=612x612&w=0&k=20&c=Hb8WgQU3bbJvKbiPueZBQNijO4CUQHnN9lO4GCBm0B8="
  },
  advantages: [
    { icon: "🚚", title: "Быстрая доставка", text: "Отправляем заказы в день оформления." },
    { icon: "🛡️", title: "Гарантия", text: "Официальная гарантия до 36 месяцев." },
    { icon: "💳", title: "Удобная оплата", text: "Карта, наличные, рассрочка 0%." },
    { icon: "🔧", title: "Свой сервис", text: "Ремонт в собственном сервис-центре." }
  ],
  products: [
    { name: "Смартфон Galaxy X Pro", price: "59 990 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZoS7kaUYPqGPysLwNzeIUQN-6jEdkhxVTWPI2bXOXTA&s=10", description: "6.7\" AMOLED, 256 ГБ, камера 108 Мп." },
    { name: "Ноутбук UltraBook 14", price: "84 500 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMPgE3rCwEkJVlDdo4wgNxV-2jv0BEb-DXUfLowJ-ZA&s=10", description: "Intel Core i7, 16 ГБ RAM, SSD 1 ТБ." },
    { name: "Наушники AirPods Max", price: "32 990 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRy-sIcIynU5cPmvmny8QUuM6MdidpzpObT9A2XZXe16A&s=10", description: "Шумоподавление, 30 часов работы." },
    { name: "Часы Watch Series 9", price: "27 400 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxkmbvrQ5NUa_wd1LS4MjEek6t94YIexEO8zIGJRlYSg&s=10", description: "AMOLED, ЭКГ, защита 5 ATM." },
    { name: "Планшет Tab S9 11\"", price: "45 700 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8qE-2TpikytuVuR-taF5sEmD1xx96W8UJEnZEi8fDfA&s=10", description: "2.5K 120 Гц, стилус в комплекте." },
    { name: "Колонка Boom 3", price: "9 890 ₽", image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3w5c_Qg00zyLUiDCxWnQePEVgH9CS0hbG2amJIVMU1g&s=10", description: "40 Вт, IPX7, 24 часа работы." }
  ],
  footer: {
    address: "г. Москва, ул. Сикссевенская, д. 67, офис 6767",
    phone: "+7 983 123-45-67",
    email: "info@electrotech.ru",
    copyright: "© 2026 ЭлектронТех. Все права защищены."
  }
};

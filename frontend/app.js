const inventoryData = [
  {
    batch: 'MILK-B001',
    product: 'Milk 1L',
    category: 'Dairy',
    stock: 50,
    expiry: '2026-06-10',
    supplier: 'Fresh Farm Suppliers',
    reorderLevel: 10,
    status: 'in-stock',
  },
  {
    batch: 'BREAD-B001',
    product: 'Whole Wheat Bread',
    category: 'Bakery',
    stock: 8,
    expiry: '2026-06-07',
    supplier: 'Daily Needs Trading',
    reorderLevel: 15,
    status: 'near-expiry',
  },
  {
    batch: 'VEG-B001',
    product: 'Tomato',
    category: 'Vegetables',
    stock: 38,
    expiry: '2026-06-09',
    supplier: 'Fresh Farm Suppliers',
    reorderLevel: 20,
    status: 'in-stock',
  },
  {
    batch: 'YOG-B002',
    product: 'Plain Yogurt',
    category: 'Dairy',
    stock: 4,
    expiry: '2026-06-05',
    supplier: 'Fresh Farm Suppliers',
    reorderLevel: 12,
    status: 'expired',
  },
  {
    batch: 'RICE-B004',
    product: 'Basmati Rice',
    category: 'Grains',
    stock: 120,
    expiry: '2027-02-15',
    supplier: 'Daily Needs Trading',
    reorderLevel: 40,
    status: 'in-stock',
  },
  {
    batch: 'APPLE-B003',
    product: 'Apple',
    category: 'Fruits',
    stock: 13,
    expiry: '2026-06-08',
    supplier: 'Fresh Farm Suppliers',
    reorderLevel: 18,
    status: 'low-stock',
  },
];

const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const statusFilter = document.getElementById('statusFilter');
const sortSelect = document.getElementById('sortSelect');
const inventoryTable = document.getElementById('inventoryTable');
const consumeList = document.getElementById('consumeList');
const resultCount = document.getElementById('resultCount');
const totalProducts = document.getElementById('totalProducts');
const nearExpiry = document.getElementById('nearExpiry');
const lowStock = document.getElementById('lowStock');
const wasteDonation = document.getElementById('wasteDonation');

const today = new Date('2026-06-05T00:00:00');

function daysUntil(dateValue) {
  const target = new Date(dateValue + 'T00:00:00');
  const diff = target.getTime() - today.getTime();
  return Math.ceil(diff / (1000 * 60 * 60 * 24));
}

function getPriority(item) {
  const daysLeft = daysUntil(item.expiry);
  if (daysLeft <= 0) {
    return { label: 'Expired', className: 'high' };
  }
  if (daysLeft <= 3) {
    return { label: 'High priority', className: 'high' };
  }
  if (daysLeft <= 7) {
    return { label: 'Medium priority', className: 'medium' };
  }
  return { label: 'Normal', className: 'normal' };
}

function getStatus(item) {
  const daysLeft = daysUntil(item.expiry);
  if (daysLeft <= 0) return 'expired';
  if (daysLeft <= 3) return 'near-expiry';
  if (item.stock <= item.reorderLevel) return 'low-stock';
  return 'in-stock';
}

function formatDate(dateValue) {
  return new Date(dateValue + 'T00:00:00').toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
}

function buildCategoryOptions() {
  const categories = ['all', ...new Set(inventoryData.map((item) => item.category))];
  categoryFilter.innerHTML = categories
    .map((category) => `<option value="${category}">${category === 'all' ? 'All Categories' : category}</option>`)
    .join('');
}

function render() {
  const search = searchInput.value.trim().toLowerCase();
  const category = categoryFilter.value;
  const status = statusFilter.value;
  const sortBy = sortSelect.value;

  let items = inventoryData.map((item) => ({
    ...item,
    computedStatus: getStatus(item),
    priority: getPriority(item),
    daysLeft: daysUntil(item.expiry),
  }));

  if (search) {
    items = items.filter((item) =>
      [item.batch, item.product, item.category, item.supplier].some((field) =>
        field.toLowerCase().includes(search),
      ),
    );
  }

  if (category !== 'all') {
    items = items.filter((item) => item.category === category);
  }

  if (status !== 'all') {
    items = items.filter((item) => item.computedStatus === status);
  }

  items.sort((a, b) => {
    if (sortBy === 'expiry') return a.daysLeft - b.daysLeft;
    if (sortBy === 'stock') return b.stock - a.stock;
    return a.product.localeCompare(b.product);
  });

  inventoryTable.innerHTML = items
    .map(
      (item) => `
        <tr>
          <td>${item.batch}</td>
          <td>${item.product}</td>
          <td>${item.category}</td>
          <td>${item.stock}</td>
          <td>${formatDate(item.expiry)}<br><small>${item.daysLeft} days left</small></td>
          <td>${item.supplier}</td>
          <td><span class="status-chip status-${item.computedStatus}">${item.computedStatus.replace('-', ' ')}</span></td>
        </tr>
      `,
    )
    .join('');

  consumeList.innerHTML = [...items]
    .sort((a, b) => a.daysLeft - b.daysLeft)
    .slice(0, 4)
    .map(
      (item) => `
        <div class="alert-item">
          <span class="priority-chip ${item.priority.className}">${item.priority.label}</span>
          <strong>${item.product}</strong>
          <p>${item.batch} • ${item.daysLeft <= 0 ? 'Expired' : `${item.daysLeft} days left`} • ${item.stock} units</p>
        </div>
      `,
    )
    .join('');

  totalProducts.textContent = inventoryData.length;
  nearExpiry.textContent = inventoryData.filter((item) => {
    const daysLeft = daysUntil(item.expiry);
    return daysLeft > 0 && daysLeft <= 7;
  }).length;
  lowStock.textContent = inventoryData.filter((item) => item.stock <= item.reorderLevel).length;
  wasteDonation.textContent = 10;
  resultCount.textContent = `${items.length} items`;
}

buildCategoryOptions();
render();

[searchInput, categoryFilter, statusFilter, sortSelect].forEach((control) => {
  control.addEventListener('input', render);
  control.addEventListener('change', render);
});

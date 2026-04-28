const form = document.getElementById('order-form');
const itemsList = document.getElementById('items-list');
const ordersContainer = document.getElementById('orders');
const discountField = document.getElementById('discount');
const apiUrl = '/pedidos';

function createItemRow() {
  const row = document.createElement('div');
  row.className = 'item-row';

  row.innerHTML = `
    <input type="text" placeholder="Nome do produto" class="product-name" required />
    <input type="number" placeholder="Preço" class="product-price" min="0.01" step="0.01" required />
    <input type="number" placeholder="Quantidade" class="product-quantity" min="1" step="1" value="1" required />
    <button type="button" class="remove-item">X</button>
  `;

  row.querySelector('.remove-item').addEventListener('click', () => row.remove());
  itemsList.appendChild(row);
}

function readItems() {
  return Array.from(document.querySelectorAll('.item-row')).map(row => ({
    produtoId: 0,
    nome: row.querySelector('.product-name').value.trim(),
    preco: parseFloat(row.querySelector('.product-price').value),
    quantidade: parseInt(row.querySelector('.product-quantity').value, 10),
  }));
}

async function fetchPedidos() {
  const response = await fetch(apiUrl);
  const result = await response.json();
  renderOrders(result.data || []);
}

function renderOrders(pedidos) {
  ordersContainer.innerHTML = '';

  if (pedidos.length === 0) {
    ordersContainer.textContent = 'Nenhum pedido registrado.';
    return;
  }

  pedidos.forEach(pedido => {
    const card = document.createElement('div');
    card.className = 'order-card';

    card.innerHTML = `
      <h3>Pedido #${pedido.id}</h3>
      <pre>${pedido.itens.map(item => `${item.quantidade}x ${item.produto.nome} - R$ ${item.subtotal.toFixed(2)}`).join('\n')}</pre>
      <p><strong>Desconto:</strong> ${pedido.discountType}</p>
      <p><strong>Total:</strong> R$ ${pedido.total.toFixed(2)}</p>
    `;

    const actions = document.createElement('div');
    actions.className = 'order-actions';

    const whatsappLink = document.createElement('a');
    whatsappLink.href = createWhatsAppUrl(pedido);
    whatsappLink.target = '_blank';
    whatsappLink.textContent = 'Enviar WhatsApp';

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Excluir';
    deleteButton.addEventListener('click', () => deletePedido(pedido.id));

    actions.appendChild(whatsappLink);
    actions.appendChild(deleteButton);
    card.appendChild(actions);
    ordersContainer.appendChild(card);
  });
}

function createWhatsAppUrl(pedido) {
  const base = 'https://wa.me/?text=';
  const lines = [
    `Pedido #${pedido.id}`,
    ...pedido.itens.map(item => `${item.quantidade}x ${item.produto.nome} - R$ ${item.subtotal.toFixed(2)}`),
    `Desconto: ${pedido.discountType}`,
    `Total: R$ ${pedido.total.toFixed(2)}`,
  ];

  return base + encodeURIComponent(lines.join('\n'));
}

async function submitPedido(event) {
  event.preventDefault();

  const itens = readItems();
  const payload = {
    itens,
    discountType: discountField.value,
  };

  const response = await fetch(apiUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });

  const result = await response.json();

  if (result.success) {
    form.reset();
    itemsList.innerHTML = '';
    createItemRow();
    fetchPedidos();
    alert('Pedido criado com sucesso!');
    return;
  }

  alert(result.error || 'Erro ao criar pedido');
}

async function deletePedido(id) {
  if (!confirm('Confirma exclusão do pedido?')) {
    return;
  }

  await fetch(apiUrl, {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id }),
  });

  fetchPedidos();
}

document.getElementById('add-item').addEventListener('click', createItemRow);
form.addEventListener('submit', submitPedido);
createItemRow();
fetchPedidos();

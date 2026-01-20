@extends('layouts.app')

@section('title', 'Punto de Venta - Productos')
@section('page-title', 'POS - Venta de Productos y Servicios')

@section('content')
<div class="fade-in">
    <!-- Cash Register Info -->
    <div class="mb-6 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Caja Activa</p>
                    <p class="font-semibold text-primary-dark">{{ $activeCashRegister->code }}</p>
                    <p class="text-xs text-gray-500">Abierta: {{ $activeCashRegister->opened_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Fondo Inicial</p>
                <p class="text-2xl font-display font-bold text-green-600">{{ $activeCashRegister->formatted_opening_amount }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT SIDE - Products -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Search -->
                <div class="card-premium">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-display font-semibold text-primary-dark">
                            Buscar Producto/Servicio
                        </h2>
                    </div>

                    <div class="relative">
                        <input type="text"
                               id="product_search"
                               class="input-elegant text-lg"
                               placeholder="Escribe el nombre del producto o servicio..."
                               autocomplete="off"
                               autofocus>

                        <div id="product-results" class="absolute z-50 w-full mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-2xl hidden max-h-96 overflow-y-auto">
                            <!-- Results will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Quick Product Selection -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Productos Disponibles</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-96 overflow-y-auto">
                        @foreach($products as $product)
                        <button type="button"
                                onclick="addProductToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->track_inventory ? 1 : 0 }}, {{ $product->stock }})"
                                class="p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition text-left">
                            <p class="font-semibold text-sm">{{ $product->name }}</p>
                            <p class="text-lg font-bold text-purple-600">${{ number_format($product->price, 2) }}</p>
                            @if($product->track_inventory)
                            <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE - Cart and Payment -->
            <div class="lg:col-span-1">
                <div class="card-premium sticky top-6">
                    <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">Carrito de Compra</h3>

                    <!-- Cart Items -->
                    <div id="cart-items" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                        <p class="text-center text-gray-400 py-8">Carrito vacío</p>
                    </div>

                    <div class="border-t-2 pt-4 space-y-3">
                        <!-- Customer Info (Optional) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                            <input type="text"
                                   name="customer_name"
                                   class="input-elegant text-sm"
                                   placeholder="Nombre del cliente">
                            <input type="text"
                                   name="customer_document"
                                   class="input-elegant text-sm"
                                   placeholder="Cédula/Documento">
                        </div>

                        <!-- Subtotal -->
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span class="font-semibold" id="subtotal-display">$0.00</span>
                        </div>

                        <!-- Discount -->
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-700">Descuento:</label>
                            <input type="number"
                                   name="discount"
                                   id="discount-input"
                                   class="input-elegant text-sm flex-1"
                                   value="0"
                                   min="0"
                                   step="0.01"
                                   onchange="updateTotal()">
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between text-xl font-bold text-primary-dark">
                            <span>Total:</span>
                            <span id="total-display">$0.00</span>
                        </div>

                        <input type="hidden" name="total" id="total-input" value="0">
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="border-t-2 pt-4 mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <button type="button"
                                    id="toggle-multiple-methods-btn"
                                    onclick="toggleMultiplePaymentMethods()"
                                    class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                                + Múltiples Métodos
                            </button>
                        </div>

                        <!-- Single Payment Method -->
                        <div id="single-payment-section">
                            <select name="payment_method" id="payment-method-select" class="input-elegant">
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                <option value="tarjeta_debito">Tarjeta de Débito</option>
                                <option value="yappy">Yappy</option>
                                <option value="otro">Otro</option>
                            </select>
                            <input type="text"
                                   name="reference_number"
                                   id="reference-number-input"
                                   class="input-elegant text-sm mt-2"
                                   placeholder="Número de referencia (opcional)">
                        </div>

                        <!-- Multiple Payment Methods -->
                        <div id="multiple-payment-section" class="hidden space-y-2">
                            <div id="payment-methods-container"></div>
                            <button type="button"
                                    onclick="addPaymentMethod()"
                                    class="btn-secondary w-full text-sm">
                                + Agregar Método
                            </button>
                            <div id="payment-validation" class="text-sm mt-2"></div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="border-t-2 pt-4 mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notas (Opcional)</label>
                        <textarea name="notes"
                                  class="input-elegant text-sm"
                                  rows="2"
                                  placeholder="Notas adicionales sobre la venta"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            id="checkout-btn"
                            class="btn-primary w-full mt-6 py-4 text-lg"
                            disabled>
                        <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Procesar Venta
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let cart = [];
let useMultipleMethods = false;
let paymentMethodsCount = 0;

// Add product to cart
function addProductToCart(id, name, price, trackInventory, stock) {
    // Check if product already in cart
    const existingItem = cart.find(item => item.product_id === id);

    if (existingItem) {
        if (trackInventory && existingItem.quantity >= stock) {
            alert('No hay suficiente stock disponible');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            product_id: id,
            name: name,
            quantity: 1,
            unit_price: price,
            discount: 0,
            track_inventory: trackInventory,
            stock: stock
        });
    }

    renderCart();
    updateTotal();
}

// Remove item from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    updateTotal();
}

// Update item quantity
function updateQuantity(index, quantity) {
    const item = cart[index];
    const newQty = parseInt(quantity);

    if (newQty < 1) {
        removeFromCart(index);
        return;
    }

    if (item.track_inventory && newQty > item.stock) {
        alert('Cantidad excede el stock disponible');
        return;
    }

    item.quantity = newQty;
    renderCart();
    updateTotal();
}

// Update item discount
function updateItemDiscount(index, discount) {
    cart[index].discount = parseFloat(discount) || 0;
    updateTotal();
}

// Render cart
function renderCart() {
    const container = document.getElementById('cart-items');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-400 py-8">Carrito vacío</p>';
        checkoutBtn.disabled = true;
        return;
    }

    checkoutBtn.disabled = false;

    let html = '';
    cart.forEach((item, index) => {
        html += `
            <div class="p-3 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">${item.name}</p>
                        <p class="text-xs text-gray-500">$${item.unit_price.toFixed(2)} c/u</p>
                    </div>
                    <button type="button"
                            onclick="removeFromCart(${index})"
                            class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="number"
                           value="${item.quantity}"
                           min="1"
                           ${item.track_inventory ? `max="${item.stock}"` : ''}
                           onchange="updateQuantity(${index}, this.value)"
                           class="input-elegant text-sm w-20">
                    <input type="number"
                           value="${item.discount}"
                           min="0"
                           step="0.01"
                           placeholder="Desc."
                           onchange="updateItemDiscount(${index}, this.value)"
                           class="input-elegant text-sm w-24">
                    <span class="text-sm font-semibold">$${((item.quantity * item.unit_price) - item.discount).toFixed(2)}</span>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Update totals
function updateTotal() {
    let subtotal = 0;

    cart.forEach(item => {
        subtotal += (item.quantity * item.unit_price) - item.discount;
    });

    const discount = parseFloat(document.getElementById('discount-input').value) || 0;
    const total = subtotal - discount;

    document.getElementById('subtotal-display').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total-display').textContent = '$' + total.toFixed(2);
    document.getElementById('total-input').value = total.toFixed(2);

    if (useMultipleMethods) {
        validateMultiplePaymentMethods();
    }
}

// Toggle multiple payment methods
function toggleMultiplePaymentMethods() {
    useMultipleMethods = !useMultipleMethods;

    const singleSection = document.getElementById('single-payment-section');
    const multipleSection = document.getElementById('multiple-payment-section');
    const toggleBtn = document.getElementById('toggle-multiple-methods-btn');

    if (useMultipleMethods) {
        singleSection.classList.add('hidden');
        multipleSection.classList.remove('hidden');
        toggleBtn.textContent = '- Un Solo Método';
        document.getElementById('payment-method-select').disabled = true;
        document.getElementById('reference-number-input').disabled = true;

        if (paymentMethodsCount === 0) {
            addPaymentMethod();
            addPaymentMethod();
        }
    } else {
        singleSection.classList.remove('hidden');
        multipleSection.classList.add('hidden');
        toggleBtn.textContent = '+ Múltiples Métodos';
        document.getElementById('payment-method-select').disabled = false;
        document.getElementById('reference-number-input').disabled = false;
    }
}

// Add payment method
function addPaymentMethod() {
    const container = document.getElementById('payment-methods-container');
    const index = paymentMethodsCount++;

    const html = `
        <div class="p-3 bg-gray-50 rounded-lg" id="payment-method-${index}">
            <div class="flex justify-between items-start mb-2">
                <label class="text-xs font-medium text-gray-700">Método ${index + 1}</label>
                <button type="button"
                        onclick="removePaymentMethod(${index})"
                        class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <select name="payment_methods[${index}][method]"
                    class="input-elegant text-sm mb-2"
                    onchange="validateMultiplePaymentMethods()">
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                <option value="tarjeta_debito">Tarjeta de Débito</option>
                <option value="yappy">Yappy</option>
                <option value="otro">Otro</option>
            </select>
            <input type="number"
                   name="payment_methods[${index}][amount]"
                   class="input-elegant text-sm mb-2"
                   placeholder="Monto"
                   min="0.01"
                   step="0.01"
                   oninput="validateMultiplePaymentMethods()">
            <input type="text"
                   name="payment_methods[${index}][reference_number]"
                   class="input-elegant text-sm"
                   placeholder="Referencia (opcional)">
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Remove payment method
function removePaymentMethod(index) {
    const element = document.getElementById(`payment-method-${index}`);
    if (element) {
        element.remove();
        validateMultiplePaymentMethods();
    }
}

// Validate multiple payment methods
function validateMultiplePaymentMethods() {
    const total = parseFloat(document.getElementById('total-input').value) || 0;
    const methodInputs = document.querySelectorAll('input[name^="payment_methods"][name$="[amount]"]');

    let sum = 0;
    methodInputs.forEach(input => {
        if (input.offsetParent !== null) { // Check if visible
            sum += parseFloat(input.value) || 0;
        }
    });

    const validationDiv = document.getElementById('payment-validation');
    const difference = Math.abs(total - sum);

    if (difference < 0.01 && sum > 0) {
        validationDiv.innerHTML = '<span class="text-green-600 font-semibold">✓ Suma correcta</span>';
        return true;
    } else if (sum === 0) {
        validationDiv.innerHTML = '<span class="text-gray-500">Ingrese los montos</span>';
        return false;
    } else {
        const remaining = total - sum;
        validationDiv.innerHTML = `<span class="text-red-600 font-semibold">Falta: $${remaining.toFixed(2)}</span>`;
        return false;
    }
}

// Form submission
document.getElementById('sale-form').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('El carrito está vacío');
        return;
    }

    if (useMultipleMethods && !validateMultiplePaymentMethods()) {
        e.preventDefault();
        alert('La suma de los métodos de pago debe ser igual al total');
        return;
    }

    // Add cart items to form
    const form = this;
    cart.forEach((item, index) => {
        const fields = [
            ['product_id', item.product_id],
            ['quantity', item.quantity],
            ['unit_price', item.unit_price],
            ['discount', item.discount]
        ];

        fields.forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${name}]`;
            input.value = value;
            form.appendChild(input);
        });
    });
});

// Product search
let searchTimeout;
document.getElementById('product_search').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();

    if (query.length < 2) {
        document.getElementById('product-results').classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(() => {
        // Filter products by name
        const products = @json($products);
        const filtered = products.filter(p =>
            p.name.toLowerCase().includes(query.toLowerCase())
        );

        const resultsDiv = document.getElementById('product-results');

        if (filtered.length === 0) {
            resultsDiv.innerHTML = '<div class="p-4 text-gray-500 text-center">No se encontraron productos</div>';
            resultsDiv.classList.remove('hidden');
            return;
        }

        let html = '';
        filtered.forEach(product => {
            html += `
                <div class="p-4 hover:bg-gray-50 cursor-pointer border-b last:border-b-0"
                     onclick="addProductToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, ${product.track_inventory ? 1 : 0}, ${product.stock}); document.getElementById('product-results').classList.add('hidden'); document.getElementById('product_search').value = '';">
                    <p class="font-semibold">${product.name}</p>
                    <p class="text-sm text-gray-600">Precio: $${product.price.toFixed(2)}</p>
                    ${product.track_inventory ? `<p class="text-xs text-gray-500">Stock: ${product.stock}</p>` : ''}
                </div>
            `;
        });

        resultsDiv.innerHTML = html;
        resultsDiv.classList.remove('hidden');
    }, 300);
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#product_search') && !e.target.closest('#product-results')) {
        document.getElementById('product-results').classList.add('hidden');
    }
});
</script>
@endpush
@endsection

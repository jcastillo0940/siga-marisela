<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - Academia Auténtica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100vh; overflow: hidden; }
        .modal { display: none; position: fixed; z-index: 999; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); }
        .modal.active { display: flex; align-items: center; justify-content: center; animation: fadeIn 0.2s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .slide-up { animation: slideUp 0.3s ease-out; }
        .tab-button { transition: all 0.2s; }
        .tab-button.active { background: #DC2626; color: white; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #DC2626; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #991b1b; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- LEFT PANEL -->
        <div class="w-2/3 flex flex-col bg-white">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-900 to-red-700 text-white p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Caja Activa</p>
                        <p class="font-bold text-lg">{{ $activeCashRegister->code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm opacity-90">Fondo Inicial</p>
                        <p class="font-bold text-xl">{{ $activeCashRegister->formatted_opening_amount }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg font-semibold transition">
                        ← Salir
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="p-4 border-b bg-gray-50">
                <input type="text" id="universal-search" placeholder="🔍 Buscar productos, estudiantes..." 
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-xl focus:border-red-600 focus:ring-2 focus:ring-red-200 focus:outline-none transition">
                <div id="search-results" class="absolute mt-2 w-1/2 bg-white rounded-xl shadow-2xl hidden max-h-96 overflow-y-auto z-50 border-2 border-gray-200"></div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b bg-gray-50 px-4">
                <button class="tab-button active px-6 py-3 font-semibold rounded-t-lg" onclick="switchTab('products')">📦 Productos</button>
                <button class="tab-button px-6 py-3 font-semibold rounded-t-lg" onclick="switchTab('payments')">💳 Pagos</button>
            </div>

            <!-- Products Tab -->
            <div id="products-tab" class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-4 gap-4">
                    @foreach($products as $product)
                    <button onclick='addToCart({type:"product",id:{{ $product->id }},name:"{{ addslashes($product->name) }}",price:{{ $product->price }},track_inventory:{{ $product->track_inventory ? "true" : "false" }},stock:{{ $product->stock ?? 0 }}})' 
                            class="p-6 bg-gradient-to-br from-white to-gray-50 rounded-2xl border-2 border-gray-200 hover:border-red-500 hover:shadow-xl transition-all text-left">
                        <div class="text-4xl mb-3">📦</div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                        <p class="text-2xl font-bold text-red-600">${{ number_format($product->price, 2) }}</p>
                        @if($product->track_inventory)
                        <p class="text-xs text-gray-500 mt-1">Stock: {{ $product->stock }}</p>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Payments Tab -->
            <div id="payments-tab" class="flex-1 overflow-y-auto p-4 hidden">
                @if($student && $enrollments->isNotEmpty())
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">{{ $student->full_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $student->identification }}</p>
                                </div>
                            </div>
                            <a href="{{ route('pos.unified') }}" class="text-red-700 hover:text-red-900 font-semibold">✕ Cambiar</a>
                        </div>
                    </div>

                    @foreach($enrollments as $enrollment)
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                        <div class="flex justify-between mb-3 pb-3 border-b">
                            <h4 class="font-bold text-lg">{{ $enrollment->courseOffering->course->name }}</h4>
                            <p class="text-2xl font-bold text-red-700">${{ number_format($enrollment->paymentPlan->balance, 2) }}</p>
                        </div>
                        @foreach($enrollment->paymentPlan->schedules as $schedule)
                        <div class="p-3 border-2 rounded-lg cursor-pointer hover:border-green-500 transition mb-2 {{ $schedule->is_overdue ? 'bg-red-50 border-red-300' : 'bg-blue-50 border-blue-200' }}"
                             onclick='addToCart({type:"payment",enrollment_id:{{ $enrollment->id }},schedule_id:{{ $schedule->id }},installment:{{ $schedule->installment_number }},course_name:"{{ addslashes($enrollment->courseOffering->course->name) }}",amount:{{ $schedule->balance }},due_date:"{{ $schedule->due_date->format('d/m/Y') }}",is_overdue:{{ $schedule->is_overdue ? "true" : "false" }}})'>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $schedule->is_overdue ? 'bg-red-600 text-white' : 'bg-blue-600 text-white' }}">Cuota #{{ $schedule->installment_number }}</span>
                                    @if($schedule->is_overdue)<span class="ml-2 text-xs bg-red-600 text-white px-2 py-1 rounded font-bold">⚠ VENCIDA</span>@endif
                                    <p class="text-sm text-gray-600 mt-1">Vence: {{ $schedule->due_date->format('d/m/Y') }}</p>
                                </div>
                                <p class="text-2xl font-bold">${{ number_format($schedule->balance, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">👥</div>
                    <p class="text-xl text-gray-600 font-semibold">Busca un estudiante</p>
                </div>
                @endif
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="w-1/3 bg-gray-900 text-white flex flex-col">
            <div class="p-6 bg-red-600">
                <h2 class="text-2xl font-bold">🛒 Carrito</h2>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center text-gray-500 py-20">
                    <div class="text-6xl mb-4">🛒</div>
                    <p>Carrito vacío</p>
                </div>
            </div>

            <div class="border-t border-gray-700 p-6 space-y-4">
                <div class="flex justify-between text-lg">
                    <span>Subtotal:</span>
                    <span id="subtotal" class="font-bold">$0.00</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span>Descuento:</span>
                    <input type="number" id="discount" class="w-24 text-right font-bold bg-gray-800 border-2 border-gray-700 rounded px-2 py-1 text-white" value="0" min="0" step="0.01" oninput="updateTotals()">
                </div>
                <div class="flex justify-between text-3xl font-bold border-t border-gray-700 pt-4">
                    <span>TOTAL:</span>
                    <span id="total" class="text-red-400">$0.00</span>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button onclick="clearCart()" class="px-6 py-4 bg-gray-700 hover:bg-gray-600 rounded-xl font-semibold transition">Limpiar</button>
                    <button onclick="openPaymentModal()" id="checkout-btn" class="px-6 py-4 bg-red-600 hover:bg-red-700 rounded-xl font-semibold text-lg transition disabled:opacity-50" disabled>Cobrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="bg-white rounded-3xl shadow-2xl w-2/3 max-h-[90vh] overflow-y-auto slide-up">
            <div class="p-8 bg-red-600 text-white flex justify-between items-center">
                <h3 class="text-3xl font-bold">💳 Procesar Pago</h3>
                <button onclick="closePaymentModal()" class="text-4xl hover:bg-white/20 w-12 h-12 rounded-full">×</button>
            </div>
            <div class="p-8">
                <div class="bg-gray-50 p-6 rounded-2xl mb-6">
                    <div class="flex justify-between text-2xl font-bold">
                        <span>Total a Pagar:</span>
                        <span id="modalTotal" class="text-red-600">$0.00</span>
                    </div>
                </div>

                <div id="paymentMethods"></div>

                <div class="flex gap-4 mt-8">
                    <button onclick="closePaymentModal()" class="flex-1 px-6 py-4 bg-gray-200 hover:bg-gray-300 rounded-xl font-semibold text-lg transition">Cancelar</button>
                    <button onclick="processPayment()" class="flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-lg transition">Confirmar Pago</button>
                </div>
            </div>
        </div>
    </div>

    <form id="checkout-form" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="cart_data" id="cart-data">
        <input type="hidden" name="payment_methods" id="payment-methods-data">
        <input type="hidden" name="discount" id="discount-input">
        <input type="hidden" name="total" id="total-input">
    </form>

    <script>
        let cart = [];
        let activeTab = 'products';
        let paymentMethodsData = [];

        function switchTab(tab) {
            activeTab = tab;
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('products-tab').classList.toggle('hidden', tab !== 'products');
            document.getElementById('payments-tab').classList.toggle('hidden', tab !== 'payments');
        }

        function addToCart(item) {
            const existing = cart.findIndex(i => {
                if (i.type === 'product' && item.type === 'product') return i.id === item.id;
                if (i.type === 'payment' && item.type === 'payment') return i.schedule_id === item.schedule_id;
                return false;
            });

            if (existing >= 0) {
                if (item.type === 'product') {
                    if (item.track_inventory && cart[existing].quantity >= item.stock) {
                        alert('Stock insuficiente');
                        return;
                    }
                    cart[existing].quantity++;
                } else {
                    alert('Esta cuota ya está en el carrito');
                    return;
                }
            } else {
                if (item.type === 'product') item.quantity = 1;
                cart.push(item);
            }
            renderCart();
            updateTotals();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
            updateTotals();
        }

        function updateQuantity(index, qty) {
            const item = cart[index];
            const newQty = parseInt(qty);
            if (newQty < 1) { removeFromCart(index); return; }
            if (item.track_inventory && newQty > item.stock) { alert('Supera el stock'); return; }
            item.quantity = newQty;
            updateTotals();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const btn = document.getElementById('checkout-btn');

            if (cart.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-20"><div class="text-6xl mb-4">🛒</div><p>Carrito vacío</p></div>';
                btn.disabled = true;
                return;
            }

            btn.disabled = false;
            container.innerHTML = cart.map((item, i) => {
                if (item.type === 'product') {
                    return `<div class="bg-gray-800 rounded-xl p-4">
                        <div class="flex justify-between mb-3">
                            <div class="flex-1"><h4 class="font-semibold text-lg">📦 ${item.name}</h4><p class="text-red-400 font-bold">$${item.price.toFixed(2)}</p></div>
                            <button onclick="removeFromCart(${i})" class="text-red-400 text-2xl">×</button>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="updateQuantity(${i}, ${item.quantity - 1})" class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-lg font-bold text-xl">−</button>
                            <span class="text-2xl font-bold w-12 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity(${i}, ${item.quantity + 1})" class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg font-bold text-xl">+</button>
                            <span class="ml-auto text-xl font-bold">$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    </div>`;
                } else {
                    return `<div class="bg-gray-800 rounded-xl p-4 ${item.is_overdue ? 'border-2 border-red-500' : ''}">
                        <div class="flex justify-between mb-2">
                            <div class="flex-1"><h4 class="font-semibold text-lg">💳 ${item.course_name}</h4><p class="text-sm text-gray-400">Cuota #${item.installment} - ${item.due_date}</p>${item.is_overdue ? '<span class="text-xs bg-red-600 px-2 py-1 rounded">⚠ VENCIDA</span>' : ''}</div>
                            <button onclick="removeFromCart(${i})" class="text-red-400 text-2xl">×</button>
                        </div>
                        <div class="text-right text-xl font-bold">$${item.amount.toFixed(2)}</div>
                    </div>`;
                }
            }).join('');
        }

        function updateTotals() {
            let subtotal = cart.reduce((sum, item) => sum + (item.type === 'product' ? item.quantity * item.price : item.amount), 0);
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
            document.getElementById('modalTotal').textContent = '$' + total.toFixed(2);
        }

        function clearCart() {
            if (cart.length && confirm('¿Limpiar carrito?')) {
                cart = [];
                renderCart();
                updateTotals();
            }
        }

        function openPaymentModal() {
            if (cart.length === 0) { alert('Carrito vacío'); return; }
            
            const total = parseFloat(document.getElementById('total').textContent.replace('$', ''));
            paymentMethodsData = [{method: 'efectivo', amount: total.toFixed(2)}];
            
            renderPaymentMethods();
            document.getElementById('paymentModal').classList.add('active');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
            paymentMethodsData = [];
        }

        function renderPaymentMethods() {
            const total = parseFloat(document.getElementById('modalTotal').textContent.replace('$', ''));
            const container = document.getElementById('paymentMethods');
            
            container.innerHTML = `
                <div class="space-y-4 mb-6">
                    ${paymentMethodsData.map((pm, i) => `
                        <div class="bg-gray-50 border-2 border-gray-300 rounded-xl p-4">
                            <div class="flex gap-4 items-center">
                                <select onchange="updatePaymentMethod(${i}, this.value)" class="flex-1 px-4 py-3 border-2 rounded-xl font-semibold">
                                    <option value="efectivo" ${pm.method === 'efectivo' ? 'selected' : ''}>💵 Efectivo</option>
                                    <option value="transferencia" ${pm.method === 'transferencia' ? 'selected' : ''}>🏦 Transferencia</option>
                                    <option value="tarjeta_debito" ${pm.method === 'tarjeta_debito' ? 'selected' : ''}>💳 Tarjeta Débito</option>
                                    <option value="yappy" ${pm.method === 'yappy' ? 'selected' : ''}>📱 Yappy</option>
                                </select>
                                <input type="number" step="0.01" value="${pm.amount}" onchange="updatePaymentAmount(${i}, this.value)" class="w-32 px-4 py-3 border-2 rounded-xl text-right font-bold" placeholder="Monto">
                                ${paymentMethodsData.length > 1 ? `<button onclick="removePaymentMethod(${i})" class="text-red-600 text-2xl font-bold">×</button>` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="flex justify-between items-center mb-4">
                    <button onclick="addPaymentMethod()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">+ Agregar Método</button>
                    <div class="text-lg font-bold">Pagado: $<span id="totalPaid">0.00</span> / $${total.toFixed(2)}</div>
                </div>
            `;
            calculateTotalPaid();
        }

        function addPaymentMethod() {
            paymentMethodsData.push({method: 'efectivo', amount: '0.00'});
            renderPaymentMethods();
        }

        function removePaymentMethod(index) {
            paymentMethodsData.splice(index, 1);
            renderPaymentMethods();
        }

        function updatePaymentMethod(index, method) {
            paymentMethodsData[index].method = method;
        }

        function updatePaymentAmount(index, amount) {
            paymentMethodsData[index].amount = parseFloat(amount).toFixed(2);
            calculateTotalPaid();
        }

        function calculateTotalPaid() {
            const totalPaid = paymentMethodsData.reduce((sum, pm) => sum + parseFloat(pm.amount), 0);
            document.getElementById('totalPaid').textContent = totalPaid.toFixed(2);
        }

        function processPayment() {
            const total = parseFloat(document.getElementById('modalTotal').textContent.replace('$', ''));
            const totalPaid = paymentMethodsData.reduce((sum, pm) => sum + parseFloat(pm.amount), 0);

            if (totalPaid < total) {
                alert(`Falta pagar: $${(total - totalPaid).toFixed(2)}`);
                return;
            }

            const hasProducts = cart.some(i => i.type === 'product');
            const hasPayments = cart.some(i => i.type === 'payment');

            if (hasProducts && hasPayments) {
                alert('No se pueden mezclar productos y pagos');
                return;
            }

            document.getElementById('cart-data').value = JSON.stringify(cart);
            document.getElementById('payment-methods-data').value = JSON.stringify(paymentMethodsData);
            document.getElementById('discount-input').value = document.getElementById('discount').value;
            document.getElementById('total-input').value = total;

            const form = document.getElementById('checkout-form');
            form.action = hasProducts ? '{{ route("sales.store") }}' : '{{ route("payments.store") }}';
            form.submit();
        }

        let searchTimeout;
        document.getElementById('universal-search').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            const results = document.getElementById('search-results');

            if (query.length < 2) { results.classList.add('hidden'); return; }

            searchTimeout = setTimeout(async () => {
                const response = await fetch(`{{ route('pos.unified.search') }}?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (data.students.length === 0 && data.products.length === 0) {
                    results.innerHTML = '<div class="p-4 text-center text-gray-500">Sin resultados</div>';
                    results.classList.remove('hidden');
                    return;
                }

                let html = '';
                if (data.students.length) {
                    html += '<div class="p-2 bg-gray-100 font-bold text-sm">👥 ESTUDIANTES</div>';
                    data.students.forEach(s => {
                        html += `<a href="{{ route('pos.unified') }}?student_id=${s.id}" class="block p-3 hover:bg-gray-50 border-b"><p class="font-bold">${s.icon} ${s.name}</p><p class="text-sm text-gray-600">${s.identification}</p></a>`;
                    });
                }
                if (data.products.length) {
                    html += '<div class="p-2 bg-gray-100 font-bold text-sm">📦 PRODUCTOS</div>';
                    data.products.forEach(p => {
                        html += `<div class="p-3 hover:bg-gray-50 cursor-pointer border-b" onclick='addToCart(${JSON.stringify(p)}); document.getElementById("search-results").classList.add("hidden"); document.getElementById("universal-search").value = "";'><p class="font-bold">${p.icon} ${p.name}</p><p class="text-sm text-gray-600">$${p.price.toFixed(2)}</p></div>`;
                    });
                }
                results.innerHTML = html;
                results.classList.remove('hidden');
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#universal-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
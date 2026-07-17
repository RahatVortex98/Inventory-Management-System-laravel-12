<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if($errors->any())
                    <div class="alert alert-danger mb-4 text-red-600">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.saleStore') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name (optional)</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Walk-in customer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile_banking">Mobile Banking</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <table class="table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->current_stock }}">
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="stock-display">-</td>
                                <td class="price-display">-</td>
                                <td><input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required></td>
                                <td class="subtotal-display">0.00</td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="addRow" class="btn btn-sm btn-secondary mb-3">+ Add Item</button>

                    <h5>Total: <span id="grandTotal">0.00</span></h5>

                    <button type="submit" class="btn btn-primary">Complete Sale</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        const productOptions = document.querySelector('.product-select').innerHTML;
        let rowIndex = 1;

        function attachRowEvents(row) {
            const select = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');

            function updateRow() {
                const selected = select.options[select.selectedIndex];
                const price = parseFloat(selected.dataset.price || 0);
                const stock = selected.dataset.stock || '-';
                const qty = parseInt(qtyInput.value || 0);

                row.querySelector('.price-display').textContent = price.toFixed(2);
                row.querySelector('.stock-display').textContent = stock;
                row.querySelector('.subtotal-display').textContent = (price * qty).toFixed(2);
                updateGrandTotal();
            }

            select.addEventListener('change', updateRow);
            qtyInput.addEventListener('input', updateRow);
        }

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal-display').forEach(el => {
                total += parseFloat(el.textContent || 0);
            });
            document.getElementById('grandTotal').textContent = total.toFixed(2);
        }

        document.querySelectorAll('.item-row').forEach(attachRowEvents);

        document.getElementById('addRow').addEventListener('click', function () {
            const tbody = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML = `
                <td><select name="items[${rowIndex}][product_id]" class="form-select product-select" required>
                    <option value="">-- Select Product --</option>${productOptions}</select></td>
                <td class="stock-display">-</td>
                <td class="price-display">-</td>
                <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control qty-input" min="1" value="1" required></td>
                <td class="subtotal-display">0.00</td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
            `;
            tbody.appendChild(row);
            attachRowEvents(row);
            rowIndex++;
        });

        document.querySelector('#itemsTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('tr').remove();
                    updateGrandTotal();
                }
            }
        });
    </script>
</x-app-layout>
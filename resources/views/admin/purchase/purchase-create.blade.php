<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Purchase') }}
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

                <form action="{{ route('admin.purchaseStore') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>
                    <h6>Items</h6>
                    <table class="table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][quantity]" class="form-control" min="1" required></td>
                                <td><input type="number" name="items[0][unit_price]" class="form-control" step="0.01" min="0" required></td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="addRow" class="btn btn-sm btn-secondary mb-3">+ Add Item</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Save Purchase</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;
        const productOptions = `@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>@endforeach`;

        document.getElementById('addRow').addEventListener('click', function () {
            const tbody = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML = `
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-select" required>
                        <option value="">-- Select Product --</option>
                        ${productOptions}
                    </select>
                </td>
                <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control" min="1" required></td>
                <td><input type="number" name="items[${rowIndex}][unit_price]" class="form-control" step="0.01" min="0" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
            `;
            tbody.appendChild(row);
            rowIndex++;
        });

        document.querySelector('#itemsTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('tr').remove();
                }
            }
        });
    </script>
</x-app-layout>
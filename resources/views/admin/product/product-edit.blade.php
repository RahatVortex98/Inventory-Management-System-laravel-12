<x-admin-layout>

<div class="max-w-4xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Update Product
        </h1>

        <p class="text-gray-500 mb-8">
            Update the product information below.
        </p>

        <form method="POST" action="{{ route('admin.productUpdate', $product->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Category --}}
                <div>
                    <label class="block font-medium mb-2">
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                        <option value="">Select Category</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Brand --}}
                <div>
                    <label class="block font-medium mb-2">
                        Brand
                    </label>

                    <select
                        name="brand_id"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                        <option value="">Select Brand</option>

                        @foreach($brands as $brand)
                            <option
                                value="{{ $brand->id }}"
                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('brand_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Supplier --}}
                <div>
                    <label class="block font-medium mb-2">
                        Supplier
                    </label>

                    <select
                        name="supplier_id"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                        <option value="">Select Supplier</option>

                        @foreach($suppliers as $supplier)
                            <option
                                value="{{ $supplier->id }}"
                                {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach

                    </select>

                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Name --}}
                <div>
                    <label class="block font-medium mb-2">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label class="block font-medium mb-2">
                        SKU
                    </label>

                    <input
                        type="text"
                        name="sku"
                        value="{{ old('sku', $product->sku) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('sku')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unit --}}
                <div>
                    <label class="block font-medium mb-2">
                        Unit
                    </label>

                    <input
                        type="text"
                        name="unit"
                        value="{{ old('unit', $product->unit) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cost Price --}}
                <div>
                    <label class="block font-medium mb-2">
                        Cost Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="cost_price"
                        value="{{ old('cost_price', $product->cost_price) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('cost_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Selling Price --}}
                <div>
                    <label class="block font-medium mb-2">
                        Selling Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="selling_price"
                        value="{{ old('selling_price', $product->selling_price) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('selling_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current Stock --}}
                <div>
                    <label class="block font-medium mb-2">
                        Current Stock
                    </label>

                    <input
                        type="number"
                        name="current_stock"
                        value="{{ old('current_stock', $product->current_stock) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('current_stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reorder Level --}}
                <div>
                    <label class="block font-medium mb-2">
                        Reorder Level
                    </label>

                    <input
                        type="number"
                        name="reorder"
                        value="{{ old('reorder', $product->reorder) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    @error('reorder')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <input type="hidden" name="status" value="0">

            <div class="mt-8 flex items-center gap-3">

                <input
                    type="checkbox"
                    id="status"
                    name="status"
                    value="1"
                    {{ old('status', $product->status) ? 'checked' : '' }}
                    class="h-5 w-5 rounded">

                <label for="status" class="font-medium">
                    Active Product
                </label>

            </div>

            <div class="mt-8 flex gap-4">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Update Product
                </button>

                <a
                    href="{{ route('admin.productList') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</x-admin-layout>
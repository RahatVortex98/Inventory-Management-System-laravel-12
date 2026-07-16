<x-admin-layout>

<div class="max-w-xl mx-auto mt-8 bg-white shadow rounded-lg p-6">

    <h1 class="text-3xl font-bold mb-6">Add Brand</h1>

    <form method="POST" action="{{ route('admin.brandStore') }}">
        @csrf

        {{-- Category --}}
        <div class="mb-4">
            <label class="block mb-2">Category</label>

            <select name="category_id" class="w-full border rounded p-2">
                <option value="">-- Select Category --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Brand Name --}}
        <div class="mb-4">
            <label class="block mb-2">Brand Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full border rounded p-2"
                placeholder="Enter brand name">

            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="block mb-2">Description</label>

            <textarea
                name="description"
                rows="4"
                class="w-full border rounded p-2">{{ old('description') }}</textarea>

            @error('description')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-6">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    {{ old('status') ? 'checked' : '' }}>

                <span class="ml-2">Active</span>
            </label>
        </div>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
            Save Brand
        </button>

    </form>

</div>

</x-admin-layout>
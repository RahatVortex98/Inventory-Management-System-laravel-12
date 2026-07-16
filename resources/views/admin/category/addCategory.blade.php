<x-admin-layout>
    <div class="max-w-xl mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">Add Category</h1>

        <form method="POST" action="{{ route('admin.storeCategory') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Category Name</label>

                <input
                    type="text"
                    name="name"
                    class="w-full border rounded p-2"
                    placeholder="Enter category name">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Category Description</label>

                <textarea
                    name="description"
                    class="w-full border rounded p-2"
                    rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label>
                    <input
                        type="checkbox"
                        name="status"
                        value="1">

                    Active
                </label>
            </div>

            <button
                type="submit"
                class="bg-green-950 text-black px-5 py-2 rounded">
                Save Category
            </button>
        </form>
    </div>
</x-admin-layout>
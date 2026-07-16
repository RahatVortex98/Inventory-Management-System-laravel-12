<x-admin-layout>
    <div class="max-w-xl mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">Add Supplier</h1>

        <form method="POST" action="{{ route('admin.supplierCreate') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Create Supplier</label>

                <input
                    type="text"
                    name="supplier_name"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier name">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Contact Person</label>

                <input
                    type="text"
                    name="contact_person"
                    class="w-full border rounded p-2"
                    placeholder="Enter contact person">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Email</label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier email">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Phone</label>

                <input
                    type="number"
                    name="phone"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier phone number">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Supplier Address</label>

                <textarea
                    name="address"
                    class="w-full border rounded p-2"
                    rows="4"></textarea>
            </div>
             <div class="mb-4">
                <label class="block mb-2">City</label>

                <input
                    type="text"
                    name="city"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier city">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Postal Code</label>

                <input
                    type="number"
                    name="postal_code"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier phone number">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Country</label>

                <input
                    type="text"
                    name="country"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier phone number">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Website</label>

                <input
                    type="url"
                    name="website"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier website if available">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Tax_number</label>

                <input
                    type="number"
                    name="tax_number"
                    class="w-full border rounded p-2"
                    placeholder="Enter supplier tax number">
            </div>
                 <div class="mb-4">
                <label class="block mb-2">Notes</label>

                <textarea
                    name="notes"
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
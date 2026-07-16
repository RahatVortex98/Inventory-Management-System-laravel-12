<x-admin-layout>
    <div class="max-w-xl mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">Add Supplier</h1>

        <form method="POST" action="{{ route('admin.supplierUpdate',$supplier->id) }}">
            @method('PUT')
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Supplier Name</label>

                <input
                    type="text"
                    name="supplier_name"
                    class="w-full border rounded p-2"
                    value="{{ old('supplier_name', $supplier->supplier_name) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Contact Person</label>

                <input
                    type="text"
                    name="contact_person"
                    class="w-full border rounded p-2"
                    value="{{ old('contact', $supplier->contact_person) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Email</label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded p-2"
                    value="{{ old('email', $supplier->email) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-2">Phone</label>

                <input
                    type="number"
                    name="phone"
                    class="w-full border rounded p-2"
                    value="{{ old('phone', $supplier->phone) }}">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Supplier Address</label>

                <textarea
                    name="address"
                    class="w-full border rounded p-2"
                    rows="4">"{{ old('address', $supplier->address) }}"</textarea>
            </div>
             <div class="mb-4">
                <label class="block mb-2">City</label>

                <input
                    type="text"
                    name="city"
                    class="w-full border rounded p-2"
                    value="{{ old('city', $supplier->city) }}">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Postal Code</label>

                <input
                    type="number"
                    name="postal_code"
                    class="w-full border rounded p-2"
                    value="{{ old('postal_code', $supplier->postal_code) }}">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Country</label>

                <input
                    type="text"
                    name="country"
                    class="w-full border rounded p-2"
                    value="{{ old('country', $supplier->country) }}">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Website</label>

                <input
                    type="url"
                    name="website"
                    class="w-full border rounded p-2"
                    value="{{ old('website', $supplier->website) }}">
            </div>
             <div class="mb-4">
                <label class="block mb-2">Tax_number</label>

                <input
                    type="number"
                    name="tax_number"
                    class="w-full border rounded p-2"
                    value="{{ old('tax_number', $supplier->tax_number) }}">
            </div>
                 <div class="mb-4">
                <label class="block mb-2">Notes</label>

                <textarea
                    name="notes"
                    class="w-full border rounded p-2"
                    rows="4">{{ old('notes', $supplier->notes) }}</textarea>
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
                Edit supplier
            </button>
        </form>
    </div>
</x-admin-layout>
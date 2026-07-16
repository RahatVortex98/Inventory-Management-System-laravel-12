@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Suppliers
    </h2>
@endsection

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Suppliers List</h1>

        <a href="{{ route('admin.supplierCreate') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Add Supplier
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm whitespace-nowrap">

                <thead class="bg-gray-100">
                    <tr>

                        <th class="px-3 py-3 border">#</th>
                        <th class="px-3 py-3 border">Supplier</th>
                        <th class="px-3 py-3 border">Contact</th>
                        <th class="px-3 py-3 border">Email</th>
                        <th class="px-3 py-3 border">Phone</th>
                        <th class="px-3 py-3 border">Address</th>
                        <th class="px-3 py-3 border">City</th>
                        <th class="px-3 py-3 border">Postal</th>
                        <th class="px-3 py-3 border">Country</th>
                        <th class="px-3 py-3 border">Website</th>
                        <th class="px-3 py-3 border">Tax No</th>
                        <th class="px-3 py-3 border">Status</th>
                        <th class="px-3 py-3 border">Notes</th>
                        <th class="px-3 py-3 border">Action</th>

                    </tr>
                </thead>

                <tbody>

                @forelse($suppliers as $supplier)

                    <tr class="hover:bg-gray-50">

                        <td class="border px-3 py-2">{{ $loop->iteration }}</td>

                        <td class="border px-3 py-2">
                            {{ $supplier->supplier_name }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->contact_person }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->email }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->phone }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->address }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->city }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->postal_code }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->country }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->website }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->tax_number }}
                        </td>

                        <td class="border px-3 py-2 text-center">

                            @if($supplier->status)
                                <span class="text-green-600 font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="text-red-600 font-semibold">
                                    Inactive
                                </span>
                            @endif

                        </td>

                        <td class="border px-3 py-2">
                            {{ $supplier->notes }}
                        </td>

                        <td class="border px-3 py-2">

                            <div class="flex gap-3">

                                <a href="{{ route('admin.supplierEdit',$supplier->id) }}"
                                   class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('admin.supplierDelete',$supplier->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Delete this supplier?')"
                                        class="text-red-600 hover:underline">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="14"
                            class="text-center py-6 text-gray-500">
                            No suppliers found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
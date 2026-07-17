<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Purchases') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('admin.purchaseCreate') }}" class="btn btn-primary">+ New Purchase</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Recorded By</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->supplier_name }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>{{ number_format($purchase->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $purchase->status === 'received' ? 'success' : 'warning' }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td>{{ $purchase->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No purchases yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $purchases->links() }}

            </div>
        </div>
    </div>
</x-admin-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('user.saleCreate') }}" class="btn btn-primary">+ New Sale</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td>{{ ucfirst($sale->payment_method) }}</td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No sales yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $sales->links() }}

            </div>
        </div>
    </div>
</x-app-layout>
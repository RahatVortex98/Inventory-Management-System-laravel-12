@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl">Admin Dashboard</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500 text-sm">Sales today</p>
            <p class="text-3xl font-bold">{{ $todaySales }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500 text-sm">Revenue today</p>
            <p class="text-3xl font-bold">{{ number_format($todayRevenue, 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500 text-sm">Low stock items</p>
            <p class="text-3xl font-bold text-red-600">{{ $lowStockProducts->count() }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="font-semibold mb-3">Recent sales</h3>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Cashier</th>
                    <th class="py-2">Customer</th>
                    <th class="py-2">Total</th>
                    <th class="py-2">When</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales as $sale)
                <tr class="border-b">
                    <td class="py-2">{{ $sale->user->name }}</td>
                    <td class="py-2">{{ $sale->customer_name ?? 'Walk-in' }}</td>
                    <td class="py-2">{{ number_format($sale->total_amount, 2) }}</td>
                    <td class="py-2">{{ $sale->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-2 text-gray-500">No sales yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lowStockProducts->count())
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="font-semibold mb-3 text-red-600">Low stock — reorder soon</h3>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Product</th>
                    <th class="py-2">Current stock</th>
                    <th class="py-2">Reorder level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                <tr class="border-b">
                    <td class="py-2">{{ $product->name }}</td>
                    <td class="py-2 text-red-600 font-semibold">{{ $product->current_stock }}</td>
                    <td class="py-2">{{ $product->reorder }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
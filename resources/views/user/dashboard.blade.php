<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
                        <h3 class="text-2xl font-bold">{{ $todaySalesCount ?? 0 }} sales today</h3>
                    </div>
                    <a href="{{ route('user.saleCreate') }}" class="btn btn-primary bg-indigo-600 text-white px-4 py-2 rounded">+ New Sale</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categories') }}
    </h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Category List</h1>

        <a href="{{ route('admin.addCategory') }}"
           class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
            + Add Category
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-3 text-left">#</th>
                    <th class="border px-4 py-3 text-left">Category Name</th>
                    <th class="border px-4 py-3 text-left">Description</th>
                    <th class="border px-4 py-3 text-center">Status</th>
                    <th class="border px-4 py-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-3">{{ $loop->iteration }}</td>

                        <td class="border px-4 py-3">
                            {{ $category->name }}
                        </td>

                        <td class="border px-4 py-3">
                            {{ $category->description }}
                        </td>

                        <td class="border px-4 py-3 text-center">
                            @if ($category->status)
                                <span class="text-green-600 font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="text-red-600 font-semibold">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td class="border px-4 py-3 text-center">
                            <a href="{{ route('admin.editCategory',$category->id) }}" class="text-blue-600 hover:underline">
                                Edit
                            </a>

                            |

                       <form method="POST" action="{{ route('admin.deleteCategory', $category->id) }}">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-red-600 hover:underline"
                                onclick="return confirm('Are you sure you want to delete this category?')">
                                Delete
                            </button>
                        </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border px-4 py-6 text-center text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
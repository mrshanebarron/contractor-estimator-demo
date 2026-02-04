<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit: {{ $estimate->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('estimates.update', $estimate) }}">
                @csrf
                @method('PUT')
                @include('estimates._form', ['estimate' => $estimate])
            </form>
        </div>
    </div>
</x-app-layout>

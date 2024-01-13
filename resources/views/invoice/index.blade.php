@extends('layout.app')

@push('style')
    @livewireStyles
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Invoice</h1>
        <a href="{{ route('invoice.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah Invoice</a>
    </div>
</header>
<main>
    @livewire('invoice-list-table')
</main>
@endsection

@push('script')

@livewireScripts

@endpush
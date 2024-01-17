@extends('layout.app')

@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <h1 class="text-2xl">Dashboard</h1>
</header>
<main>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <div class="flex gap-3 mb-2">
                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 19">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 12 5.419 3.871A1 1 0 0 0 16 15.057V2.943a1 1 0 0 0-1.581-.814L9 6m0 6V6m0 6H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h7m-5 6h2v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-5Zm15-3a3 3 0 0 1-3 3V6a3 3 0 0 1 3 3Z"/>
                </svg>
                <h2 class="text-base font-medium">Pengumuman</h2>
            </div>
            <hr class="mb-4">

            <div class="flex justify-center items-center">
                Comming Soon
            </div>
        </div>

        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex gap-3 mb-2">
                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h9M5 9h5m8-8H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h4l3.5 4 3.5-4h5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
                    </svg>
                    <h2 class="text-base font-medium">Catatan</h2>
                </div>
            </div>

            <hr class="mb-4">

            <div class="flex justify-center items-center">
                Comming Soon
            </div>
        </div>

    </div>

    {{-- Statistik chart start --}}
    @can('chart-statistik-invoice')
        @livewire('chart-statistik-invoice')
    @endcan
    {{-- Statistik chart end --}}

</main>
@endsection
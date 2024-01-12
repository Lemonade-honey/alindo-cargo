@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#vendor-list').DataTable({ responsive: true });
        });
        $(document).ready(function() {
            $('#kota-list').select2();
        });

        const btnCreate = document.getElementById('createBtn');
        btnCreate.addEventListener("click", () => {
            const formCreate = document.getElementById("form-create")
            formCreate.submit();
        })
    </script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Kota</h1>

        @can('kota-kelola')
        <button type="button" data-modal-target="modal-tambah-kota" data-modal-toggle="modal-tambah-kota" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</button>
        @endcan
    </div>
</header>
@include('include.flash')

@can('kota-kelola')
<div id="modal-tambah-kota" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Kota
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-tambah-kota">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <form action="#" method="post" id="form-create">
                    @csrf
                    <div class="mb-6">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="nama" id="nama" name="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('nama') }}">
                    </div>
                    <div class="mb-6">
                        <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                        <input type="number" id="harga" name="harga" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('harga') }}">
                    </div>
                    
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b ">
                <button type="button" id="createBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
            </div>
        </div>
    </div>
</div>
@endcan


<div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
    <div class="relative overflow-x-auto">
        <table id="vendor-list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kota</th>
                    <th>Harga/Kg</th>
                    @can('kota-kelola')
                    <th>Action</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach ($kotas as $key => $item)
                <tr class="border-b border-gray-100 hover:bg-gray-100">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->kota }}</td>
                    <td>Rp. {{ number_format($item->harga) }}</td>
                    @can('kota-kelola')
                    <td>
                        <a href="{{ route('kota.detail', ['id' => $item->id]) }}" class="text-blue-600 p-3 rounded-lg hover:bg-gray-200">Detail</a>
                    </td>
                    @endcan
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
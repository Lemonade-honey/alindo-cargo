@extends('layout.app')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
    .select2.select2-container {
        width: 100% !important;
    }

    .select2.select2-container .select2-selection {
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        height: 34px;
        outline: none !important;
        transition: all .15s ease-in-out;
    }

    .select2.select2-container .select2-selection .select2-selection__rendered {
        color: #333;
        font-size: 0.875rem;
        line-height: 32px;
        --tw-bg-opacity: 1;
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
    }

    .select2.select2-container .select2-selection .select2-selection__arrow {
        background: #f8f8f8;
        border-left: 1px solid #ccc;
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        height: 32px;
        width: 33px;
    }
</style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#costumer-list').DataTable({ responsive: true });
            $('#langganan-list').DataTable({ responsive: true });

            $('#costumer-1').select2({
                placeholder: "nama-kontak costumer"
            });

            $('#costumer-2').select2({
                placeholder: "nama-kontak costumer"
            });
        });
    </script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">Costumer</h1>
        <button type="button" data-modal-target="modal-costumer-tambah" data-modal-toggle="modal-costumer-tambah" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</button>
    </div>
</header>

@include('include.flash')

<div id="modal-costumer-tambah" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Costumer
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-costumer-tambah">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <form action="{{ route('costumer.create.post') }}" method="post" id="form-create">
                    @csrf
                    <div class="mb-6">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" id="nama" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('name') }}">
                    </div>
                    <div class="mb-6">
                        <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kontak</label>
                        <input type="number" id="harga" name="kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('kontak') }}">
                    </div>
                    <div class="mb-6">
                        <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                        <textarea name="alamat" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 gap-3">
    <div class="w-full p-4 border border-gray-100 rounded-lg shadow">
        <div class="mb-4">
            <h2 class="text-xl">List Costumer</h2>
            <p class="text-sm">data costumer yang tersimpan</p>
        </div>
        <div class="relative overflow-x-auto">
            <table id="costumer-list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($costumers as $key => $costumer)
                    <tr class="border-b border-gray-100 hover:bg-gray-100">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $costumer->name }}</td>
                        <td>{{ $costumer->kontak }}</td>
                        <td>{{ $costumer->alamat }}</td>
                        <td>
                            <a href="{{ route('costumer.edit', ['id' => $costumer->id]) }}" class="text-blue-600 p-3 rounded-lg hover:bg-gray-200">Edit</a>
                            <a href="{{ route('costumer.delete', ['id' => $costumer->id]) }}" class="text-red-600 p-3 rounded-lg hover:bg-gray-200">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl">List Langganan Costumer</h2>
                <p class="text-sm">data langganan antar costumer yang tersimpan</p>
            </div>

            <button type="button" data-modal-target="modal-tambah-hubungan" data-modal-toggle="modal-tambah-hubungan" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</button>

            <div id="modal-tambah-hubungan" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Tambah Costumer
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-tambah-hubungan">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <form action="{{ route('langganan.create.post') }}" method="post" id="form-create">
                                @csrf
                                <div class="mb-6">
                                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costumer</label>
                                    <select name="costumer1" id="costumer-1">
                                        <option></option>
                                        @foreach ($costumers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}, {{ $item->kontak }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hubungan</label>
                                    <input type="text" name="hubungan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('hubungan') }}">
                                </div>
                                <div class="mb-6">
                                    <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costumer</label>
                                    <select name="costumer2" id="costumer-2">
                                        <option></option>
                                        @foreach ($costumers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}, {{ $item->kontak }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table id="langganan-list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Costumer</th>
                        <th>Hubungan</th>
                        <th>Costumer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hubungan as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->costumer1 != null ? $item->costumer1->name . "," . $item->costumer1->kontak : '' }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->costumer2 != null ? $item->costumer2->name . "," . $item->costumer2->kontak : '' }}</td>
                        <td>
                            <a href="{{ route('langganan.delete', ['id' => $item->id]) }}" class="text-red-600 p-3 rounded-lg hover:bg-gray-200">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
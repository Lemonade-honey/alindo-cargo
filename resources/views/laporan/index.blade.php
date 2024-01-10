@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#laporan-list').DataTable({ responsive: true });
        });
    </script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Laporan</h1>
        <button type="button" data-modal-target="modal-tambah-laporan" data-modal-toggle="modal-tambah-laporan" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</button>
    </div>
</header>

@include('include/flash')

<div id="modal-tambah-laporan" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Rekap Laporan
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-tambah-laporan">
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
                        <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900">Bulan, Tahun</label>
                        <input type="month" name="tanggal" id="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button> 
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

<div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
    <div class="relative overflow-x-auto">
        <table id="laporan-list">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th>No</th>
                    <th>Laporan</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporans as $key => $value)
                <tr class="hover:bg-gray-200">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('F, Y', strtotime($value->tanggal)) }}</td>
                    <td>{{ $value->created_at }}</td>
                    <td>
                        <a href="{{ route('laporan.detail', ['tanggal' => $value->tanggal]) }}" class="text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
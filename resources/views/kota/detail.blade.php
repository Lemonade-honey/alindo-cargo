@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#user-role').DataTable({ responsive: true });
        });

        const btnDelete = document.querySelectorAll('#btnDelete')
        btnDelete.forEach(e => {
            e.addEventListener("click", () => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Kota akan dihapus secara permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.isConfirmed){
                        Swal.fire({
                            title: "Masukan nama kota dibawah ini",
                            input: "text",
                            inputLabel: "{{ $kota->kota }}",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Hapus",
                            inputValidator: (value) => {
                                if (!value) return "Masukkan nama kota!";
                                if (value != "{{ $kota->kota }}") return "Data tidak sama";

                                if(value == "{{ $kota->kota }}"){
                                    window.location = "{{ route('kota.delete', ['id' => $kota->id]) }}";
                                    return;
                                }
                            }
                        })
                    }
                });
            })
        });
    </script>
@endpush

@section('body')
<header class="mb-5">
    <h1 class="text-xl font-medium capitalize">Detail Kota {{ $kota->kota }}</h1>
    <nav class="flex my-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('kota') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Kota
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">{{ $kota->kota }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<div class="grid grid-cols-1 gap-5">
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <p class="mb-3 font-medium">Kota dengan Vendor</p>
        <div class="relative overflow-x-auto">
            <table id="user-role">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($kota->vendor as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                <a href="{{ route('vendor.detail', ['id' => $item->id]) }}" class="text-blue-500 hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
    <form method="POST" class="w-full p-4 border border-gray-100 shadow rounded-lg">
        @csrf
        <div class="mb-6">
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
            <input type="nama" id="nama" name="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('nama') ?? $kota->kota }}">
        </div>
        <div class="mb-6">
            <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
            <input type="number" id="harga" name="harga" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('harga') ?? $kota->harga }}">
        </div>

        <div class="flex justify-end mt-10">
            <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">POST</button>
        </div>
    </form>

    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <h3 class="font-medium text-lg">Hapus Kota</h3>
        <p class="text-xs"><span class="text-red-600">*</span> hal ini akan mempengaruhi <span class="underline font-medium">data vendor</span> yang terhubung dengan kota ini akan <span class="font-bold">coruption</span></p>
        <div class="flex justify-center mt-5">
            <button type="button" id="btnDelete" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">DELETE</button>
        </div>
    </div>
</div>
@endsection
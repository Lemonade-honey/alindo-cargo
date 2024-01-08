@extends('layout.app')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        $('#kota').select2({
            placeholder: 'Pilih Kota..'
        });
    });

    const btnDelete = document.getElementById("btnDelete")
    btnDelete.addEventListener("click", () => {
        Swal.fire({
            title: "Are you sure?",
            text: "Wilayah akan dihapus secara permanen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if(result.isConfirmed){
                window.location = "{{ route('vendor.wilayah.delete', ['id' => $wilayahVendor->id_vendor, 'id_gabung' => $wilayahVendor->id]) }}"
            }
        })
    })
</script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-100 shadow rounded-lg mb-5">
    <h1 class="text-xl font-medium">Detail Wilayah</h1>
    <nav class="flex my-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('vendor') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Vendor
                </a>
            </li>
            <li class="inline-flex items-center">
                <a href="{{ route('vendor.detail', ['id' => $wilayahVendor->vendor->id]) }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    {{ $wilayahVendor->vendor->nama }}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 capitalize">{{ $wilayahVendor->kota->kota }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<div class="grid grid-cols-1 gap-3 mt-5">
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <h2 class="text-base font-medium mb-4">Detail</h2>
        <form action="#" method="post">
            @csrf
            <div class="mb-6">
                <label for="kota" class="block mb-2 text-sm font-medium text-gray-900">Wilayah</label>
                <select name="kota" id="kota">
                    @foreach ($kotas as $key => $value)
                        <option value="{{ $value->id }}" @selected($value->id == $wilayahVendor->kota->id)>{{ $value->kota }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label for="harga" class="block mb-2 text-sm font-medium text-gray-900">Harga</label>
                <input type="number" id="harga" name="harga" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Rp." required value="{{ old('harga') ?? $wilayahVendor->harga }}">
            </div>
            <div class="mb-6">
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') ?? $wilayahVendor->deskripsi }}</textarea>
            </div>
            <div class="flex justify-end mt-5">
                <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">UPDATE</button>
            </div>
        </form>
    </div>

    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <h2 class="text-base font-medium">Hapus</h2>
        <p class="text-xs">Hal ini akan berdampak pada data <span class="underline font-bold">invoice dan laporan</span> yang terhubung dengan vendor wilayah ini</p>
        <div class="flex justify-center mt-5">
            <button type="button" id="btnDelete" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">DELETE</button>
        </div>
    </div>
</div>
@endsection
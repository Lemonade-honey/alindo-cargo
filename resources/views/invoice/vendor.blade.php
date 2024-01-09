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
        $('#kota-vendor').select2({
            placeholder: 'Pilih Kota Vendor..'
        });
        $('#nama-vendor').select2({
            placeholder: 'Pilih Nama Vendor..'
        });
    });

    // api
    $('#kota-vendor').change(function(){
        $('#nama-vendor').val(null)
        let id = $('#kota-vendor').val()
        $('#nama-vendor').select2({
            placeholder: 'Pilih Nama Vendor..',
            ajax: {
                url: "{{ url('/api/vendors/kota') }}/" + id,
                processResults: function (data) {
                    return {
                        results: $.map(data, function(item){
                            if(item != null){
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            }
                        })

                    }
                }
            }
        })
    })
</script>

<script>
const deleteBtn = document.querySelectorAll("#deleteBtn")
deleteBtn.forEach(e => {
    e.addEventListener("click", () => {
        console.log(e.getAttribute("target"))
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "/dashboard/invoice/{{ $invoice->invoice }}/vendors/delete/" + e.getAttribute("target")
            }
        });
    })
});
</script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="mb-3">
        <h1 class="text-2xl">Detail Vendor Invoice {{ $invoice->invoice }}</h1>
        <hr>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('invoice') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Invoice
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{ route('invoice.detail', ['invoice' => $invoice->invoice]) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">{{ $invoice->invoice }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Vendor</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include/flash')

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
        <h2 class="text-lg font-medium">Tambah Vendor</h2>

        <div class="flex items-center justify-around p-4">
            <p class="p-2 border border-gray-100 shadow rounded-lg">{{ $invoice->asal }}</p>
            <p class="text-lg font-bold">---></p>
            <p class="p-2 border border-gray-100 shadow rounded-lg">{{ $invoice->tujuan }}</p>
        </div>
        <form action="#" method="post">
            @csrf
            <div class="mb-6">
                <label for="kota-vendor" class="block mb-2 text-sm font-medium text-gray-900">Kota Vendor</label>
                <select name="kota-vendor" id="kota-vendor" class="w-full" required>
                    <option></option>
                    @foreach ($kota as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->kota }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label for="nama-vendor" class="block mb-2 text-sm font-medium text-gray-900">Nama Vendor</label>
                <select name="id-vendor" id="nama-vendor" class="w-full" required>
                </select>
                <p class="text-sm font-light mt-3"><span class="text-red-500">*</span> jika data vendor tidak ada, pergi ke menu vendor untuk mengisi data vendor</p>
            </div>

            <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2">Tambah</button>
        </form>
    </div>
    <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
        <h2 class="mb-6 text-lg font-medium">List Vendor Invoice</h2>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Kota Vendor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Vendor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Harga Vendor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($invoice->vendors["vendors"] as $key => $value)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{ $value["kota_vendor"] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $value["nama_vendor"] }}
                        </td>
                        <td class="px-6 py-4">
                            Rp. {{ number_format($value["harga_vendor"]) }}
                        </td>
                        <td class="px-6 py-4">
                            Rp. {{ number_format($value["total_vendor"]) }}
                        </td>
                        <td class="px-6 py-4">
                            <button id="deleteBtn" target="{{ $value["id"] }}" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-1.5 mr-2 mb-2" title="Hapus Vendor">
                                <div class="w-6">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 12L14 16M14 12L10 16M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="2" class="px-6 py-4 font-bold">Total Dana</td>
                        <td class="px-6 py-4">Rp. {{ number_format($invoice->vendors['total-harga']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
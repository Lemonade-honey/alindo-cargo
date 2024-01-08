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
            $('#vendor-invoice').DataTable({ responsive: true });
            $('#track-history').DataTable({ responsive: true });
            $('#tracking-list').DataTable({ responsive: true });
        });

        const textInput = document.getElementById('keterangan');
        textInput.addEventListener("keydown", function(){
            const maxLength = 100;
            if(textInput.value.length > maxLength) {
                textInput.value = textInput.value.substr(0, maxLength);
            }

            const target = document.getElementById('notif-length');
            target.innerText = textInput.value.length
        })

        const btnDelete = document.querySelectorAll('#btnDelete')
        btnDelete.forEach(e => {
            e.addEventListener("click", () => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Invoice akan dihapus secara permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.isConfirmed){
                        Swal.fire({
                            title: "Masukan Kode dibawah ini",
                            input: "text",
                            inputLabel: "{{ $invoice->invoice }}",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Hapus",
                            inputValidator: (value) => {
                                if (!value) return "Masukkan Kode Invoice!";
                                if (value != "{{ $invoice->invoice }}") return "Data tidak sama";

                                if(value == "{{ $invoice->invoice }}"){
                                    window.location = "{{ route('invoice.delete', ['invoice' => $invoice->invoice]) }}";
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
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="mb-3">
        <h1 class="text-2xl">Detail Invoice</h1>
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
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">{{ $invoice->invoice }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<main>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <div class="w-full p-4 border border-gray-100 rounded-lg shadow">
            <p class="mb-2 font-medium">Invoice Status</p>
            <div class="flex gap-3">
                <p>Status : </p>
                @if ($invoice->status == "proses")
                <span class="text-blue-500 font-medium">Proses</span>
                @elseif($invoice->status == "selesai")
                <span class="text-green-500 font-medium">Selesai</span>
                @else
                <span class="text-red-500 font-medium">Batal</span>
                @endif
            </div>
            <div class="mt-1">
                <p>Keterangan : <span>{{ $invoice->keterangan }}</span></p>
            </div>
            
            <div class="mt-5 flex justify-end">
                <button type="button" data-modal-target="status-invoice" data-modal-toggle="status-invoice" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Set Status</button>
            </div>

            {{-- Model Start --}}
            <div id="status-invoice" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Set Status Invoice
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="status-invoice">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <form action="{{ route('invoice.status', ['invoice' => $invoice->invoice]) }}" method="post">
                                @csrf
                                <div class="mb-6">
                                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">
                                        <option value="proses" @selected($invoice->status == 'proses')>Proses</option>
                                        <option value="selesai" @selected($invoice->status == 'selesai')>Selesai</option>
                                        <option value="batal" @selected($invoice->status == 'batal')>Batal</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900">Keterangan (Optional max <span id="notif-length">0</span>/100)</label>
                                    <textarea name="keterangan" id="keterangan" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ $invoice->keterangan }}</textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Model End --}}
        </div>
    
        <div class="w-full p-4 border border-gray-100 rounded-lg shadow">
            <p class="font-medium mb-2">Status Pembayaran Invoice</p>
            <div class="flex justify-between">
                <div class="">
                    <p>Status :
                        <span class="{{ ($invoice->invoiceCost->status == 'Lunas') ? 'text-green-500' : 'text-red-500' }} font-medium">{{ $invoice->invoiceCost->status }}</span>
                    </p>
                    <p>
                        Metode Pembayaran : {{ $invoice->invoiceCost->metode ?? "-" }}
                    </p>
                    <p>Bukti Transaksi : 
                        <a href="#" class="text-blue-500 underline hover:text-blue-700">{{ $invoice->invoiceCost->bukti }}</a>
                    </p>
                </div>
                <div class="">
                    <p>Update : {{ date('H:i, d M Y', strtotime($invoice->invoiceCost->updated_at)) }}</p>
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Set Ulang</a>
            </div>
        </div>
    </div>

    {{-- Start Form Data --}}
    <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
        <p class="mb-2 font-medium">Invoice setting</p>
        <label class="relative inline-flex items-center">
            <input type="checkbox" value="" class="sr-only peer" disabled @checked($invoice->invoiceData->form_setting["form-lock"] == 1)>
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900">Form Lock</span>
        </label>
        <p class="mt-1">Member : {{ $invoice->invoiceData->form_setting["member-id"] != null ? $invoice->invoicePerson->pengirim : '-' }}</p>
    </div>
    <div class="p-4 border border-gray-200 rounded-lg shadow">
        <p class="font-medium mb-5">Data Invoice</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="w-full">
                <div class="mb-6 flex gap-3">
                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Invoice</label>
                        <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold">{{ $invoice->invoice }}</p>
                    </div>
                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                        <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ date("H:i:s, d M Y", strtotime($invoice->created_at)) }}</p>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Kota Asal</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">{{ $invoice->asal }}</p>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Kota Tujuan</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">{{ $invoice->tujuan }}</p>
                </div>
                <div class="mb-6">
                    <div class="flex gap-3">
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Berat</label>
                            <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoiceData->berat }}</p>
                        </div>
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Harga/Kg</label>
                            <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">Rp. {{ number_format($invoice->invoiceData->harga_kg) }}</p>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Keterangan Barang</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoiceData->kategori }}</p>
                </div>
                <div class="flex justify-between gap-3 mb-10">
                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Koli</label>
                        <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            {{ $invoice->invoiceData->koli }}
                        </p>
                    </div>
                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Pemeriksaan Barang</label>
                        <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            {{ $invoice->invoiceData->pemeriksaan ? 'iya' : 'tidak' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nama Pengirim</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoicePerson->pengirim }}</p>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Kontak Pengirim</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoicePerson->kontak_pengirim }}</p>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nama Penerima</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoicePerson->penerima }}</p>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Kontak Penerima</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $invoice->invoicePerson->kontak_penerima }}</p>
                </div>
                <div class="mb-10">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Alamat Penerima</label>
                    <textarea name="alamat-penerima" id="alamat-penerima" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap..." readonly>{{ $invoice->invoicePerson->alamat }}</textarea>
                </div>
            </div>
            <div class="">
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Biaya Kirim</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">Rp. {{ number_format($invoice->invoiceCost->biaya_kirim) }}</p>
                </div>
                <div class="mb-6">
                    <label for="kontak-penerima" class="block mb-2 text-sm font-medium text-gray-900">Biaya Lainnya</label>
                    @foreach ($invoice->invoiceCost->biaya_lainnya as $item)
                    <div class="flex gap-2 items-center mb-5">
                        <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            {{ $item["keterangan"] }}
                        </div>
                        <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            Rp. {{ number_format($item["harga"]) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Total Biaya</label>
                    <p class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">Rp. {{ number_format($invoice->invoiceCost->biaya_total) }}</p>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('invoice.cetak.resi', ['invoice' => $invoice->invoice]) }}" target="_blank" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2" title="Print Resi">
                        <div class="w-5 text-white">
                            <svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.192"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V11C20.6569 11 22 12.3431 22 14V18C22 19.6569 20.6569 21 19 21H5C3.34314 21 2 19.6569 2 18V14C2 12.3431 3.34315 11 5 11V5ZM5 13C4.44772 13 4 13.4477 4 14V18C4 18.5523 4.44772 19 5 19H19C19.5523 19 20 18.5523 20 18V14C20 13.4477 19.5523 13 19 13V15C19 15.5523 18.5523 16 18 16H6C5.44772 16 5 15.5523 5 15V13ZM7 6V12V14H17V12V6H7ZM9 9C9 8.44772 9.44772 8 10 8H14C14.5523 8 15 8.44772 15 9C15 9.55228 14.5523 10 14 10H10C9.44772 10 9 9.55228 9 9ZM9 12C9 11.4477 9.44772 11 10 11H14C14.5523 11 15 11.4477 15 12C15 12.5523 14.5523 13 14 13H10C9.44772 13 9 12.5523 9 12Z" fill="#ffffff"></path> </g></svg>
                        </div>
                    </a>
                    <a href="#" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2" title="Print Invoice QR">
                        <div class="w-5 text-white">
                            <svg fill="#ffffff" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>paper</title> <path d="M0 32l4-4 4 4 4-4 4 4 4-4 4 4 4-4 4 4v-25.984q0-2.496-1.76-4.256t-4.224-1.76h-20q-2.496 0-4.256 1.76t-1.76 4.256v25.984zM4 22.016v-16q0-0.832 0.576-1.408t1.44-0.608h20q0.8 0 1.408 0.608t0.576 1.408v16l-4 4-4-4-4 4-4-4-4 4zM8 18.016h16v-2.016h-16v2.016zM8 14.016h16v-2.016h-16v2.016zM8 10.016h16v-2.016h-16v2.016z"></path> </g></svg>
                        </div>
                    </a>
                    <a href="{{ route('invoice.edit', ['invoice' => $invoice->invoice]) }}" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2" title="Print Invoice QR">
                        Edit Invoice
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    {{-- Start Form End --}}

    {{-- Vendor Invoice Start --}}
    <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
        <p class="mb-2 font-medium">Vendor Invoice</p>
        {{-- @if (!$invoice->invoiceVendor()->count() > 0)
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-100" role="alert">
            <span class="font-medium">Warning!</span> Vendor Invoice Set Empty.
        </div>
        @endif --}}
        
        <div class="relative overflow-x-auto">
            <table id="vendor-invoice" class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
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
                            Total Vendor
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($invoice->vendors["vendor"] as $key => $value)
                    <tr class="bg-white border-b">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $key + 1 }}
                        </th>
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
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>
            {{-- <p class="mt-5">Total Harga Vendor : <span class="font-medium">Rp. {{ number_format($invoice->vendors["total_harga"]) ?? 0 }}</span></p> --}}
        </div>

        <div class="mt-5">
            <a href="#" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Kelola Vendor</a>
        </div>
    </div>
    {{-- Vendor Invoice End --}}

    {{-- Tracking Invoice Start --}}
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <p class="mb-2 font-medium">Tracking Invoice</p>
        <p class="mb-4">Tracking Id : {{ $invoice->invoiceTracking->tracking_guid }}</p>
        <div class="relative overflow-x-auto">
            <table id="tracking-list">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Person</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->invoiceTracking->tracking as $key => $tracking)
                        <tr class="hover:bg-gray-50">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $tracking["status"] }}</td>
                            <td>{{ $tracking["location"] }}</td>
                            <td>{{ $tracking["deskripsi"] }}</td>
                            <td>{{ $tracking["person"] }}</td>
                            <td>{{ date('H:i d M Y', strtotime($tracking["date"])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            <a href="#" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Track Invoice</a>
        </div>
    </div>
    {{-- Tracking Invoice End --}}

    {{-- History Invoice Start --}}
    <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
        <p class="mb-2 font-medium">History Invoice</p>
        <div class="relative overflow-x-auto">
            <table id="track-history">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Massage</th>
                        <th>Person</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->history as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item["action"] }}</td>
                            <td>{{ $item["keterangan"] }}</td>
                            <td>{{ $item["person"] }}</td>
                            <td>{{ date('H:i:s, d M Y', strtotime($item["date"])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Massage</th>
                        <th>Person</th>
                        <th>Date</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- History Invoice End --}}

    {{-- Invoice Batal Start --}}
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <p class="font-bold">Hapus Invoice</p>
        <p class="text-sm">invoice ini akan dihapus secara <span class="font-medium">permanen</span></p>
        <div class="flex justify-center mt-3">
            <button id="btnDelete" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                Hapus Invoice Ini
            </button>
        </div>
    </div>
    {{-- Invoice Batal End --}}

</main>
@endsection
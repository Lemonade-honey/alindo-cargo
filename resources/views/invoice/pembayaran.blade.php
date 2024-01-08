@extends('layout.app')

@push('style')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
@endpush

@push('script')
<script>
    file_input.onchange = evt => {
        const [file] = file_input.files
        const target = document.getElementById("display-img")
        target.firstChild?.remove()

        if (file) {
            const img = document.createElement('img')
            img.setAttribute("class", "h-128 w-96");
            img.setAttribute("src", URL.createObjectURL(file))

            target.append(img)
        }
    }
</script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="mb-3">
        <h1 class="text-2xl">Pembayaran Invoice</h1>
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
                    <a href="{{ route('invoice.detail', ['invoice' => $invoice->invoice]) }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        {{ $invoice->invoice }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Pembayaran</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<div class="grid grid-cols-1 gap-3">
    <div class="w-full p-4 border border-gray-100 rounded-lg shadow">
        <div class="flex mb-3 justify-between">
            <h1 class="font-medium">Pembayaran Invoice {{ $invoice->invoice }}</h1>
            <p>Last Update : {{ date("H:i, d M Y", strtotime($invoice->invoiceCost->updated_at)) }}</p>
        </div>
        <p class="mb-10">Total Biaya : Rp. {{ number_format($invoice->invoiceCost->biaya_total) }}</p>
        <form action="#" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="metode" class="block mb-2 text-sm font-medium text-gray-900">Metode Pembayaran</label>
                <select name="metode" id="metode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="cash" @selected($invoice->invoiceCost->metode == "Cash")>Cash</option>
                    <option value="kartu" @selected($invoice->invoiceCost->metode == "Kartu")>Kartu</option>
                    <option value="transfer" @selected($invoice->invoiceCost->metode == "Transfer")>Transfer</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status Pembayaran</label>
                <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="belum bayar" @selected($invoice->invoiceCost->status == "belum bayar")>Belum Bayar</option>
                    <option value="belum lunas" @selected($invoice->invoiceCost->status == "belum lunas")>Belum Lunas</option>
                    <option value="lunas" @selected($invoice->invoiceCost->status == "lunas")>Lunas</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Keterangan (Optional)</label>
                <textarea name="deskripsi" id="deskripsi" cols="30" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ $invoice->invoiceCost->deskripsi }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Bukti Gambar</label>
                <a href="{{ route('view.transaksi', ['file' => $invoice->invoiceCost->bukti]) }}" class="text-blue-500 hover:underline" target="_blank">{{ $invoice->invoiceCost->bukti }}</a>
            </div>
            <div class="mb-6">
                <label for="bukti" class="block mb-2 text-sm font-medium text-gray-900">Upload Bukti Pembayaran (max 6MB)</label>
                <input type="file" name="bukti" id="file_input" accept="image/jpeg, image/png, application/pdf">
                <div id="display-img" class="mt-5"></div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('invoice.detail', ['invoice' => $invoice->invoice]) }}" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">Detail Invoice</a>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Set Pembayaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
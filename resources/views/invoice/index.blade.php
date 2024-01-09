@extends('layout.app')

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Invoice</h1>
        <a href="{{ route('invoice.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah Invoice</a>
    </div>
</header>
<main>
    <div class="flex justify-between mb-2">
        <div class="flex items-center gap-3">
            <p class="text-sm">Filter Type</p>
            <select name="filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1">
                <option>Pembayaran : Lunas</option>
                <option>Pembayaran : Belum Bayar</option>
                <option>Invoice : Proses</option>
                <option>Invoice : Selesai</option>
                <option>Invoice : Batal</option>
            </select>
        </div>
        <div class="">
            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search..">
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Invoice
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tujuan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Pengirim
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Pembayaran
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $key + 1 }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $invoice->invoice }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $invoice->created_at }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice->tujuan }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice->InvoicePerson->pengirim }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice->InvoiceCost->status }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice->status }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('invoice.detail', ['invoice' => $invoice->invoice]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center gap-3 mt-4">
        <p class="text-sm">Per Page</p>
        <select name="filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1">
            <option>10</option>
            <option>50</option>
            <option>100</option>
        </select>
    </div>

    <div class="w-full mt-4">
        {{ $invoices->links() }}
    </div>

</main>
@endsection
@extends('layout.app')

@push('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#invoice-list').DataTable({ responsive: true });
        });

        const btnDelete = document.getElementById('btnDelete')
        btnDelete.addEventListener("click", function(){
            Swal.fire({
                title: "Are you sure?",
                text: "Laporan akan dihapus secara permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if(result.isConfirmed){
                    window.location = "{{ route('laporan.delete', ['tanggal' => $tanggal]) }}"
                }
            });
        })
    </script>
@endpush

@section('body')
<header class="w-full p-4 mb-5 border border-gray-100 shadow rounded-lg">
    <h1 class="text-xl font-medium capitalize">Detail Laporan</h1>
    <nav class="flex my-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('laporan') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Laporan
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">{{ date('F Y', strtotime($tanggal)) }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')


<div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="">
            <div class="mb-3 flex justify-between">
                <p>Total Income Invoice</p>
                <p>Rp. {{ number_format($laporan->statistik['total-harga-invoice']) }}</p>
            </div>
            <div class="mb-3 flex justify-between">
                <p>Total Vendor</p>
                <p>Rp. {{ number_format($laporan->statistik['total-harga-vendor']) }}</p>
            </div>
            <div class="mb-3 flex justify-between">
                <p>Total Profit</p>
                <p>Rp. {{ number_format($laporan->statistik['total-profit']) }}</p>
            </div>
        </div>
        <div class="">
            <div class="mb-3 flex justify-between">
                <p>Target bulan laporan ini</p>
                <p>{{ date('F Y', strtotime($laporan->statistik['target-tanggal'])) }}</p>
            </div>
            <div class="mb-3 flex justify-between">
                <p>Total Invoice</p>
                <p>{{ number_format($laporan->statistik['total-invoice']) }}</p>
            </div>
            <div class="mb-3 flex justify-between">
                <p><span class="text-yellow-400">Warning</span> status</p>
                <p>{{ $laporan->statistik['total-warning'] }}</p>
            </div>
        </div>

    </div>

    <div class="flex justify-end mt-5 gap-4">
        <button type="button" id="btnDelete" class="text-white bg-red-700 hover:bg-red-900 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Hapus Laporan</button>
        <a href="{{ route('laporan.detail.download', ['tanggal' => $tanggal]) }}" class="text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Download</a>
    </div>
</div>

<div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
    <div class="relative overflow-x-auto">
        <table id="invoice-list">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Resi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tujuan</th>
                    <th>Berat</th>
                    <th>Vendor</th>
                    <th>Total Vendor</th>
                    <th>Total Biaya</th>
                    <th>Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan->invoices as $key => $value)
                <tr class="hover:bg-gray-200">
                    <td>{{ $key + 1 }}</td>
                    <td class="font-medium">{{ $value->invoice }}</td>
                    <td><a href="{{ route('invoice.detail', ['resi' => $value->resi]) }}" class="hover:underline">{{ $value->resi }}</a></td>
                    <td>{{ date('H:i, d M Y', strtotime($value->created_at)) }}</td>
                    <td class="capitalize font-medium">
                        @if ($value->status == "proses")
                            <span class="text-blue-500">{{ $value->status }}</span>
                        @elseif($value->status == "selesai")
                            <span class="text-green-500">{{ $value->status }}</span>
                        @elseif($value->status == "warning")
                            <span class="text-yellow-500">{{ $value->status }}</span>
                        @else
                            <span class="text-red-500">{{ $value->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('invoice.pembayaran', ['resi' => $value->resi]) }}" class="{{ ($value->invoiceCost->status == 'lunas') ? 'text-green-500': 'text-red-500'}} font-medium hover:underline capitalize">{{ $value->invoiceCost->status }}</a>
                    </td>
                    <td>{{ $value->tujuan }}</td>
                    <td>{{ $value->invoiceData->berat }}</td>
                    <td>
                        <a href="{{ route('invoice.vendor', ['resi' => $value->resi]) }}" class="hover:underline">{{ $value->invoiceVendors->count() }}</a>
                    </td>
                    <td>
                        <a href="{{ route('invoice.vendor', ['resi' => $value->resi]) }}" class="hover:underline">Rp. {{ number_format($value->total_harga_vendor) }}</a>
                    </td>
                    <td>
                        Rp. {{ number_format($value->invoiceCost->biaya_total) }}
                    </td>
                    <td class="{{ ($value->profit < 1) ? 'text-red-500' : 'text-green-500' }}">Rp. {{ number_format($value->profit) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Resi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tujuan</th>
                    <th>Berat</th>
                    <th>Vendor</th>
                    <th>Total Vendor</th>
                    <th>Total Biaya</th>
                    <th>Profit</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
</div>

@endsection
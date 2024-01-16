<div>
    <div wire:loading class="w-full gap-3 p-4 border border-gray-100 shadow rounded-md mb-2">
        <div class="flex justify-center">
            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
        </div>
    </div>
    <div wire:loading.remove class="relative overflow-x-auto">
        <table id="invoice-list">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
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
                @foreach ($invoices as $key => $value)
                <tr class="hover:bg-gray-200">
                    <td>{{ $key + 1 }}</td>
                    <td><a href="{{ route('invoice.detail', ['invoice' => $value->invoice]) }}" class="hover:underline">{{ $value->invoice }}</a></td>
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
                        <a href="{{ route('invoice.pembayaran', ['invoice' => $value->invoice]) }}" class="{{ ($value->invoiceCost->status == 'lunas') ? 'text-green-500': 'text-red-500'}} font-medium hover:underline capitalize">{{ $value->invoiceCost->status }}</a>
                    </td>
                    <td>{{ $value->tujuan }}</td>
                    <td>{{ $value->invoiceData->berat }}</td>
                    <td>
                        <a href="{{ route('invoice.vendor', ['invoice' => $value->invoice]) }}" class="hover:underline">{{ $value->invoiceVendors->count() }}</a>
                    </td>
                    <td>
                        <a href="{{ route('invoice.vendor', ['invoice' => $value->invoice]) }}" class="hover:underline">Rp. {{ number_format($value->total_harga_vendor) }}</a>
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

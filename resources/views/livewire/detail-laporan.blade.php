<div>
    <div class="relative overflow-x-auto">
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

@extends('layout.app')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        $('#kota-tujuan').select2({
            placeholder: "Masukan kota tujuan"
        });
        $('#member-list').select2({
            placeholder: "Relasi Member"
        });

        $('#member-list').on('change', function(){
            var selectValue = $(this).val()

            $.ajax({
                url: '/api/costumerRelation/' + selectValue, // Gantilah dengan endpoint yang sesuai
                method: 'GET',
                success: function(data) {
                    // pengirim
                    $('#nama-pengirim').val(data[0].name)
                    $('#kontak-pengirim').val(data[0].kontak)

                    // penerima
                    $('#nama-penerima').val(data[1].name)
                    $('#kontak-penerima').val(data[1].kontak)
                    $('#alamat-penerima').val(data[1].alamat)

                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        })
    });

    const form_lock = document.getElementById('form-lock')
    const kota_tujuan = document.getElementById('kota-tujuan')
    const harga = document.getElementById('harga-kg')
    const berat = document.getElementById('berat')
    const biaya_kirim = document.getElementById('biaya-kirim')
    const total = document.getElementById('total-biaya')
    const btnTambah = document.getElementById('tambah-keterangan')

    function ml(tagName, props, nest) {
        var el = document.createElement(tagName);
        if(props) {
            for(var name in props) {
                if(name.indexOf("on") === 0) {
                    el.addEventListener(name.substr(2).toLowerCase(), props[name], false)
                } else {
                    el.setAttribute(name, props[name]);
                }
            }
        }
        if (!nest) {
            return el;
        }
        return nester(el, nest)
    }

    function nester(el, n) {
        if (typeof n === "string") {
            var t = document.createTextNode(n);
            el.appendChild(t);
        } else if (n instanceof Array) {
            for(var i = 0; i < n.length; i++) {
                if (typeof n[i] === "string") {
                    var t = document.createTextNode(n[i]);
                    el.appendChild(t);
                } else if (n[i] instanceof Node){
                    el.appendChild(n[i]);
                }
            }
        } else if (n instanceof Node){
            el.appendChild(n)
        }
        return el;
    }

    btnTambah.addEventListener('click', () => {
        const form = ml('div', {class: 'flex gap-2 items-center mb-5'}, [
            ml('input', {type: 'text', name: 'ket_lainnya[]', id:'keterangan', class: 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', placeholder: 'keteragan...', required:''}, ''),
            ml('input', {type: 'number', name: 'ket_biaya[]', id:'biaya-lainnya', class: 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5', placeholder: 'biaya..', required:''}, ''),
            ml('button', {type: 'button', id: 'hapus-keterangan', class: 'focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2'}, 'hapus')
        ])

        const target = document.getElementById('biaya-tambahan')
        target.append(form)
    })

    setInterval(() => {
        const btnHapus = document.querySelectorAll('#hapus-keterangan')
        btnHapus.forEach(element => {
            element.addEventListener('click', () => {
                element.parentElement.remove()
            })
        });
        

        if(form_lock.checked){
            const input = document.querySelectorAll("input[lock]")
            input.forEach(element => {
                element.readOnly  = true;
            });
            harga.value = kota_tujuan.options[kota_tujuan.selectedIndex].getAttribute('harga')
            // total.value = biaya_kirim.value
            
        }else{
            const input = document.querySelectorAll("input[lock]")
            input.forEach(element => {
                element.readOnly  = false;
            });
        }
        biaya_kirim.value = Number(harga.value * berat.value)


        let total_lainnya = 0
        const biaya_lainnya = document.querySelectorAll('#biaya-lainnya')
        biaya_lainnya.forEach(element => {
            total_lainnya = Number(total_lainnya) + Number(element.value)
        });
        if(total_lainnya == 0)
        {
            total.value = Number(biaya_kirim.value)
        }else{
            total.value = Number(biaya_kirim.value) + Number(total_lainnya)
        }
        
    }, 500);
</script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="mb-3">
        <h1 class="text-2xl">Edit Invoice {{ $invoice->invoice }}</h1>
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
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

<main>
    @include('include.flash')
    
    <form method="POST">
        @csrf    
        <div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
            <p class="mb-2 font-medium">Form setting</p>
            <div class="flex justify-between">
                <div class="">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="form-lock" id="form-lock" class="sr-only peer" @checked($invoice->invoiceData->form_setting["form-lock"])>
                        <div class="w-11 h-6 bg-gray-200 cursor-pointer rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900">Form Lock</span>
                    </label>
                </div>
                <div class="flex items-center gap-3 sm:w-1/3">
                    <p>Relasi</p>
                    <div class="w-full">
                        <select name="member-id" id="member-list" class="w-full">
                            <option></option>
                            @foreach ($hubungan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 border border-gray-200 rounded-lg shadow">
            <div class="w-full">
                <div class="mb-6">
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Kota Asal</label>
                    <select name="kota-asal" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option selected>Yogyakarta - Base</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Kota Tujuan</label>
                    <div wire:ignore>
                        <select name="kota-tujuan" id="kota-tujuan" class="w-full">
                            <option></option>
                            @foreach ($kotas as $kota)
                                <option value="{{ $kota->kota }}" harga="{{ $kota->harga }}" @selected(old('kota-tujuan') ?? $invoice->tujuan == $kota->kota)>{{ $kota->kota }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-xs text-gray-500"><span class="text-red-500">*</span> jika data tidak ada, set data kota pada menu kota</p>
                </div>
                <div class="mb-6">
                    <div class="flex gap-3">
                        <div class="w-full">
                            <label for="berat" class="block mb-2 text-sm font-medium text-gray-900">Berat</label>
                            <input type="number" name="berat" id="berat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('berat') ?? $invoice->invoiceData->berat }}">
                        </div>
                        <div class="w-full">
                            <label for="harga-kg" class="block mb-2 text-sm font-medium text-gray-900">Harga/Kg</label>
                            <input type="number" name="harga/kg" id="harga-kg" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('harga/kg') ?? $invoice->invoiceData->harga_kg }}">
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="keterangan-barang" class="block mb-2 text-sm font-medium text-gray-900">Keterangan Barang</label>
                    <input type="text" name="keterangan-barang" id="keterangan-barang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" min="1" required value="{{ old('keterangan-barang') ?? $invoice->invoiceData->kategori }}">
                </div>
                <div class="flex justify-between gap-4 mb-10">
                    <div class="w-full">
                        <label for="koli" class="block mb-2 text-sm font-medium text-gray-900">Koli</label>
                        <input type="number" name="koli" id="koli" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" min="1" required value="{{ old('koli') ?? $invoice->invoiceData->koli }}">
                    </div>
                    <div class="w-full">
                        <label for="pemeriksaan" class="block mb-2 text-sm font-medium text-gray-900">Pemeriksaan Barang</label>
                        <select name="pemeriksaan" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="0" @selected(old('pemeriksaan') ?? $invoice->invoiceData->pemeriksaan == 0)>Tidak</option>
                            <option value="1" @selected(old('pemeriksaan') ?? $invoice->invoiceData->pemeriksaan == 1)>Iya</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="mb-6">
                    <label for="nama-pengirim" class="block mb-2 text-sm font-medium text-gray-900">Nama Pengirim</label>
                    <input type="text" name="nama-pengirim" id="nama-pengirim" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('nama-pengirim') ?? $invoice->invoicePerson->pengirim }}">
                </div>
                <div class="mb-6">
                    <label for="kontak-pengirim" class="block mb-2 text-sm font-medium text-gray-900">Kontak Pengirim</label>
                    <input type="number" name="kontak-pengirim" id="kontak-pengirim" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="+62" required value="{{ old('kontak-pengirim') ?? $invoice->invoicePerson->kontak_pengirim }}">
                </div>
                <div class="mb-6">
                    <label for="nama-penerima" class="block mb-2 text-sm font-medium text-gray-900">Nama Penerima</label>
                    <input type="text" name="nama-penerima" id="nama-penerima" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('nama-penerima') ?? $invoice->invoicePerson->penerima }}">
                </div>
                <div class="mb-6">
                    <label for="kontak-penerima" class="block mb-2 text-sm font-medium text-gray-900">Kontak Penerima</label>
                    <input type="number" name="kontak-penerima" id="kontak-penerima" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="+62" required value="{{ old('kontak-penerima') ?? $invoice->invoicePerson->kontak_penerima }}">
                </div>
                <div class="mb-10">
                    <label for="alamat-penerima" class="block mb-2 text-sm font-medium text-gray-900">Alamat Penerima</label>
                    <textarea name="alamat-penerima" id="alamat-penerima" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap...">{{ old('alamat-penerima') ?? $invoice->invoicePerson->alamat }}</textarea>
                </div>
            </div>
            <div class="">
                <div class="mb-6">
                    <label for="biaya-kirim" class="block mb-2 text-sm font-medium text-gray-900">Biaya Kirim</label>
                    <input type="number" name="biaya-kirim" id="biaya-kirim" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Biaya Lainnya</label>
                    <button type="button" id="tambah-keterangan" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">Tambah</button>
                    <div id="biaya-tambahan">
                        @foreach ($invoice->invoiceCost->biaya_lainnya as $key => $value)
                        <div class="flex gap-2 items-center mb-5">
                            <input type="text" name="ket_lainnya[]" id="keterangan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $value["keterangan"] }}">
                            <input type="number" name="ket_biaya[]" id="biaya-lainnya" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $value["harga"] }}">
                            <button type="button" id="hapus-keterangan" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">Hapus</button>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-6">
                    <label for="total-biaya" class="block mb-2 text-sm font-medium text-gray-900">Total Biaya</label>
                    <input type="number" name="total-biaya" id="total-biaya" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">UPDATE</button>
                </div>
                <p class="text-sm text-right"><span class="text-red-500">*</span>pastikan data yang dimasukan benar</p>
            </div>
        </div>
    </form>
</main>
@endsection
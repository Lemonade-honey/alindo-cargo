@extends('layout.guest')

@push('head-data')
<meta name="description" content="Ekspedisi Alindo Cargo, cek pengiriman paling murah.">
<meta name="keywords" content="Alindo Cargo, pengiriman barang, cek harga, layanan pengiriman">
<meta name="author" content="Alindo Cargo">
@endpush

@section('body')
<main>
    <section class="w-full mt-20">
        <div class="mb-5">
            <h1 class="text-4xl font-medium mb-4 text-gray-600">Cek Harga</h1>
            <p class="text-gray-700 text-lg text-justify">Kami menawarkan harga pengiriman termurah untuk logistik</p>
        </div>
        <div class="">
            @livewire('price-alindo')
        </div>
    </section>
</main>
@endsection
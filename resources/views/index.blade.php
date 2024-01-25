@extends('layout.guest')

@push('head-data')
<meta name="description" content="Ekspedisi Alindo Cargo, solusi terpercaya untuk pengiriman barang dan logistik dengan harga murah dan terpecaya. Jangkauan luas ke seluruh Indonesia.">
<meta name="keywords" content="Alindo Cargo, pengiriman barang, logistik, cargo, layanan pengiriman">
<meta name="author" content="Alindo Cargo">
@endpush

@section('body')
<main class="">
    <section class="w-full flex justify-around items-center flex-wrap gap-5 sm:min-h-dvh mt-20 sm:mt-0" id="home">
        <div class="w-full lg:w-3/6">
            <img src="https://static.vecteezy.com/system/resources/previews/010/974/056/original/3d-courier-service-delivery-delivery-man-and-boxes-courier-or-delivery-service-men-characters-with-parcels-packages-boxes-3d-rendering-png.png" alt="">
        </div>
        <header class="max-w-xl text-center sm:text-left">
            <h1 class="text-gray-700 text-5xl mb-4 font-medium"><span class="text-yellow-400">Alindo</span> Cargo</h1>
            <p class="text-gray-700 text-xl">Kami Menyediakan Layanan Pengiriman Barang <strong>Termurah</strong> dan <span class="text-yellow-400 font-medium">Terpercaya</span>. Dapatkan Pengalaman Terbaik dalam <strong>Pengiriman Barang</strong> Bersama Kami.</p>

            <div class="flex justify-center sm:justify-end mt-5">
                <a href="#" class="text-gray-600 hover:text-white bg-yellow-300 hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-200 font-bold rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">hubungi kami sekarang</a>
            </div>
        </header>
    </section>
    <section class="mt-20 scroll-mt-28" id="service">
        <div class="mb-5">
            <h2 class="text-4xl font-medium mb-4 text-gray-600">Layanan Kami</h2>
            <p class="text-gray-700 text-lg text-justify">Kami menawarkan berbagai layanan pengiriman barang dan Kami siap mengantarkan barang Anda ke <span class="text-yellow-400 font-medium">seluruh pelosok Indonesia</span> dengan <strong>keamanan terjamin</strong>.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
            <div class="w-full p-4 border border-yellow-300 shadow-yellow-50 rounded-lg">
                <div class="">
                    <h3 class="text-lg font-medium uppercase mb-2">Lorem ipsum dolor sit amet.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem corrupti consequuntur corporis sapiente. Consequatur minima cum facere tenetur aut quae!</p>
                </div>
            </div>
            <div class="w-full p-4 border border-gray-300 shadow rounded-lg">
                <div class="">
                    <h3 class="text-lg font-medium uppercase mb-2">Lorem ipsum dolor sit.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem corrupti consequuntur corporis sapiente. Consequatur minima</p>
                </div>
            </div>
            <div class="w-full p-4 border border-gray-300 shadow rounded-l">
                <div class="">
                    <h3 class="text-lg font-medium uppercase mb-2">Lorem ipsum dolor sit amet consectetur.</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus voluptates eius, delectus a non optio.</p>
                </div>
            </div>
            <div class="w-full p-4 border border-yellow-300 shadow-yellow-50 rounded-lg">
                <div class="">
                    <h3 class="text-lg font-medium uppercase mb-2">Lorem, ipsum dolor.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, sit?</p>
                </div>
            </div>
        </div>
        <div class="mt-5 text-center">
            <p class="text-lg mb-4">Tertarik dengan Jasa Kami ?</p>
            <a href="#" class="text-gray-600 hover:text-white bg-yellow-300 hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-200 font-bold rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">hubungi kami sekarang</a>
        </div>
        
    </section>

    <section class="mt-40 sm:mt-60 scroll-mt-28 flex justify-around flex-wrap" id="about">
        <div class="max-w-xl mb-5">
            <h2 class="text-4xl font-medium mb-4 text-gray-600 sm:text-right">Tentang <span class="text-yellow-400">Kami</span></h2>
            <p class="text-lg text-gray-700 text-justify"><strong>Alindo Cargo</strong> adalah perusahaan yang berdedikasi dalam menyediakan solusi <strong>pengiriman barang</strong> yang <strong class="text-yellow-400">murah</strong> dan <span class="font-bold">terpercaya</span>. Dengan pengalaman bertahun-tahun, kami telah menjadi mitra terpercaya untuk kebutuhan <strong>logistik</strong> dan pengiriman Anda.</p>
        </div>
        <div class="relative w-full max-w-xl h-96">
            <iframe class="absolute top-0 left-0 w-full h-full rounded-lg" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d247.0714752089365!2d110.33477351740378!3d-7.774593487531369!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5766c12ff4f3%3A0x82a3e3a49c061c88!2sEkspedisi%20Jogja%20ALINDO%20%7C%20Cargo%20Alindo%20%7C%20Cargo%20Murah%20Jogjakarta%20%7C%20Cargo%20Jogja%20Kalimantan!5e0!3m2!1sen!2sid!4v1705588423319!5m2!1sen!2sid" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <section class="mt-40 sm:mt-60 scroll-mt-28 mb-10" id="contact">
        <div class="">
            <h2 class="text-4xl font-medium mb-4 text-gray-600 sm:text-center">Hubungi Kami</h2>
            <p class="text-lg text-gray-700 text-justify sm:text-center">Hubungi kami <span class="text-yellow-400 font-medium">sekarang</span> untuk mendapatkan <b>penawaran terbaik</b> dan layanan pengiriman barang yang memuaskan.</p>
        </div>
        <div class="flex justify-center gap-5 mt-5">
            <p>Email: info@alindocargo.com</p>
            <p>Telepon: 0349-3429-3483</p>
        </div>
        <div class="mt-8 text-center">
            <a href="#" class="text-gray-600 hover:text-white bg-yellow-300 hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-200 font-bold rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">hubungi kami sekarang</a>
        </div>
    </section>
</main>
<footer class="flex justify-center my-2">
    <p>&copy; 2024 Alindo Cargo.</p>
</footer>
@endsection
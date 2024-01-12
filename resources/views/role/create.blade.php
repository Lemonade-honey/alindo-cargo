@extends('layout.app')

@section('body')

<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="mb-3">
        <h1 class="text-2xl">Buat Role Baru</h1>
        <hr>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('role') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Roles
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Buat</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<form action="#" method="post">
    @csrf
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <h2 class="text-lg">Role</h2>
        <div class="mt-3">
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama Role</label>
            <input type="text" name="name" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('nama') }}">
        </div>
    </div>

    <div class="w-full p-4 border border-gray-100 shadow rounded-lg mt-3">
        <div class="mb-4">
            <h2 class="text-lg">Permission</h2>
            <p class="text-sm"><span class="text-yellow-400">Kuning</span> Permission penting</p>
            <p class="text-sm"><span class="text-red-500">Merah</span> Permission sangat penting</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Invoice Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice" value="invoice">
                    <label for="invoice" class="cursor-pointer text-sm">Invoice</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice-kelola" value="invoice-kelola">
                    <label for="invoice-kelola" class="cursor-pointer text-yellow-400 text-sm">Kelola Invoice</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice-pembayaran" value="invoice-pembayaran">
                    <label for="invoice-pembayaran" class="cursor-pointer text-sm">Invoice Pembayaran</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice-tracking" value="invoice-tracking">
                    <label for="invoice-tracking" class="cursor-pointer text-sm">Invoice Tracking</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice-status" value="invoice-status">
                    <label for="invoice-status" class="cursor-pointer text-yellow-400 text-sm">Invoice Status</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="invoice-delete" value="invoice-delete">
                    <label for="invoice-delete" class="cursor-pointer text-red-500 text-sm">Invoice Delete</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Vendor Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="vendor" value="vendor">
                    <label for="vendor" class="cursor-pointer text-sm">Vendor</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="vendor-kelola" value="vendor-kelola">
                    <label for="vendor-kelola" class="cursor-pointer text-yellow-400 text-sm">Kelola Vendor</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Kota Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="kota" value="kota">
                    <label for="kota" class="cursor-pointer text-sm">Kota</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="kota-kelola" value="kota-kelola">
                    <label for="kota-kelola" class="cursor-pointer text-yellow-400 text-sm">Kelola Kota</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Laporan Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="laporan" value="laporan">
                    <label for="laporan" class="cursor-pointer text-sm">Laporan</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="laporan-kelola" value="laporan-kelola">
                    <label for="laporan-kelola" class="cursor-pointer text-yellow-400 text-sm">Kelola Laporan</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">User Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="user" value="user">
                    <label for="user" class="cursor-pointer text-red-500 text-sm">User</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="user-kelola" value="user-kelola">
                    <label for="user-kelola" class="cursor-pointer text-red-500 text-sm">User Kelola</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Costumer Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="costumer" value="costumer">
                    <label for="costumer" class="cursor-pointer text-sm">Costumer</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="costumer-kelola" value="costumer-kelola">
                    <label for="costumer-kelola" class="cursor-pointer text-sm">Costumer Kelola</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Role Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="role" value="role">
                    <label for="role" class="cursor-pointer text-red-500 text-sm">Role</label>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="role-kelola" value="role-kelola">
                    <label for="role-kelola" class="cursor-pointer text-red-500 text-sm">Role Kelola</label>
                </div>
            </div>
            <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Dashboard Permission</label>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="permission[]" id="chart-statistik-invoice" value="chart-statistik-invoice">
                    <label for="chart-statistik-invoice" class="cursor-pointer text-sm">Chart Statistik Invoice</label>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full p-4 border border-gray-100 shadow rounded-lg mt-5">
        <p class="text-xs">Pastikan data dan permission yang dimasukan sudah benar serta sesuai ketentuan</p>
        <div class="flex justify-center mt-3">
            <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">POST</button>
        </div>
    </div>
</form>
@endsection
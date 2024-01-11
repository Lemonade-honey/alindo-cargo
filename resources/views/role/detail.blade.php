@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            $('#user-list').DataTable({ responsive: true });

            $('#users').select2({
                placeholder: "email atau nama user"
            });
        });

        const btnDelete = document.querySelectorAll('#btnDelete')
        btnDelete.forEach(e => {
            e.addEventListener("click", () => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Role akan dihapus secara permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.isConfirmed){
                        Swal.fire({
                            title: "Masukan nama Role dibawah ini",
                            input: "text",
                            inputLabel: "{{ $role->name }}",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Hapus",
                            inputValidator: (value) => {
                                if (!value) return "Masukkan nama Role!";
                                if (value != "{{ $role->name }}") return "Data tidak sama";

                                if(value == "{{ $role->name }}"){
                                    window.location = "{{ route('role.delete', ['role' => $role->name]) }}";
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
        <h1 class="text-2xl">Detail Role</h1>
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
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 capitalize">{{ $role->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

<div class="flex flex-col gap-3">
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <div class="flex justify-between mb-5">
            <div class="">
                <h2 class="text-lg">Role dengan User</h2>
                <p class="text-xs">Akun yang terikat dengan role ini, 1 akun 1 role</p>
            </div>
            <button type="button" data-modal-target="modal-tambah-user" data-modal-toggle="modal-tambah-user" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">Tambah</button>
        </div>

        <div id="modal-tambah-user" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Tambah User ke dalam Role
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-tambah-user">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <form action="{{ route('role.user.add', ['role' => $role->name]) }}" method="post">
                            @csrf
                            <div class="mb-6">
                                <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900">Name - Email</label>
                                <select name="user-id" id="users" class="w-full">
                                    <option></option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button> 
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table id="user-list">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($role->users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <a href="{{ route('role.user.delete', ['role' => $role->name, 'id' => $user->id]) }}" class="text-red-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <form action="{{ route('role.nama.post', ['role' => $role->name]) }}" method="post">
        @csrf
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <h2 class="text-lg">Role</h2>
            <p class="text-xs">Ganti nama role, pastikan nama yang belum terdaftar</p>
            <div class="mt-3">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama Role</label>
                <input type="text" name="name" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('nama') ?? $role->name }}">
            </div>
            <div class="flex justify-end mt-3">
                <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">UPDATE</button>
            </div>
        </div>
    </form>
    
    <form action="{{ route('role.permission.post', ['role' => $role->name]) }}" method="post">
        @csrf
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <div class="mb-4">
                <h2 class="text-lg">Permission</h2>
                <p class="text-sm"><span class="text-yellow-300">Kuning</span> Permission penting</p>
                <p class="text-sm"><span class="text-red-500">Merah</span> Permission sangat penting</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Invoice Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="invoice" value="invoice" @checked(in_array("invoice", $role->permission))>
                        <label for="invoice" class="cursor-pointer text-sm">Invoice</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="invoice-pembayaran" value="invoice-pembayaran" @checked(in_array("invoice-pembayaran", $role->permission))>
                        <label for="invoice-pembayaran" class="cursor-pointer text-sm">Invoice Pembayaran</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="invoice-tracking" value="invoice-tracking" @checked(in_array("invoice-tracking", $role->permission))>
                        <label for="invoice-tracking" class="cursor-pointer text-sm">Invoice Tracking</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="invoice-status" value="invoice-status" @checked(in_array("invoice-status", $role->permission))>
                        <label for="invoice-status" class="cursor-pointer text-yellow-300 text-sm">Invoice Status</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="invoice-delete" value="invoice-delete" @checked(in_array("invoice-delete", $role->permission))>
                        <label for="invoice-delete" class="cursor-pointer text-red-500 text-sm">Invoice Delete</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Vendor Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="vendor" value="vendor" @checked(in_array("vendor", $role->permission))>
                        <label for="vendor" class="cursor-pointer text-sm">Vendor</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="vendor-kelola" value="vendor-kelola" @checked(in_array("vendor-kelola", $role->permission))>
                        <label for="vendor-kelola" class="cursor-pointer text-yellow-300 text-sm">Kelola Vendor</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Kota Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="kota" value="kota" @checked(in_array("kota", $role->permission))>
                        <label for="kota" class="cursor-pointer text-sm">Kota</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="kota-kelola" value="kota-kelola" @checked(in_array("kota-kelola", $role->permission))>
                        <label for="kota-kelola" class="cursor-pointer text-yellow-300 text-sm">Kelola Kota</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Laporan Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="laporan" value="laporan" @checked(in_array("laporan", $role->permission))>
                        <label for="laporan" class="cursor-pointer text-sm">Laporan</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="laporan-kelola" value="laporan-kelola" @checked(in_array("laporan-kelola", $role->permission))>
                        <label for="laporan-kelola" class="cursor-pointer text-yellow-300 text-sm">Kelola Laporan</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">User Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="user" value="user" @checked(in_array("user", $role->permission))>
                        <label for="user" class="cursor-pointer text-sm">User</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="user-kelola" value="user-kelola" @checked(in_array("user-kelola", $role->permission))>
                        <label for="user-kelola" class="cursor-pointer text-red-500 text-sm">User Kelola</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Costumer Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="costumer" value="costumer" @checked(in_array("costumer", $role->permission))>
                        <label for="costumer" class="cursor-pointer text-sm">Costumer</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="costumer-kelola" value="costumer-kelola" @checked(in_array("costumer-kelola", $role->permission))>
                        <label for="costumer-kelola" class="cursor-pointer text-sm">Costumer Kelola</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Role Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="role" value="role" @checked(in_array("role", $role->permission))>
                        <label for="role" class="cursor-pointer text-red-500 text-sm">Role</label>
                    </div>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="role-kelola" value="role-kelola" @checked(in_array("role-kelola", $role->permission))>
                        <label for="role-kelola" class="cursor-pointer text-red-500 text-sm">Role Kelola</label>
                    </div>
                </div>
                <div class="px-3 py-1 border border-gray-100 shadow rounded-sm">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Dashboard Permission</label>
                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="chart-statistik-invoice" value="chart-statistik-invoice" @checked(in_array("chart-statistik-invoice", $role->permission))>
                        <label for="chart-statistik-invoice" class="cursor-pointer text-sm">Chart Statistik Invoice</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-xs text-right">Pastikan data dan permission yang dimasukan sudah benar serta sesuai ketentuan</p>
                <div class="flex justify-end mt-2">
                    <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">UPDATE</button>
                </div>
            </div>
        </div>
    </form>
    
    <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
        <h2 class="text-lg text-red-500">Hapus Role ini</h2>
        <p class="text-sm">role akan dihapus secara <span class="font-medium">permanen</span> dan semua akun yang terikat dengan role ini akan hilang <span class="font-medium">rolenya</span></p>
        <div class="flex justify-center mt-3">
            <button type="button" id="btnDelete" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">DELETE</button>
        </div>
    </div>
</div>
@endsection
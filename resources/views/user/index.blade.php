@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        $('#user-list').DataTable({ responsive: true });
    });

    const btnCreate = document.getElementById('createBtn');
    btnCreate.addEventListener("click", () => {
        const formCreate = document.getElementById("form-create")
        formCreate.submit();
    })
</script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Users</h1>
        <button type="button" data-modal-target="modal-tambah-user" data-modal-toggle="modal-tambah-user" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</button>
    </div>
</header>

<div id="modal-tambah-user" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah User
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
                <form action="#" method="post" id="form-create">
                    @csrf
                    <div class="mb-6">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="nama" id="nama" name="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('nama') }}">
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('password') }}">
                    </div>
                    <div class="mb-6">
                        <label for="repassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" id="repassword" name="repassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b ">
                <button type="button" id="createBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
            </div>
        </div>
    </div>
</div>

@include('include.flash')
<div class="p-4 w-full shadow border border-gray-100 rounded-lg">
    <div class="relative overflow-x-auto">
        <table id="user-list">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created at
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $item)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $key + 1 }}
                    </th>
                    <td class="px-6 py-4 capitalize">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->email }}
                    </td>
                    <td class="px-6 py-4 capitalize">
                        @if ($item->block)
                            <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-5 py-1 rounded">block</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-5 py-1 rounded">aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ date('H:i, d M Y', strtotime($item->created_at)) }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('user.detail', ['uid' => $item->user_uid]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
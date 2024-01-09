@extends('layout.app')

@push('style')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('script')
    <script>
        const btnDelete = document.querySelectorAll('#btnDelete')
        btnDelete.forEach(e => {
            e.addEventListener("click", () => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Akun akan dihapus secara permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.isConfirmed){
                        Swal.fire({
                            title: "Masukan email dibawah ini",
                            input: "text",
                            inputLabel: "{{ $user->email }}",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Hapus",
                            inputValidator: (value) => {
                                if (!value) return "Masukkan email akun ini!";
                                if (value != "{{ $user->email }}") return "Data tidak sama";

                                if(value == "{{ $user->email }}"){
                                    window.location = "{{ route('user.delete', ['id' => $user->id]) }}";
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
        <h1 class="text-2xl">User Detail</h1>
        <hr>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('user') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    User
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">{{ $user->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
</header>

@include('include.flash')

@if ($user->block)
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
    <span class="font-medium">Alert!</span> Akun ini telah diblokir
</div>
@endif

<main>
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <h2 class="text-xl">Detail Akun {{ $user->id == 1 ? '--- Super Admin ---' : '' }}</h2>
            <p class="text-sm mb-4">Terakhir diubah pada tanggal: {{ $user->updated_at }}</p>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Akun UID</label>
                <input type="text" class="block w-full p-2 text-gray-500 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500" value="{{ $user->user_uid }}" disabled>
            </div>

            <form action="{{ route('user.akun.post', ['uid' => $user->user_uid]) }}" method="post">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                    <input type="text" name="nama" id="name" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500" value="{{ $user->name }}">
                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" name="email" id="name" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500" value="{{ $user->email }}">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">UPDATE</button>
                </div>
            </form>
        </div>
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <h2 class="text-xl">Ganti Password</h2>
            <p class="text-sm mb-4">Masukan kata sandi baru untuk akun ini</p>
            <form method="post" action="{{ route('user.password.post', ['uid' => $user->user_uid]) }}">
                @csrf
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 capitalize">password baru</label>
                    <input type="password" name="password" id="password" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500" value="">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">UPDATE</button>
                </div>
            </form>
        </div>
        @if ($user->id != 1)
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <h2 class="text-xl">Block Akun ini</h2>
            <p class="text-sm mb-4">Jika <span class="font-medium">dinyalakan</span>, akun ini akan di non-aktifkan</p>
            <form action="{{ route('user.block.post', ['uid' => $user->user_uid]) }}" method="post">
                @csrf
                <div class="mb-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="block" class="sr-only peer" @checked($user->block)>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900">Block akun ini</span>
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">UPDATE</button>
                </div>
            </form>
        </div>
        <div class="w-full p-4 border border-gray-100 shadow rounded-lg">
            <h2 class="text-xl text-red-600">Hapus Akun ini</h2>
            <p class="text-sm mb-4">Akun ini akan dihapus semua datanya secara <span class="font-medium">permanen</span> dan tidak dapat dipulihkan</p>
            <div class="flex justify-center">
                <button type="button" id="btnDelete" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">HAPUS</button>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection
@extends('layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#role-list').DataTable({ responsive: true });
        });
    </script>
@endpush

@section('body')
<header class="w-full p-4 border border-gray-200 shadow rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl">List Roles</h1>
        @can('role-kelola')
        <a href="{{ route('role.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">Tambah</a>
        @endcan
    </div>
</header>

@include('include.flash')

<div class="w-full my-2 p-4 border border-gray-100 rounded-lg shadow">
    <div class="relative overflow-x-auto">
        <table id="role-list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Role</th>
                    @can('role-kelola')
                    <th>Action</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
            @foreach ($roles as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    @can('role-kelola')
                    <td>
                        <a href="{{ route('role.detail', ['role' => $item->name]) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
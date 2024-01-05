@if ($errors->any())
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
    <span class="font-medium">Error!</span> {{ $errors->first() }}
</div>
@endif

@if (session()->has('info'))
<div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-100" role="alert">
    <span class="font-medium">Info!</span> {{ session()->get('info') }}
</div>
@endif

@if (session()->has('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
    <span class="font-medium">Success!</span> {{ session()->get('success') }}
</div>
@endif

@if (session()->has('error'))
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
    <span class="font-medium">Error!</span> {{ session()->get('error') }}
</div>
@endif

@if (session()->has('warning'))
<div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-100" role="alert">
    <span class="font-medium">Warning!</span> {{ session()->get('warning') }}
</div>
@endif
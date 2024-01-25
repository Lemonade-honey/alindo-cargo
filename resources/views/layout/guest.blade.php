<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @stack('head-data')
    @stack('style')
    <title>{{ $title ?? config("app.name") }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-primary-bg">
    @include('include.navbar')
    <div class="mx-5 sm:mx-10">
        @yield('body')
    </div>
    @stack('script')
</body>
</html>
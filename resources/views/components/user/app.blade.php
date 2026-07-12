<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'Page Title' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('components.user.partials.styles')
    @stack('styles')
</head>

<body>

    <body class="tyn-body">
        <div class="tyn-root">
            <livewire:components.user.partials.header />
            {{ $slot }}
        </div>
        @include('components.user.partials.scripts')
        @stack('scripts')
    </body>

</html>

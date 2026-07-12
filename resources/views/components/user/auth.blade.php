<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    @include('components.user.partials.styles')
    @stack('styles')
</head>

<body class="tyn-body">
    <div class="tyn-root">
        <div class="tyn-content tyn-auth tyn-auth-centered">
            <div class="container">
                {{ $slot }}
            </div>
        </div>
    </div>
    @include('components.user.partials.scripts')
    @stack('scripts')
</body>

</html>

<html>
    <head>
        <title>{{ ($title ?? 'TrackIt') . ' - ' . config('app.name') }}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        @livewireStyles
    </head>
    <body class="bg-gray-200">
        <div class="container max-w-3xl mx-auto bg-white py-4 px-4">
            @yield('content')
        </div>

        @livewireScripts
    </body>
</html>
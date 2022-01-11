@props(['links'])

@php
    $title = [
        'home' => 'Zur Übersicht',
        'statistics' => 'Statistik',
        'settings' => 'Einstellungen',
        'chart' => 'Chart',
        'timeline' => 'Timeline',
        'toplist' => 'Top Artikel',
    ];
@endphp

<div class="flex items-center justify-between pb-4 border-black border-b-2">
    <h1 class="text-xl">{{ $slot }}</h1>

    <div class="space-x-3">
        @foreach ($links as $link)
            <x-link href="{{ route($link) }}">{{ $title[$link] }}</x-link>
        @endforeach
    </div>
</div>
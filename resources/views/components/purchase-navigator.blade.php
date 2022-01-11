@props([
    'direction',
    'purchase'
])

@if ($purchase)
    <a href="{{ route('purchase', [$purchase['id']]) }}">
        @if ($direction == 'next')
            <x-heroicon-o-arrow-right class="h-4 w-4 text-black" />
        @elseif ($direction == 'prev')
            <x-heroicon-o-arrow-left class="h-4 w-4 text-black" />
        @endif
    </a>
@endif
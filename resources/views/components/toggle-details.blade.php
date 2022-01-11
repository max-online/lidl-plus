@props([
    'details' => $details
])

<div {{ $attributes->merge(['class' => 'flex text-sm cursor-pointer items-center bg-blue-700 px-4 py-2 text-white rounded hover:bg-blue-700']) }}>
    @if($details)
        <x-heroicon-s-minus-circle class="h-4 w-4 text-white mr-1"/>
    @else
        <x-heroicon-s-plus-circle class="h-4 w-4 text-white mr-1"/>
    @endif
    <div>{{ $details ? 'Zusammenfassen' : 'Details anzeigen' }}</div>
</div>
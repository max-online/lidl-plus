@props([
    'type' => 'text',
    'error' => $error ?? '',
])

<input type="{{ $type }}"
 {{ $attributes->merge(['class' => 'form-input w-48 border rounded-md focus:outline-none focus:border-blue-300 ' . ($error ? 'border-red-300' : 'border-gray-300')]) }}>
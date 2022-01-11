<button {{ $attributes->merge(['class' => 'text-sm cursor-pointer bg-blue-700 px-4 py-2 text-white rounded hover:bg-blue-700']) }}>
    {{ $slot }}
</button>
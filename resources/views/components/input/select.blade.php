<select {{ $attributes->merge(['class' => 'text-sm form-select block w-48 border border-gray-300 rounded-md focus:outline-none focus:border-blue-300']) }}>
    {{ $slot }}
</select>
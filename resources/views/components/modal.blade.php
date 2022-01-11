<div x-data="{ show: @entangle($attributes->wire('model')) }" 
    x-show="show" 
    x-on:keydown.escape.window="show = false"
    class="absolute inset-0 flex items-center justify-center"
>
    <div class="fixed inset-0 bg-gray-800 opacity-80" x-on:click="show = false"></div>

    <div class="fixed bg-white m-auto max-w-lg w-full max-h-full overflow-auto rounded-md">
        <div class="p-5 text-xl bg-gray-100 border-b border-black relative">
            {{ $title }}

            <div class="absolute top-3 right-3">
                <div class="text-right cursor-pointer" x-on:click="show = false">
                    <x-heroicon-o-x class="h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="p-5">
            {{ $body }}
        </div>

        <div class="p-5 flex justify-end bg-gray-100 border-t border-black">
            {{ $footer }}
        </div>
    </div>
</div>
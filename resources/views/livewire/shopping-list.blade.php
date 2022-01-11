<div>
    <div>
        <x-nav :links="['toplist' ,'home']">
            Deine Einkaufsliste
        </x-nav>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        @foreach ($selectedCategories as $category)
            <div>
                <h3 class="my-2 text-lg underline">{{ $category }}</h3>
                <div class="space-y-1">
                    @foreach ($topArticles->where('category', $category) as $article)
                        <div class="flex items-center">
                            <label class="flex items-center w-52">
                                <input type="checkbox" wire:model="checkedArticles.{{ $article['name'] }}" wire:key="{{ $article['name'] }}" class="form-checkbox focus:ring-0" value="{{ $article['name'] }}">
                                <span class="ml-3">{{ $article['name'] }}</span>
                            </label>
                            <div wire:click="removeItem('{{ $article['name'] }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
                <input 
                 wire:keydown.enter="addItem('{{ $category }}')" 
                 wire:model.lazy="newArticle.{{ $category }}" 
                 @class(['form-input w-48 p-1 my-1 text-xs border focus:outline-none focus:border-blue-300', 'border-red-500' => $errors->has('category.' . $category)])
                >
                @error('category.' . $category)<div class="text-red-500 text-xs">{{ $message }}</div> @enderror
            </div>
        @endforeach
    </div>

    <div class="flex justify-end mt-8">
        <button wire:click="export" class="px-2 py-1 bg-gray-300 border border-gray-600">Einkaufszettel ausdrucken</button>
    </div>
</div>

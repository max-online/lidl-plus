<div>
    <x-nav :links="['statistics', 'home']">
        Top Listen
    </x-nav>

    <div class="mt-6 divide-black" x-data="{ topList: true, topListByCategory: false }">
        <div class="flex justify-start space-x-2 mb-6 pl-3 border-b border-black">
            <div x-on:click="topList = true; topListByCategory = false"
             class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white" 
             :class="topList ? '-mb-px' : 'bg-gray-100 opacity-50 hover:opacity-100'"
             x-on:>
             Top Artikel
            </div>
            <div x-on:click="topList = false; topListByCategory = true"
             class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white" 
             :class="topListByCategory ? '-mb-px' : 'bg-gray-100 opacity-50 hover:opacity-100'"
             >
             Top Artikel nach Kategorie
            </div>
        </div>
        <div x-show="topList" class="mt-4">
            <div class="my-2">
                <x-button wire:click="toggleMode">
                    @if ($mode == 'price')
                        Anzahl anzeigen
                    @else
                        Preis anzeigen
                    @endif
                </x-button>
            </div>
            <div class="flex">
                @if ($topArticles)
                    @foreach ($topArticles->chunk(10) as $chunk)
                        <table class="w-1/3 text-sm">
                            <tbody>
                                @foreach ($chunk as $name => $article)
                                    <tr>
                                        <td @class(['p-2 bg-gray-200 border-t border-b border-r border-black text-center font-semibold', 'border-l' => $loop->parent->iteration == 1 ])>
                                            {{ $loop->iteration + ($loop->parent->index * $chunk->count()) }}
                                        </td>
                                        <td class="p-2 border border-black">{{ \Str::limit($name, 16) }}</td>
                                        <td class="p-2 border-t border-b border-r border-black text-right">
                                            @if ($mode == 'price')
                                                {{ money($article['total']/100) }} €
                                            @else
                                                {{ $article['count'] }} x
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach                    
                @endif
            </div>
        </div>
        
        <div x-show="topListByCategory" class="mt-4">
            <div class="flex space-x-6 mb-3">
                <x-input.select wire:model="selectedCategory">
                    <option value="">Kategorie wählen..</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-input.select>
    
                @if ($selectedCategory)
                    <button wire:click="modifyLimit" class="items-center text-sm cursor-pointer bg-blue-700 px-4 py-2 text-white rounded hover:bg-blue-700'">
                        Top Liste: {{ $limit == 5 ? ' +' : ' -' }} 5
                    </button>
                @endif
            </div>
    
            @include('partials.loading')
    
            @if ($selectedCategory)
                <div class="grid grid-cols-3 gap-4" wire:loading.remove>
                    @foreach ($articlesByMonth as $date => $articles)
                        @php($date = Carbon\Carbon::parse($date))
                        <div class="border border-black rounded-md">
                            <div class="flex justify-between items-center p-3 mb-2 bg-gray-200 rounded-t-md border-b border-black">
                                <h3 class="text-lg">{{ $date->translatedFormat('F') . ' ' . $date->format('Y') }}</h3>
                                <div class="text-sm text-gray-600">{{ money(collect($articles)->sum()/100) }} €</div>
                            </div>
    
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="p-2 text-left">Artikel</th>
                                        <th class="p-2 text-right">Ausgaben</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $article => $price)
                                        <tr class="text-sm">
                                            <td class="py-1 px-2">{{ \Str::limit($article, 16) }}</td>
                                            <td class="py-1 px-2 text-right">
                                                {{ money($price/100) }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm italic" wire:loading.remove>
                    Wähle eine Kategorie.
                </div>
            @endif
        </div>

    </div>

</div>

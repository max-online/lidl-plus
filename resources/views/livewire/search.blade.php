<div class="my-4 pb-4 border-b-2 border-black">
    <div class="flex justify-between items-center">
        <div>
            Nach Artikel suchen:
        </div>
        <div class="flex items-center">
            <form wire:submit.prevent="searchArticles" class="flex items-center space-x-2 m-0 inline-block">
                <div class="relative">
                    <x-input.text wire:model.defer="search" type="text" placeholder="Artikel" />
                    @if ($search)
                        <button type="button" class="absolute right-0 top-1 mt-2 mr-2 focus:outline-none transform duration-300 focus:scale-125">
                            <x-heroicon-o-x wire:click="$set('search', '')" class="h-5 w-5"/>
                        </button>
                    @endif
                </div>
                <button type="submit">
                    <x-heroicon-o-search class="h-5 w-5"/>
                </button>
            </form>
        </div>
    </div>

    @if ($search)
        <div class="mt-4">
        @if (empty($results))
            <div class="flex items-center text-sm italic mb-4 bg-red-100 text-red-600 px-5 py-2">
                <x-heroicon-s-x-circle class="h-4 w-4 fill-current mr-5"/>
                Keine Artikel gefunden.
            </div>
        @else
            <div class="flex items-center text-sm italic mb-4 bg-green-50 text-green-600 px-5 py-2">
                <x-heroicon-s-check-circle class="h-4 w-4 fill-current mr-5"/>
                {{ $results->reduce(fn($carry, $group) => $group['count'] + $carry) }} Artikel gefunden.
            </div>
            <div>
                <table>
                    <thead>
                        <tr class="text-sm text-semibold">
                            <th class="py-1 pr-8 text-left">Artikel</th>
                            <th class="py-1 pr-8 text-left">Preis</th>
                            <th class="py-1 pr-8 text-left">Einkauf vom</th>
                        </tr> 
                    </thead>
                    <tbody>
                        @foreach($results as $date => $articleGroup)
                            @foreach($articleGroup['articles'] as $article)
                                <tr @class(['text-sm', 'border-b border-gray-600' => $loop->last])>
                                    <td class="py-1 pr-8" title="{{ ($article->meta ? $article->meta : '') }}">{{ $article->name }}</td>
                                    <td class="py-1 pr-8">{{ money($article->price/100) }} €</td>
                                    <td class="py-1 pr-8 text-blue-300 cursor-pointer hover:underline">
                                        <a href="{{ url('purchase/' . $article->purchase->id) }}">{{ $article->purchase->date->germanFormat() }}</a>
                                    </td>
                                    <td class="py-1">
                                        <div>{{ $loop->first ? $date : '' }}</div>
                                        <div class="text-right text-gray-600">{{ $loop->last ? money($articleGroup['sum']/100) . ' €' : '' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot class="text-sm">
                        <tr class="border-t-2 border-black">
                            <td class="py-1 pr-8 font-semibold">Summe</td>
                            <td class="py-1 pr-8">{{ money($results->flatten()->sum('price')/100) }} €</td>
                            <td class="py-1 pr-8"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
        </div>
    @endif
</div>
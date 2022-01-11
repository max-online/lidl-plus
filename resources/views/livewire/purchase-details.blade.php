<div>
    <div>
        <x-nav :links="['home']">
            <div class="flex items-center space-x-3 pl-1">
                <x-purchase-navigator :direction="'prev'" :purchase="$purchases['prev']"></x-purchase-navigator>
                <div>Einkauf vom {{ $purchase->formattedDate }} - {{ $purchase->time }} Uhr </div>
                <x-purchase-navigator :direction="'next'" :purchase="$purchases['next']"></x-purchase-navigator>
            </div>
        </x-nav>
    </div>

    <div class="border-black border-b-2 pb-4 my-4">
        <table>
            <tr class="text-lg">
                <td class="py-1 pr-4">Gesamt:</td>
                <td class="py-1 text-right">{{ money($purchase->total/100) }} €</td>
            </tr>
            <tr class="text-sm text-gray-700">
                <td class="py-1 pr-4">Preisvorteil:</td>
                <td class="py-1 text-right">{{ money($purchase->savings/100) }} €</td>
            </tr>
        </table>
    </div>

    <div class="flex space-x-10">
        <div class="w-3/5">
            <x-input.select wire:model="selectedCategory" class="mb-3">
                <option value="">Kategorie wählen..</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-input.select>

            @include('partials.loading')

            <table class="w-full" wire:loading.remove>
                <tbody>
                    @foreach($articles as $article)
                        <tr @class(['text-sm', 'border-t border-gray-600' => (!$loop->first) && $article->category_name])>
                            <td class="px-3 py-1" title="{{ $article->meta ?? '' }}">{{ $article->name }}
                            </td>
                            <td class="px-3 py-1 text-right">{{ money($article->price/100) }} €</td>
                            <td class="px-3 py-1">{{ $article->category_name }}</td>
                        </tr>
                    @endforeach
                    @if ($purchase->bottle_deposit && ! $selectedCategory)
                        <tr class="text-sm border-t border-gray-600 text-blue-600">
                            <td class="px-3 py-1">Pfand</td>
                            <td class="px-3 py-1 text-right">{{ money($purchase->bottle_deposit/100) }} €</td>
                            <td class="px-3 py-1"></td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-black">
                        <td class="px-3 py-2">Summe</td>
                        <td class="px-3 py-2 text-right">
                            {{ money(($articles->sum('price') + ($selectedCategory ? 0 : $purchase->bottle_deposit))/100) }} €
                        </td>
                        <td class="px-3 py-2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="w-2/5">
            @include('partials.category-table')
        </div>
    </div>
</div>
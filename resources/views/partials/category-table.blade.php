<h2 class="font-semibold text-lg my-2">Auswertung {!! $titleSuffix ?? '' !!}</span></h2>

<table>
    <tbody>
        @foreach($byCategory as $categoryId => $sum)
            <tr>
                <td class="p-2 {{ (int) $selectedCategory === $categoryId ? 'font-semibold' : 'underline cursor-pointer' }}" 
                    wire:click="$set('selectedCategory', {{ $categoryId }})"
                >
                    {{ $categories->firstWhere('id', $categoryId)->name ?? 'Sonstiges' }}
                </td>
                <td class="p-2 text-right {{ (int) $selectedCategory === $categoryId ? 'font-semibold' : '' }}">
                    {{ money($sum/100) }} €
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
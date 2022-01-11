<div>
    <x-nav :links="['statistics', 'home']">
        Einkäufe - Timeline
    </x-nav>

    <div class="flex space-x-5 my-4">
        <div>
            <x-input.select wire:model="selectedCategory">
                <option value="">Kategorie wählen..</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-input.select>
        </div>
        <div>
            <x-input.select wire:model="selectedDate">
                <option value="">Zeitraum wählen..</option>
                @foreach($dates as $key => $month)
                <option value="{{ $key }}">{{ $month }}</option>
                @endforeach
            </x-input.select>
        </div>
    </div>

    @include('partials.loading')

    <div wire:loading.remove class="mt-6">
        @if ($purchases->isNotEmpty())
        <div class="text-right space-y-1">
            <div>
                <span>Ausgaben für {{ $categories->firstWhere('id', $selectedCategory)->name }}:</span>
                <span class="inline-block w-20">{{ money($purchases->reduce(fn($carry, $purchase) => $carry + $purchase->articles->sum('price'))/100) }} €</span>
            </div>
            <div>
                <span>Ausgaben im {{ $dates[$selectedDate]  }}:</span>
                <span class="inline-block w-20">{{ money($purchases->reduce(fn($carry, $purchase) => $carry + $purchase->total)/100) }} €</span>
            </div>
        </div>
        <div>
            <table class="w-full">
                <tr>
                    <td class="px-2 py-4 text-left font-semibold" colspan="{{ $purchases->count() }}">Einkauf vom</td>
                </tr>
                <tr>
                    @foreach ($purchases as $purchase)
                    <td class="px-1 py-2 text-center border-b border-black {{ $loop->last ? '' : 'border-r' }}">
                        <div class="text-center">
                            <a class="text-blue-400 cursor-pointer hover:underline" href="{{ url('purchase/' . $purchase->id) }}">
                                {{ $purchase->date->germanFormat() }}
                            </a>
                        </div>
                        <div class="flex justify-between text-xs mt-1 px-1 ">
                            <div class="text-gray-600">{{ $purchase->date->translatedFormat('D') }}</div>
                            <div class="text-gray-600">{{ Str::substr($purchase->time, 0, 5) }} Uhr</div>
                        </div>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($purchases as $purchase)
                    <td @class(['text-xs px-1 py-2 align-top', 'border-r border-black' => ! $loop->last])>
                        @forelse ($purchase->articles as $article)
                            <div class="mb-2" title="{{ $article->name . ': ' . money($article->price/100) }} €">
                                {{ Str::limit($article->name, 15) }}
                            </div>
                        @empty
                            <div class="mb-2 text-right">-</div>
                        @endforelse
                    </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($purchases as $purchase)
                    <td @class(['px-1 py-2 text-sm text-right border-t border-black', 'border-r' => ! $loop->last])>
                        <div>{{ money($purchase->articles->sum('price')/100) }} €</div>
                        <div class="mt-4 text-xs text-gray-500 space-y-1">
                            <div class="flex justify-between">
                                <div>Gesamt:</div>
                                <div>{{ money($purchase->total/100) }} €</div>
                            </div>
                            <div class="flex justify-between">
                                <div>Anteil:</div>
                                <div>{{ inPercent($purchase->articles->sum('price'), $purchase->total) }} %</div>
                            </div>
                        </div>
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif
    </div>
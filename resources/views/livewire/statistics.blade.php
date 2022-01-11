<div>
    <x-nav :links="['timeline', 'home']">
        Monatsübersicht
    </x-nav>

    <div class="py-6">
        <div class="mt-6 divide-black" x-data="{ byMonth: true, total: false }">
            <div class="flex justify-start space-x-2 mb-6 pl-3 border-b border-black">
                <div x-on:click="byMonth = true; total = false"
                 class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white" 
                 :class="byMonth ? '-mb-px' : 'bg-gray-100 opacity-50 hover:opacity-100'"
                 x-on:>
                 Monatsübersicht
                </div>
                <div x-on:click="byMonth = false; total = true"
                 class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white" 
                 :class="total ? '-mb-px' : 'bg-gray-100 opacity-50 hover:opacity-100'"
                 >
                 Gesamtausgaben
                </div>
            </div>

            <div x-show="byMonth">
                <div class="flex justify-between py-3 mb-4">
                    <div>
                        Statistik als <x-link href="{{ route('chart') }}">Chart</x-link> ansehen
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox focus:ring-0" wire:model="showPercentages">
                            <span class="ml-2">Prozentwerte anzeigen</span>
                        </label>
                    </div>
                </div>

                <table class="table-auto w-full border-black border">
                    <thead>
                        <tr class="font-bold">
                            <th class="p-2 border-black border-r">Kategorie</th>
                            <th colspan="{{ $dates->count() }}" class="p-2 border-black border-r">Monatliche Ausgaben</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-bold border-black border-t-2">
                            <td class="p-2 border-black border-r"></td>
                            @foreach ($dates as $date)
                                <td class="p-2 text-center w-32 border-black border-r">{{ $date }}</td>
                            @endforeach
                        </tr>
                        @foreach ($categories as $category)
                        <tr @class(['border-black border-t', 'bg-gray-100' => $loop->odd])>
                            <td class="p-2 border-black border-r">{{ $category->name }}</td>
                            @foreach ($dates as $key => $date)
        
                            <td class="p-2 text-right border-black border-r">
                                @isset ($articles[$key][$category->id])
                                    @php
                                        $total = $articles[$key]->flatten()->sum('price');
                                        $partion = $articles[$key][$category->id]->sum('price');
                                        $title = $showPercentages ? money($partion/100) . ' €' : inPercent($partion, $total) . ' %';
                                        $text = $showPercentages ? inPercent($partion, $total) . ' %' : money($partion/100) . ' €';
                                    @endphp
        
                                    <a class="cursor-pointer" title="{{ $title }}" wire:click="showDetails('{{ $key }}', '{{ $category->id }}')">
                                        {{ $text }}
                                    </a>
                                @else
                                    -
                                @endisset
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        <tr class="font-semibold border-black border-t-2 text-white bg-black">
                            <td class="p-2 border-black border-r">Summe</td>
                            @foreach ($dates as $key => $date)
                                <td class="p-2 text-right border-black border-r">
                                    @isset($articles[$key])
                                        {{ money($articles[$key]->flatten()->sum('price')/100) . ' €' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div x-show="total">
                <table class="table-auto w-full border-black border">
                    <thead>
                        <tr class="font-bold">
                            <th class="p-2 border-black border-r">Kategorie</th>
                            <th class="p-2 border-black border-r">Gesamte Ausgaben</th>
                            <th class="p-2 border-black border-r">Durchschnitt pro Monat</th>
                            <th class="p-2 border-black border-r">In Prozent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr @class(['border-black border-t', 'bg-gray-100' => $loop->odd])>
                                <td class="p-2 border-black border-r">{{ $category->name }}</td>
                                <td class="p-2 border-black border-r text-right">{{ money($totals[$category->id]/100) }} €</td>
                                <td class="p-2 border-black border-r text-right">{{ money($totals[$category->id]/100/$numMonths) }} €</td>
                                <td class="p-2 border-black border-r text-right">{{ inPercent($totals[$category->id], array_sum($totals)) }} %</td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold border-black border-t-2 text-white bg-black">
                            <td class="p-2 border-black border-r">Summe</td>
                            <td class="p-2 text-right border-black border-r">{{ money(array_sum($totals)/100) }} €</td>
                            <td class="p-2 text-right border-black border-r">{{ money(array_sum($totals)/100/$numMonths) }} €</td>
                            <td class="p-2 text-right border-black border-r"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($showModal)
        <x-modal wire:model.defer="showModal">
            <x-slot name="title">
                <h2>Übersicht</h2>
                <div class="text-sm text-gray-500">{{ $categoryName . ' - ' . $month['name'] }}</div>
            </x-slot>

            <x-slot name="body">
                <table class="w-full">
                    <tbody>
                        @foreach($selection as $article)
                        <tr class="text-sm">
                            <td class="p-1">{{ $article->name }}</td>
                            <td class="p-1 text-right">{{ money($article->price/100) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-black">
                            <td class="p-1"></td>
                            <td class="p-1 text-right">{{ money($selection->sum('price')/100) }} €</td>
                        </tr>
                    </tfoot>
                </table>

                @isset($month['key'])
                    <div class="mt-4 text-sm text-right text-gray-500">
                        @php( $total = $articles[$month['key']]->flatten()->sum('price'))
                        {{ inPercent($selection->sum('price'), $total) }}%
                        von <a>{{ money($total/100) . ' €' }}</a>
                    </div>
                @endisset
            </x-slot>

            <x-slot name="footer">
                <a class="text-sm py-2 px-3 bg-blue-600 hover:bg-blue-600 text-white rounded-md" href="#" wire:click="$set('showModal', false)">Ok</a>
            </x-slot>
        </x-modal>
    @endif
</div>
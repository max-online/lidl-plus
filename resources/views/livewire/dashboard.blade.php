<div>
    <x-nav :links="['toplist', 'statistics', 'settings']">
        Übersicht meiner Einkäufe
    </x-nav>

    @livewire('search')

    <div class="flex mt-8 pb-4 space-x-8">
        <div class="w-1/2">
            <div class="flex items-center mb-3">
                <x-input.select wire:model="date">
                    <option value="">Zeitraum wählen..</option>
                    <option value="complete">Kompletter Zeitraum</option>
                    @foreach($dates as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </x-input.select>

                <div class="ml-3 cursor-pointer" wire:click="refresh">
                    <x-heroicon-o-x class="h-5 w-5"/>
                </div>
            </div>

            <div class="mt-5 bg-gray-100 border border-gray-200 rounded-md">
                <div class="flex items-center p-4 pb-0 space-x-3">
                    <div>
                        <x-heroicon-o-chart-square-bar class="w-6 h-6 text-gray-900"/>
                    </div>
                    <div class="text-lg text-gray-900">
                        @if (\Str::is('****-**', $date))
                            Einkäufe im {{ $dates[$date] }}
                        @elseif ($date == 'complete')
                            Alle Einkäufe
                        @else
                            Einkäufe in den letzten 2 Monaten
                        @endif
                    </div> 
                </div> 
                <div class="p-4">
                    <table>
                        <tr>
                            <td class="p-2">Anzahl der Einkäufe:</td>
                            <td class="p-2 font-semibold text-right">{{ $purchases->count() }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 pt-5">Gesamte Ausgaben:</td>
                            <td class="p-2 pt-5 font-semibold text-right">{{ money($purchases->sum('total')/100) }} €</td>
                        </tr>
                        <tr>
                            <td class="p-2 pb-5">Lidl Plus Ersparnis:</td>
                            <td class="p-2 pb-5 font-semibold text-right">{{ money($purchases->sum('savings')/100) }} €</td>
                        </tr>
                        <tr>
                            <td class="p-2">&#8709; der Einkäufe:</td>
                            <td class="p-2 font-semibold text-right">{{ money($purchases->average('total')/100) }} €</td>
                        </tr>
                        @if ($date == 'complete')
                            @php($days = now()->diffInDays($purchases->min('date')))
                            <tr>
                                <td class="p-2 pt-5">
                                    Zeitraum in Tagen: <br>
                                    <div class="text-sm text-gray-600">Erster Einkauf: {{ $purchases->min('date')->germanFormat() }}</div>
                                    <div class="text-sm text-gray-600">Letzter Einkauf: {{ $purchases->max('date')->germanFormat() }}</div>
                                </td>
                                <td class="p-2 pt-5 text-right font-semibold align-top">{{ $days }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">Tägliche Ausgaben:</td>
                                <td class="p-2 text-right font-semibold">{{ money(($purchases->sum('total') - $purchases->sum('savings'))/$days/100) }} €</td>
                            </tr>
                        @endif
                    </table>  
                </div>
            </div>
        </div>

        <div class="w-1/2 border border-gray-200 rounded-md">
            <table class="w-full">
                <thead>
                    <tr class="font-medium bg-gray-100 text-gray-600 border-b border-black">
                        <th class="px-3 py-3 text-left">Einkauf</th>
                        <th class="px-3 py-3 text-right">Gesamt</th>
                        <th class="px-3 py-3 text-right">Gespart</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($purchases as $purchase)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-3 py-2 text-blue-400 cursor-pointer hover:underline">
                            <a href="{{ url('purchase/' . $purchase->id) }}"> Vom {{ $purchase->date->germanFormat() }}</a>
                        </td>
                        <td class="px-3 py-2 text-right">{{ money($purchase->total/100) }} €</td>    
                        <td class="px-3 py-2 text-right">{{ money($purchase->savings/100) }} €</td>    
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 border-t-2 border-black hover:bg-gray-100">
                        <td class="px-2 py-3 font-medium text-gray-600">Summe</td>
                        <td class="px-2 py-3 text-right">{{ money($purchases->sum('total')/100) }} €</td>    
                        <td class="px-2 py-3 text-right">{{ money($purchases->sum('savings')/100) }} €</td>    
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    @if ($byCategory)
        <div class="flex mt-4 border-t-2 border-black">
            <div class="flex-1 mt-6">
                @include('partials.category-table', ['titleSuffix' => '<span class="font-light text-sm">(' . $dates[$date] . ')'])
            </div>

            <div class="flex-1 mt-6">

                @if ($articles)
                    <div class="flex justify-between items-center my-3">
                        <h3 class="font-semibold text-lg">Gekaufte Artikel</h3>

                        <x-toggle-details wire:click="$toggle('details')" :details="$details"></x-toggle-details>
                    </div>

                    @include('partials.loading')

                    <table wire:loading.remove>
                        <tbody>
                            @foreach($articles as $article)
                                <tr class="text-sm">
                                    <td class="py-1 px-3">{{ $article->name }}</td>
                                    <td class="py-1 px-3">{{ money($article->price/100) }} €</td>
                                    <td class="py-1 px-3 text-blue-300 hover:underline">
                                        @isset($article->purchase)
                                            <a href="{{ route('purchase', [$article->purchase->id]) }}">{{ $article->purchase->date->germanFormat() }}</a>
                                        @endisset
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        @endif
    </div>
</div>

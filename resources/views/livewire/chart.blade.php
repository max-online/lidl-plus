<div>
    <x-nav :links="['statistics', 'home']">
        Chart
    </x-nav>

    <div class="mt-8" x-data="initChart('@chart('by_category')', {{ $categories }})">
        <div class="flex justify-start space-x-2 mb-6 pl-3 border-b border-black">
            <div class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white {{ $type != 'byCategory' ? 'bg-gray-100 opacity-50 hover:opacity-100' : '-mb-px' }}" wire:click="changeType('byCategory')">Einkäufe nach Kategorie</div>
            <div class="text-lg cursor-pointer px-3 py-2 border border-b-0 border-black bg-white {{ $type != 'byProduct' ? 'bg-gray-100 opacity-50 hover:opacity-100' : '-mb-px' }}" wire:click="changeType('byProduct')">Einkäufe nach Produkt</div>
        </div>
        <div class="flex justify-between mt-2 items-center">
            <div class="flex items-center space-x-3">
                @if ($type == 'byCategory')
                    <x-input.select x-model="selectedCategory" x-on:change="updateChart()">
                        <option value="0">Kategorie auswählen..</option>
                        <template x-for="category in categories" :key="category.id">
                            <option x-bind:value="category.id" x-text="category.name"></option>
                        </template>
                    </x-input>
                    <div class="space-x-2">
                        @if ((int) $selected['category'] > 0)
                            <span class="">
                                <a x-on:keydown.arrow-up.window="changeCategory('up')">&#8593;</a>
                            </span>
                        @endif
                        @if ((int) $selected['category'] <= $categories->count())
                            <span class="">
                                <a x-on:keydown.arrow-down.window="changeCategory('down')">&#8595;</a>
                            </span>
                        @endif
                    </div>
                @endif

                @if ($type == 'byProduct')
                    <div wire:ignore>
                        <x-input.select x-model="selectedProduct" x-on:change="updateChart()">
                            <option value="0" disabled>Produkt auswählen..</option>
                            @foreach ($products as $label => $productCategory)
                                <optgroup label="{{ $label }}">
                                    @foreach ($productCategory as $key =>$product)
                                        <option value="{{ $key }}">{{ $product }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-input.select>
                    </div>
                @endif
            </div>
            <div class="space-y-1">
                <div class="flex justify-between">
                    <span class="inline-block font-semibold text-gray-700 w-48">Gesamtausgaben: </span>{{ money($total/100) . ' €' }}
                </div>
                <div class="flex justify-between">
                    <span class="inline-block font-semibold text-gray-700 w-48">Ausgaben pro Monat: </span>{{ money($total/$numOfMonths/100) . ' €' }}
                </div>
            </div>
        </div>

        <div id="chart" wire:ignore class="mt-4" style="height: 300px;"></div>
    </div>


    @if ($articlesBySelection && $selected['date'])
        <div class="flex py-8 border-t-2 border-black">
            <div class="w-1/2">
                <h3 class="font-semibold text-lg">Gekaufte Artikel im {{ $date->translatedFormat('F Y') }}</h3>

                @if ($selected['category'])
                    <div class="text-sm text-gray-600">
                        Kategorie: {{ $categories->firstwhere('id', $selected['category'])->name }}
                    </div>

                    <div class="mt-2">
                        {{ inPercent($articlesBySelection->sum('price'), $totalByDate) }} % der Gesamtausgaben
                    </div>
                @endif
            </div>

            <div class="w-1/2">
                @if ($articlesBySelection)
                    <div class="flex justify-end mb-4">
                        <x-toggle-details wire:click="$toggle('details')" :details="$details"></x-toggle-details>
                    </div>
                    <table class="text-sm">
                        <tbody>
                            @foreach($articlesBySelection as $article)
                            <tr>
                                <td class="py-1 px-4">{{ $article->name }}</td>
                                <td class="py-1 px-4 text-right">{{ money($article->price/100) }} €</td>
                                <td class="py-1 px-4 text-blue-300 hover:underline">
                                    @if ($details && isset($article->purchase))
                                        <a href="{{ route('purchase', [$article->purchase->id]) }}">{{ $article->purchase->date->germanFormat() }}</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="font-semibold border-t-2 border-black">
                                <td class="py-1 px-4 text-left">Summe</td>
                                <td class="py-1 px-4 text-right">{{ money($articlesBySelection->sum('price')/100) }} €</td>
                                <td class="py-1 px-4 w-24"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <div>Keine Artikel vorhanden.</div>
                @endif
            </div>
        </div>
    @endif

    <!-- Charting library -->
    <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- Your application script -->
    <script>       
        const formatter = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' });

        let select = document.querySelector('select');
        let options = select.options;

		function initChart(url, categories) {
			return {
				url,
				selectedCategory: @entangle('selected.category'),
				selectedProduct: @entangle('selected.product'),
				categories: categories,
				chart: new Chartisan({
					el: '#chart',
					url: url,
                    plugins: [ChartDataLabels],
					hooks: new ChartisanHooks()
						.legend()
						.colors(['#2563EB', '#DC2626'])
                        .borderColors()
						.tooltip({
							enabled: true,
							callbacks: {
								label: function (tooltipItems, data) {
									return formatter.format(tooltipItems.yLabel);
								}
							},
						})
						.datasets([{type:'line', fill: false}, {type:'bar'}])
						.options({
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: true,
											min: 0,
											callback: function(value, index, values) {
												return value + ' €';
											}
										}
									}]
								},
                                plugins: {
                                    datalabels: {
                                        display: true,
                                        formatter: (value) => {
                                            return value;
                                        },
                                        color: 'blue',
                                    }
                                },
								onClick: function(e) {
                                    let label = this.chart.getElementsAtEvent(e)[1]._model.label;

                                    @this.set('selected.date', label);
								}
							}
						})
					}),
				updateChart() {
                    @this.set('selected.category', this.selectedCategory);
                    @this.set('selected.product', this.selectedProduct);

					this.chart.update({ url: this.url + '?category=' + this.selectedCategory + '&product=' + this.selectedProduct });
				},
                changeCategory(direction) {
                    let index = select.selectedIndex;

                    if (direction == 'down') {
                        if (index >= (options.length - 1))
                            return;

                        this.selectedCategory = options[index + 1].value;

                        this.updateChart();
                    } else {
                        if (index == 0) 
                            return;

                        this.selectedCategory = options[index - 1].value;

                        this.updateChart();
                    }
                }
			}; 
		}
    </script>
</div>
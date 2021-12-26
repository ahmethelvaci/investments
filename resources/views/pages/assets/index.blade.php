<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-bottom">
                                                {{ __('Asset') }} <br>
                                                {{ __('Last Control') }} ({{ __('Quantity') }})
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider align-bottom">
                                                {{ __('Amount') }} TL <br>
                                                {{ __('Cost') }} TL <br>
                                                {{ __('Profit') }} TL <br>
                                                {{ __('Profit') }} % <br>
                                                {{ __('Price') }} TL <br>
                                                {{ __('Unit') }} TL
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Daily') }} TL <br>
                                                {{ __('Weekly') }} TL <br>
                                                {{ __('Monthly') }} TL <br>
                                                {{ __('Quarterly') }} TL <br>
                                                {{ __('Semiannually') }} TL <br>
                                                {{ __('Yearly') }} TL
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider align-bottom">
                                                {{ __('Amount') }} $ <br>
                                                {{ __('Cost') }} $ <br>
                                                {{ __('Profit') }} $ <br>
                                                {{ __('Profit') }} % <br>
                                                {{ __('Price') }} $ <br>
                                                {{ __('Unit') }} $
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Daily') }} $ <br>
                                                {{ __('Weekly') }} $ <br>
                                                {{ __('Monthly') }} $ <br>
                                                {{ __('Quarterly') }} $ <br>
                                                {{ __('Semiannually') }} $ <br>
                                                {{ __('Yearly') }} $
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($assets as $asset)
                                            @php
                                                $unitCostTl = $asset->summary->quantity ? $asset->summary->cost_tl / $asset->summary->quantity : 0;
                                                $unitCostUsd = $asset->summary->quantity ? $asset->summary->cost_usd / $asset->summary->quantity : 0;
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 w-1/4 align-top">
                                                    <div class="text-sm text-gray-900" title="{{ $asset->name }}">
                                                        {{ Str::words($asset->name, 8, '...') }}
                                                    </div>
                                                    <hr>
                                                    <div class="text-sm text-gray-900">
                                                        {{ $asset->summary->created_at->format('d-m-Y H:i') }} ({{ number_format($asset->summary->quantity) }})
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm text-gray-900 text-right">
                                                        {{ number_format($asset->summary->amount_tl, 2) }} TL
                                                        <br>
                                                        {{ number_format($asset->summary->cost_tl, 2) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ number_format($asset->summary->profit_tl, 2) }} TL
                                                        </span>
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            % {{ number_format($asset->summary->profit_percent_tl, 2) }}
                                                        </span>
                                                        <br>
                                                        {{ number_format($asset->summary->price_tl, 6) }} TL
                                                        <br>
                                                        {{ number_format($unitCostTl, 6) }} TL
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-xs text-gray-900 text-right">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->daily_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->daily_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->daily_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->daily_price_tl, 4) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->weekly_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->weekly_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->weekly_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->weekly_price_tl, 4) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->monthly_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->monthly_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->monthly_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->monthly_price_tl, 4) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->quarterly_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->quarterly_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->quarterly_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->quarterly_price_tl, 4) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->semiannually_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->semiannually_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->semiannually_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->semiannually_price_tl, 4) }} TL
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->yearly_profit_percent_tl >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->yearly_profit_tl, 6) }} TL">
                                                            % {{ number_format($asset->summary->yearly_profit_percent_tl, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->yearly_price_tl, 4) }} TL
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm text-gray-900 text-right">
                                                        {{ number_format($asset->summary->amount_usd, 2) }} $
                                                        <br>
                                                        {{ number_format($asset->summary->cost_usd, 2) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ number_format($asset->summary->profit_usd, 2) }} $
                                                        </span>
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            % {{ number_format($asset->summary->profit_percent_usd, 2) }}
                                                        </span>
                                                        <br>
                                                        {{ number_format($asset->summary->price_usd, 6) }} $
                                                        <br>
                                                        {{ number_format($unitCostUsd, 6) }} $
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-xs text-gray-900 text-right">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->daily_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->daily_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->daily_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->daily_price_usd, 4) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->weekly_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->weekly_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->weekly_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->weekly_price_usd, 4) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->monthly_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->monthly_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->monthly_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->monthly_price_usd, 4) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->quarterly_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->quarterly_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->quarterly_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->quarterly_price_usd, 4) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->semiannually_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->semiannually_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->semiannually_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->semiannually_price_usd, 4) }} $
                                                        <br>
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset->summary->yearly_profit_percent_usd >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" title="{{ number_format($asset->summary->yearly_profit_usd, 6) }} $">
                                                            % {{ number_format($asset->summary->yearly_profit_percent_usd, 2) }}
                                                        </span>
                                                        {{ number_format($asset->summary->yearly_price_usd, 4) }} $
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compare Estimates</h2>
            <a href="{{ route('estimates.index') }}" class="text-sm text-slate-500 hover:text-slate-700 transition">&larr; Back to estimates</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left text-sm font-medium text-slate-500 pb-4 pr-4 w-48"></th>
                            @foreach($estimates as $estimate)
                                <th class="text-left pb-4 px-4 min-w-[220px]">
                                    <div class="font-semibold text-slate-800">{{ $estimate->name }}</div>
                                    @if($estimate->project_type)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $estimate->project_type)) }}
                                        </span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        {{-- Recommended Price --}}
                        <tr class="bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                            <td class="py-3 pr-4 pl-4 font-semibold rounded-l-lg">Recommended Price</td>
                            @php $maxPrice = $estimates->max('recommended_price'); @endphp
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 @if($loop->last) rounded-r-lg @endif">
                                    <span class="text-2xl font-bold text-amber-400">${{ number_format($estimate->recommended_price, 2) }}</span>
                                    @if($estimate->recommended_price == $maxPrice && $estimates->count() > 1)
                                        <span class="ml-2 text-xs bg-amber-500/20 text-amber-300 px-1.5 py-0.5 rounded">Highest</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Safe Range --}}
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Safe Range</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4">
                                    <span class="text-green-600">${{ number_format($estimate->safe_price_low, 2) }}</span>
                                    <span class="text-slate-400">&ndash;</span>
                                    <span class="text-blue-600">${{ number_format($estimate->safe_price_high, 2) }}</span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Spacer --}}
                        <tr><td colspan="{{ $estimates->count() + 1 }}" class="py-2"></td></tr>

                        {{-- Labor --}}
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Labor Hours</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 text-slate-700">{{ $estimate->labor_hours }} hrs</td>
                            @endforeach
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Labor Rate</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 text-slate-700">${{ number_format($estimate->labor_rate, 2) }}/hr</td>
                            @endforeach
                        </tr>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <td class="py-3 pr-4 text-slate-600 font-semibold">Labor Total</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 font-semibold text-slate-800">${{ number_format($estimate->labor_total, 2) }}</td>
                            @endforeach
                        </tr>

                        {{-- Materials --}}
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <td class="py-3 pr-4 text-slate-600 font-semibold">Materials Total</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 font-semibold text-slate-800">${{ number_format($estimate->materials_total, 2) }}</td>
                            @endforeach
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Material Items</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 text-slate-600">
                                    @foreach($estimate->materials ?? [] as $m)
                                        <div class="text-xs">{{ $m['name'] }}: {{ $m['quantity'] }} &times; ${{ number_format($m['unit_cost'], 2) }}</div>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>

                        {{-- Spacer --}}
                        <tr><td colspan="{{ $estimates->count() + 1 }}" class="py-2"></td></tr>

                        {{-- Subtotal --}}
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <td class="py-3 pr-4 text-slate-600 font-semibold">Subtotal</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 font-semibold text-slate-800">${{ number_format($estimate->subtotal, 2) }}</td>
                            @endforeach
                        </tr>

                        {{-- Overhead --}}
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Overhead</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 text-slate-700">{{ $estimate->overhead_percent }}% &mdash; ${{ number_format($estimate->overhead_amount, 2) }}</td>
                            @endforeach
                        </tr>

                        {{-- Profit --}}
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 text-slate-500 font-medium">Profit Margin</td>
                            @foreach($estimates as $estimate)
                                @php $profitAmt = $estimate->recommended_price - $estimate->subtotal - $estimate->overhead_amount; @endphp
                                <td class="py-3 px-4 text-slate-700">{{ $estimate->profit_percent }}% &mdash; ${{ number_format($profitAmt, 2) }}</td>
                            @endforeach
                        </tr>

                        {{-- Notes --}}
                        <tr>
                            <td class="py-3 pr-4 text-slate-500 font-medium">Notes</td>
                            @foreach($estimates as $estimate)
                                <td class="py-3 px-4 text-slate-600 text-xs">{{ $estimate->notes ?: '—' }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

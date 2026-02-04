<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $estimate->name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('estimates.edit', $estimate) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('estimates.duplicate', $estimate) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Duplicate
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            {{-- Pricing Summary --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-6 text-white">
                <div class="text-center mb-6">
                    <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">Recommended Price</div>
                    <div class="text-5xl font-bold text-amber-400">${{ number_format($estimate->recommended_price, 2) }}</div>
                </div>
                <div class="flex justify-center gap-12 mb-4">
                    <div class="text-center">
                        <div class="text-xs text-slate-400">Safe Low</div>
                        <div class="text-xl font-semibold text-green-400">${{ number_format($estimate->safe_price_low, 2) }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-slate-400">Safe High</div>
                        <div class="text-xl font-semibold text-blue-400">${{ number_format($estimate->safe_price_high, 2) }}</div>
                    </div>
                </div>
                <div class="relative h-3 bg-slate-700 rounded-full overflow-hidden">
                    <div class="absolute inset-y-0 bg-gradient-to-r from-green-500 via-amber-400 to-blue-500 rounded-full" style="left: 0%; width: 100%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mt-1">
                    <span>${{ number_format($estimate->safe_price_low, 2) }}</span>
                    <span class="text-amber-400 font-medium">Recommended</span>
                    <span>${{ number_format($estimate->safe_price_high, 2) }}</span>
                </div>
            </div>

            {{-- Cost Breakdown --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Labor --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Labor</h3>
                    <div class="text-3xl font-bold text-slate-800">${{ number_format($estimate->labor_total, 2) }}</div>
                    <div class="text-sm text-slate-500 mt-1">{{ $estimate->labor_hours }} hrs &times; ${{ number_format($estimate->labor_rate, 2) }}/hr</div>
                </div>

                {{-- Materials --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Materials</h3>
                    <div class="text-3xl font-bold text-slate-800">${{ number_format($estimate->materials_total, 2) }}</div>
                    <div class="text-sm text-slate-500 mt-1">{{ count($estimate->materials ?? []) }} line items</div>
                </div>
            </div>

            {{-- Materials Detail --}}
            @if(!empty($estimate->materials))
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Materials Breakdown</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="pb-2 font-medium">Item</th>
                            <th class="pb-2 font-medium text-right">Qty</th>
                            <th class="pb-2 font-medium text-right">Unit Cost</th>
                            <th class="pb-2 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estimate->materials as $material)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-700">{{ $material['name'] }}</td>
                            <td class="py-2 text-right text-slate-600">{{ $material['quantity'] }}</td>
                            <td class="py-2 text-right text-slate-600">${{ number_format($material['unit_cost'], 2) }}</td>
                            <td class="py-2 text-right font-medium text-slate-800">${{ number_format($material['quantity'] * $material['unit_cost'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Overhead & Profit --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Pricing Calculation</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subtotal (Labor + Materials)</span>
                        <span class="font-medium text-slate-800">${{ number_format($estimate->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Overhead ({{ $estimate->overhead_percent }}%)</span>
                        <span class="font-medium text-slate-800">${{ number_format($estimate->overhead_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-2">
                        <span class="text-slate-600">Cost Base</span>
                        <span class="font-medium text-slate-800">${{ number_format($estimate->subtotal + $estimate->overhead_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Profit ({{ $estimate->profit_percent }}%)</span>
                        <span class="font-medium text-slate-800">${{ number_format($estimate->recommended_price - $estimate->subtotal - $estimate->overhead_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-slate-300 pt-2">
                        <span class="font-semibold text-slate-800">Recommended Price</span>
                        <span class="font-bold text-lg text-amber-600">${{ number_format($estimate->recommended_price, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($estimate->notes)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-2">Notes</h3>
                <p class="text-slate-700">{{ $estimate->notes }}</p>
            </div>
            @endif

            {{-- Meta --}}
            <div class="text-xs text-slate-400 text-center">
                @if($estimate->project_type)
                    <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 rounded mr-2">{{ ucfirst(str_replace('_', ' ', $estimate->project_type)) }}</span>
                @endif
                Created {{ $estimate->created_at->format('M j, Y g:ia') }}
                &middot; Updated {{ $estimate->updated_at->format('M j, Y g:ia') }}
            </div>
        </div>
    </div>
</x-app-layout>

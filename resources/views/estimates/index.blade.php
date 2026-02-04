<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Estimates</h2>
            <a href="{{ route('estimates.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Estimate
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
            @endif

            @if($estimates->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No estimates yet</h3>
                    <p class="text-slate-500 mb-4">Create your first estimate to get started.</p>
                    <a href="{{ route('estimates.create') }}" class="inline-flex items-center gap-1 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create Estimate
                    </a>
                </div>
            @else
                <div x-data="{ selected: [] }">
                    {{-- Compare button --}}
                    <div class="mb-4 flex justify-end" x-show="selected.length >= 2" x-cloak>
                        <form method="GET" action="{{ route('estimates.compare') }}">
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Compare (<span x-text="selected.length"></span>)
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($estimates as $estimate)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow relative group">
                            {{-- Compare checkbox --}}
                            <label class="absolute top-3 right-3 z-10 cursor-pointer">
                                <input type="checkbox" value="{{ $estimate->id }}"
                                    @change="selected.includes({{ $estimate->id }}) ? selected = selected.filter(i => i !== {{ $estimate->id }}) : selected.push({{ $estimate->id }})"
                                    class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            </label>

                            <a href="{{ route('estimates.show', $estimate) }}" class="block p-6">
                                <div class="flex items-start justify-between pr-6">
                                    <div>
                                        <h3 class="font-semibold text-slate-800 text-lg">{{ $estimate->name }}</h3>
                                        @if($estimate->project_type)
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs">
                                                {{ ucfirst(str_replace('_', ' ', $estimate->project_type)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="text-3xl font-bold text-amber-600">${{ number_format($estimate->recommended_price, 2) }}</div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        Range: ${{ number_format($estimate->safe_price_low, 2) }} &ndash; ${{ number_format($estimate->safe_price_high, 2) }}
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-3 gap-2 text-xs">
                                    <div class="bg-slate-50 rounded p-2 text-center">
                                        <div class="text-slate-400">Labor</div>
                                        <div class="font-semibold text-slate-700">${{ number_format($estimate->labor_total, 0) }}</div>
                                    </div>
                                    <div class="bg-slate-50 rounded p-2 text-center">
                                        <div class="text-slate-400">Materials</div>
                                        <div class="font-semibold text-slate-700">${{ number_format($estimate->materials_total, 0) }}</div>
                                    </div>
                                    <div class="bg-slate-50 rounded p-2 text-center">
                                        <div class="text-slate-400">Margin</div>
                                        <div class="font-semibold text-slate-700">{{ $estimate->profit_percent }}%</div>
                                    </div>
                                </div>

                                <div class="mt-3 text-xs text-slate-400">Updated {{ $estimate->updated_at->diffForHumans() }}</div>
                            </a>

                            {{-- Actions --}}
                            <div class="border-t border-slate-100 px-6 py-3 flex items-center justify-between opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('estimates.edit', $estimate) }}" class="text-sm text-slate-500 hover:text-slate-700">Edit</a>
                                <form method="POST" action="{{ route('estimates.duplicate', $estimate) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-slate-500 hover:text-slate-700">Duplicate</button>
                                </form>
                                <form method="POST" action="{{ route('estimates.destroy', $estimate) }}" class="inline"
                                    onsubmit="return confirm('Delete this estimate?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-400 hover:text-red-600">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<div x-data="estimateForm()" class="space-y-8">
    {{-- Project Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Project Info
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" value="Estimate Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g. Smith Kitchen Remodel"
                    :value="old('name', $estimate->name ?? '')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="project_type" value="Project Type" />
                <select id="project_type" name="project_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="">Select type...</option>
                    @foreach(['residential' => 'Residential', 'commercial' => 'Commercial', 'renovation' => 'Renovation', 'new_construction' => 'New Construction'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('project_type', $estimate->project_type ?? '') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('project_type')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Labor --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Labor
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="labor_hours" value="Hours" />
                <x-text-input id="labor_hours" name="labor_hours" type="number" step="0.5" min="0" class="mt-1 block w-full"
                    x-model="laborHours" :value="old('labor_hours', $estimate->labor_hours ?? 0)" required />
                <x-input-error :messages="$errors->get('labor_hours')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="labor_rate" value="Rate ($/hr)" />
                <x-text-input id="labor_rate" name="labor_rate" type="number" step="0.01" min="0" class="mt-1 block w-full"
                    x-model="laborRate" :value="old('labor_rate', $estimate->labor_rate ?? 0)" required />
                <x-input-error :messages="$errors->get('labor_rate')" class="mt-2" />
            </div>
        </div>
        <div class="mt-3 text-sm text-slate-500">
            Labor subtotal: <span class="font-semibold text-slate-700" x-text="formatCurrency(laborTotal)"></span>
        </div>
    </div>

    {{-- Materials --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Materials
        </h3>

        <template x-for="(material, index) in materials" :key="index">
            <div class="grid grid-cols-12 gap-3 mb-3 items-end">
                <div class="col-span-5">
                    <label class="block text-xs font-medium text-slate-600 mb-1" x-show="index === 0">Item</label>
                    <input type="text" :name="'materials['+index+'][name]'" x-model="material.name"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm"
                        placeholder="Material name" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1" x-show="index === 0">Qty</label>
                    <input type="number" :name="'materials['+index+'][quantity]'" x-model.number="material.quantity" step="0.01" min="0"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-xs font-medium text-slate-600 mb-1" x-show="index === 0">Unit Cost ($)</label>
                    <input type="number" :name="'materials['+index+'][unit_cost]'" x-model.number="material.unit_cost" step="0.01" min="0"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm" required>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-700" x-text="formatCurrency(material.quantity * material.unit_cost)"></span>
                    <button type="button" @click="removeMaterial(index)" class="text-red-400 hover:text-red-600 transition" x-show="materials.length > 1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </template>

        <button type="button" @click="addMaterial()"
            class="mt-2 inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Material
        </button>

        <div class="mt-3 text-sm text-slate-500">
            Materials subtotal: <span class="font-semibold text-slate-700" x-text="formatCurrency(materialsTotal)"></span>
        </div>
    </div>

    {{-- Overhead & Profit --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Overhead & Profit
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="overhead_percent" value="Overhead (%)" />
                <x-text-input id="overhead_percent" name="overhead_percent" type="number" step="0.5" min="0" max="100" class="mt-1 block w-full"
                    x-model="overheadPercent" :value="old('overhead_percent', $estimate->overhead_percent ?? 10)" required />
                <x-input-error :messages="$errors->get('overhead_percent')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="profit_percent" value="Profit Margin (%)" />
                <x-text-input id="profit_percent" name="profit_percent" type="number" step="0.5" min="0" max="100" class="mt-1 block w-full"
                    x-model="profitPercent" :value="old('profit_percent', $estimate->profit_percent ?? 15)" required />
                <x-input-error :messages="$errors->get('profit_percent')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Live Pricing Preview --}}
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-6 text-white">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pricing Summary
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wide">Labor</div>
                <div class="text-lg font-bold" x-text="formatCurrency(laborTotal)"></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wide">Materials</div>
                <div class="text-lg font-bold" x-text="formatCurrency(materialsTotal)"></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wide">Overhead</div>
                <div class="text-lg font-bold" x-text="formatCurrency(overheadAmount)"></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wide">Profit</div>
                <div class="text-lg font-bold" x-text="formatCurrency(profitAmount)"></div>
            </div>
        </div>

        <div class="border-t border-slate-700 pt-4">
            <div class="text-center">
                <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">Recommended Price</div>
                <div class="text-4xl font-bold text-amber-400" x-text="formatCurrency(recommendedPrice)"></div>
            </div>
            <div class="flex justify-center gap-8 mt-3">
                <div class="text-center">
                    <div class="text-xs text-slate-400">Low</div>
                    <div class="text-lg font-semibold text-green-400" x-text="formatCurrency(safePriceLow)"></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-slate-400">High</div>
                    <div class="text-lg font-semibold text-blue-400" x-text="formatCurrency(safePriceHigh)"></div>
                </div>
            </div>

            {{-- Price range bar --}}
            <div class="mt-4 relative h-3 bg-slate-700 rounded-full overflow-hidden">
                <div class="absolute inset-y-0 bg-gradient-to-r from-green-500 via-amber-400 to-blue-500 rounded-full transition-all"
                    :style="'left: 0%; width: 100%'"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-500 mt-1">
                <span x-text="formatCurrency(safePriceLow)"></span>
                <span class="text-amber-400 font-medium">Recommended</span>
                <span x-text="formatCurrency(safePriceHigh)"></span>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <x-input-label for="notes" value="Notes (optional)" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500"
            placeholder="Additional notes about this estimate...">{{ old('notes', $estimate->notes ?? '') }}</textarea>
    </div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('estimates.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 transition">Cancel</a>
        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg shadow-sm transition">
            {{ isset($estimate) && $estimate->exists ? 'Update Estimate' : 'Create Estimate' }}
        </button>
    </div>
</div>

@push('scripts')
<script>
function estimateForm() {
    return {
        laborHours: {{ old('labor_hours', $estimate->labor_hours ?? 0) }},
        laborRate: {{ old('labor_rate', $estimate->labor_rate ?? 0) }},
        overheadPercent: {{ old('overhead_percent', $estimate->overhead_percent ?? 10) }},
        profitPercent: {{ old('profit_percent', $estimate->profit_percent ?? 15) }},
        materials: {!! json_encode(old('materials', $estimate->materials ?? [['name' => '', 'quantity' => 1, 'unit_cost' => 0]])) !!},

        get laborTotal() { return this.laborHours * this.laborRate; },
        get materialsTotal() {
            return this.materials.reduce((sum, m) => sum + (m.quantity || 0) * (m.unit_cost || 0), 0);
        },
        get subtotal() { return this.laborTotal + this.materialsTotal; },
        get overheadAmount() { return this.subtotal * (this.overheadPercent / 100); },
        get costBase() { return this.subtotal + this.overheadAmount; },
        get profitAmount() { return this.costBase * (this.profitPercent / 100); },
        get recommendedPrice() { return this.costBase + this.profitAmount; },
        get safePriceLow() { return this.recommendedPrice * 0.95; },
        get safePriceHigh() { return this.recommendedPrice * 1.10; },

        addMaterial() {
            this.materials.push({ name: '', quantity: 1, unit_cost: 0 });
        },
        removeMaterial(index) {
            this.materials.splice(index, 1);
        },
        formatCurrency(val) {
            return '$' + Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
@endpush

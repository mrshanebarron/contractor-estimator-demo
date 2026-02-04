<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estimate extends Model
{
    /** @use HasFactory<\Database\Factories\EstimateFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'project_type',
        'labor_hours',
        'labor_rate',
        'materials',
        'overhead_percent',
        'profit_percent',
        'notes',
    ];

    protected $casts = [
        'materials' => 'array',
        'labor_hours' => 'decimal:2',
        'labor_rate' => 'decimal:2',
        'overhead_percent' => 'decimal:2',
        'profit_percent' => 'decimal:2',
        'labor_total' => 'decimal:2',
        'materials_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'overhead_amount' => 'decimal:2',
        'recommended_price' => 'decimal:2',
        'safe_price_low' => 'decimal:2',
        'safe_price_high' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Recalculate all pricing fields from inputs.
     */
    public function calculatePricing(): self
    {
        $this->labor_total = $this->labor_hours * $this->labor_rate;

        $materials = $this->materials ?? [];
        $this->materials_total = collect($materials)->sum(function ($item) {
            return ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);
        });

        $this->subtotal = $this->labor_total + $this->materials_total;
        $this->overhead_amount = $this->subtotal * ($this->overhead_percent / 100);

        $costBase = $this->subtotal + $this->overhead_amount;
        $profit = $costBase * ($this->profit_percent / 100);

        $this->recommended_price = $costBase + $profit;
        $this->safe_price_low = $this->recommended_price * 0.95;
        $this->safe_price_high = $this->recommended_price * 1.10;

        return $this;
    }

    protected static function booted(): void
    {
        static::saving(function (Estimate $estimate) {
            $estimate->calculatePricing();
        });
    }
}

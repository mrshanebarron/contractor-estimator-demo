<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // "Smith Kitchen Remodel"
            $table->string('project_type')->nullable(); // residential, commercial, etc.

            // Labor
            $table->decimal('labor_hours', 10, 2)->default(0);
            $table->decimal('labor_rate', 10, 2)->default(0);

            // Materials - stored as JSON array [{name, quantity, unit_cost}]
            $table->json('materials')->default('[]');

            // Overhead & profit percentages
            $table->decimal('overhead_percent', 5, 2)->default(10);
            $table->decimal('profit_percent', 5, 2)->default(15);

            // Calculated totals (stored for quick access, recomputed on save)
            $table->decimal('labor_total', 12, 2)->default(0);
            $table->decimal('materials_total', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('overhead_amount', 12, 2)->default(0);
            $table->decimal('recommended_price', 12, 2)->default(0);
            $table->decimal('safe_price_low', 12, 2)->default(0);
            $table->decimal('safe_price_high', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};

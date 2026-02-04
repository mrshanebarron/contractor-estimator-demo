<?php

namespace Database\Seeders;

use App\Models\Estimate;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo Contractor',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed realistic estimates
        Estimate::create([
            'user_id' => $user->id,
            'name' => 'Smith Kitchen Remodel',
            'project_type' => 'renovation',
            'labor_hours' => 120,
            'labor_rate' => 65,
            'materials' => [
                ['name' => 'Cabinets (set)', 'quantity' => 1, 'unit_cost' => 4200],
                ['name' => 'Countertop - Granite (sq ft)', 'quantity' => 45, 'unit_cost' => 75],
                ['name' => 'Tile Backsplash (sq ft)', 'quantity' => 30, 'unit_cost' => 12],
                ['name' => 'Plumbing Fixtures', 'quantity' => 3, 'unit_cost' => 280],
                ['name' => 'Electrical Work (allowance)', 'quantity' => 1, 'unit_cost' => 800],
            ],
            'overhead_percent' => 12,
            'profit_percent' => 20,
            'notes' => 'Client wants modern farmhouse style. Timeline: 3-4 weeks.',
        ]);

        Estimate::create([
            'user_id' => $user->id,
            'name' => 'Johnson Deck Build',
            'project_type' => 'new_construction',
            'labor_hours' => 48,
            'labor_rate' => 55,
            'materials' => [
                ['name' => 'Pressure Treated Lumber (board ft)', 'quantity' => 400, 'unit_cost' => 3.50],
                ['name' => 'Composite Decking (sq ft)', 'quantity' => 320, 'unit_cost' => 8],
                ['name' => 'Concrete Footings', 'quantity' => 8, 'unit_cost' => 45],
                ['name' => 'Hardware & Fasteners', 'quantity' => 1, 'unit_cost' => 350],
                ['name' => 'Railing System (linear ft)', 'quantity' => 40, 'unit_cost' => 35],
            ],
            'overhead_percent' => 10,
            'profit_percent' => 18,
            'notes' => '16x20 deck with stairs. Permit required.',
        ]);

        Estimate::create([
            'user_id' => $user->id,
            'name' => 'Martinez Bathroom Renovation',
            'project_type' => 'renovation',
            'labor_hours' => 80,
            'labor_rate' => 70,
            'materials' => [
                ['name' => 'Vanity w/ Sink', 'quantity' => 1, 'unit_cost' => 1200],
                ['name' => 'Shower Tile (sq ft)', 'quantity' => 65, 'unit_cost' => 18],
                ['name' => 'Shower Door (glass)', 'quantity' => 1, 'unit_cost' => 950],
                ['name' => 'Toilet', 'quantity' => 1, 'unit_cost' => 380],
                ['name' => 'Floor Tile (sq ft)', 'quantity' => 48, 'unit_cost' => 14],
                ['name' => 'Plumbing Rough-in', 'quantity' => 1, 'unit_cost' => 600],
            ],
            'overhead_percent' => 12,
            'profit_percent' => 22,
        ]);
    }
}

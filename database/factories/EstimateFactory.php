<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estimate>
 */
class EstimateFactory extends Factory
{
    public function definition(): array
    {
        $projectTypes = ['residential', 'commercial', 'renovation', 'new_construction'];
        $projectNames = [
            'Kitchen Remodel', 'Bathroom Renovation', 'Deck Build', 'Roof Replacement',
            'Office Buildout', 'Basement Finishing', 'Fence Installation', 'Garage Addition',
            'Patio Extension', 'Window Replacement',
        ];

        $materialSets = [
            [
                ['name' => 'Lumber (2x4)', 'quantity' => fake()->numberBetween(20, 100), 'unit_cost' => 4.50],
                ['name' => 'Plywood Sheets', 'quantity' => fake()->numberBetween(5, 30), 'unit_cost' => 35.00],
                ['name' => 'Screws (box)', 'quantity' => fake()->numberBetween(2, 10), 'unit_cost' => 12.00],
                ['name' => 'Concrete (bags)', 'quantity' => fake()->numberBetween(5, 20), 'unit_cost' => 6.50],
            ],
            [
                ['name' => 'Drywall Sheets', 'quantity' => fake()->numberBetween(10, 50), 'unit_cost' => 15.00],
                ['name' => 'Joint Compound', 'quantity' => fake()->numberBetween(2, 8), 'unit_cost' => 18.00],
                ['name' => 'Paint (gallon)', 'quantity' => fake()->numberBetween(3, 12), 'unit_cost' => 45.00],
                ['name' => 'Trim Molding (ft)', 'quantity' => fake()->numberBetween(50, 200), 'unit_cost' => 2.50],
            ],
            [
                ['name' => 'Roofing Shingles (bundle)', 'quantity' => fake()->numberBetween(15, 40), 'unit_cost' => 35.00],
                ['name' => 'Underlayment Roll', 'quantity' => fake()->numberBetween(3, 10), 'unit_cost' => 55.00],
                ['name' => 'Flashing (ft)', 'quantity' => fake()->numberBetween(20, 60), 'unit_cost' => 3.00],
                ['name' => 'Ridge Vent (ft)', 'quantity' => fake()->numberBetween(10, 30), 'unit_cost' => 5.50],
            ],
        ];

        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement($projectNames) . ' - ' . fake()->lastName(),
            'project_type' => fake()->randomElement($projectTypes),
            'labor_hours' => fake()->numberBetween(8, 200),
            'labor_rate' => fake()->randomElement([45, 55, 65, 75, 85, 95]),
            'materials' => fake()->randomElement($materialSets),
            'overhead_percent' => fake()->randomElement([8, 10, 12, 15]),
            'profit_percent' => fake()->randomElement([10, 15, 20, 25]),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}

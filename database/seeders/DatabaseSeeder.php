<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create test users with different roles
        $qualityAssuranceRole = \App\Models\Role::where('name', 'quality_assurance')->first();
        $simulationManagerRole = \App\Models\Role::where('name', 'simulation_manager')->first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'role_id' => $qualityAssuranceRole->id,
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Simulation Manager',
            'email' => 'simulation@example.com',
            'role' => 'user',
            'role_id' => $simulationManagerRole->id,
        ]);

        // Seed feedback data
        $this->call(FeedbackSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set the first user as admin
        $firstUser = \App\Models\User::first();
        if ($firstUser) {
            $firstUser->update(['role' => 'admin']);
            $this->command->info('First user set as admin: ' . $firstUser->email);
        }

        // Set all other users as regular users
        \App\Models\User::where('id', '!=', $firstUser?->id ?? 0)
            ->update(['role' => 'user']);
    }
}

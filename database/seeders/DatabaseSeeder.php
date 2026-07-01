<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur de test principal
        $user = User::factory()->create([
            'login'    => 'testuser',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 5 utilisateurs supplémentaires avec 3 annonces chacun
        User::factory(5)->create()->each(function ($u) {
            Ad::factory(3)->create(['user_id' => $u->id]);
        });

        // 10 annonces pour le user principal
        Ad::factory(10)->create(['user_id' => $user->id]);
    }
}

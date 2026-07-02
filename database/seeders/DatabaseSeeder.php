<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $imageUrls = [
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1503602642458-232111445657?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1516455590571-18256e5bb9ff?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1490806843283-8b470fe87b15?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1000&q=80',
        ];

        // Compte administrateur de démonstration.
        User::factory()->create([
            'login'    => 'admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Utilisateur principal de démonstration.
        $user = User::factory()->create([
            'login'    => 'testuser',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 5 utilisateurs supplémentaires avec 3 annonces chacun.
        User::factory(5)->create()->each(function ($u) use ($imageUrls) {
            Ad::factory(3)->create([
                'user_id' => $u->id,
                'photo'   => Arr::random($imageUrls),
            ]);
        });

        // 10 annonces pour le user principal avec images de démonstration.
        Ad::factory(10)->create([
            'user_id' => $user->id,
            'photo'   => fn () => Arr::random($imageUrls),
        ]);
    }
}

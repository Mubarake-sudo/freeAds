<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin VORTEX',
            'login' => 'admin',
            'email' => 'admin@vortex.com',
            'password' => Hash::make('password123'),
            'phone_number' => '+225 07 00 00 00 01',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $clients = [
            ['login' => 'sofia', 'name' => 'Sofia K.', 'email' => 'sofia@vortex.com', 'phone_number' => '+225 07 10 20 30 01'],
            ['login' => 'yann', 'name' => 'Yann D.', 'email' => 'yann@vortex.com', 'phone_number' => '+225 07 11 22 33 44'],
            ['login' => 'mila', 'name' => 'Mila T.', 'email' => 'mila@vortex.com', 'phone_number' => '+225 07 12 34 56 78'],
        ];

        $clientUsers = [];

        foreach ($clients as $client) {
            $clientUsers[] = User::create([
                'name' => $client['name'],
                'login' => $client['login'],
                'email' => $client['email'],
                'password' => Hash::make('password123'),
                'phone_number' => $client['phone_number'],
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
        }

        $ads = [
            ['user' => $admin, 'title' => 'Nintendo Switch OLED', 'category' => 'Jeux vidéo', 'description' => 'Console Nintendo Switch OLED en excellent état, avec 2 manettes joy-con et boîte d’origine.', 'price' => 250000, 'location' => 'Abidjan', 'condition' => 'new', 'photo' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[0], 'title' => 'Veste Streetwear Oversized', 'category' => 'Vêtements', 'description' => 'Veste oversize premium, coupe loose, très confortable pour le quotidien ou les sorties.', 'price' => 42000, 'location' => 'Cocody', 'condition' => 'good', 'photo' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[1], 'title' => 'iPad Pro M4', 'category' => 'Informatique', 'description' => 'iPad Pro M4 11 pouces, très peu utilisé, avec étui et accessoires.', 'price' => 820000, 'location' => 'Yopougon', 'condition' => 'good', 'photo' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[2], 'title' => 'Appareil Photo Canon Reflex', 'category' => 'Électronique', 'description' => 'Canon EOS 80D avec objectif 18-55 mm, parfait pour la photo de portrait et la vidéo.', 'price' => 550000, 'location' => 'Plateau', 'condition' => 'used', 'photo' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80'],
            ['user' => $admin, 'title' => 'Chaussures de sport premium', 'category' => 'Sport', 'description' => 'Chaussures de running en très bon état, taille 42, peu portées.', 'price' => 36000, 'location' => 'Marcory', 'condition' => 'good', 'photo' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[1], 'title' => 'Moniteur gaming 27 pouces', 'category' => 'Électronique', 'description' => 'Moniteur 27 pouces Full HD, très net, avec faible temps de réponse.', 'price' => 195000, 'location' => 'Treichville', 'condition' => 'new', 'photo' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[0], 'title' => 'Bicycle urbaine', 'category' => 'Sport', 'description' => 'Vélo urbain de qualité, cadre aluminium, freins à disque et accessoires.', 'price' => 180000, 'location' => 'Koumassi', 'condition' => 'good', 'photo' => 'https://images.unsplash.com/photo-1541625602330-2277a4c46182?auto=format&fit=crop&w=900&q=80'],
            ['user' => $clientUsers[2], 'title' => 'Casque audio sans fil', 'category' => 'Électronique', 'description' => 'Casque Bluetooth à réduction de bruit, son clair et autonomie élevée.', 'price' => 65000, 'location' => 'Le Plateau', 'condition' => 'new', 'photo' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=900&q=80'],
        ];

        foreach ($ads as $ad) {
            Ad::create([
                'user_id' => $ad['user']->id,
                'title' => $ad['title'],
                'category' => $ad['category'],
                'description' => $ad['description'],
                'price' => $ad['price'],
                'location' => $ad['location'],
                'condition' => $ad['condition'],
                'photo' => $ad['photo'],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('platforms')->insert([
            ['id' => 1, 'name' => 'X', 'url' => 'https://x.com', 'warna' => '#000000'],
            ['id' => 2, 'name' => 'Instagram', 'url' => 'https://instagram.com', 'warna' => '#E1306C'],
            ['id' => 3, 'name' => 'Facebook', 'url' => 'https://facebook.com', 'warna' => '#1877F2'],
            ['id' => 4, 'name' => 'TikTok', 'url' => 'https://tiktok.com', 'warna' => '#010101'],
            ['id' => 5, 'name' => 'YouTube', 'url' => 'https://youtube.com', 'warna' => '#FF0000'],
        ]);

        User::factory()->admin()->create([
            'name' => 'AdminUtama',
            'email' => 'zidnolmenol@gmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ]);

        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admindiskominfo@admin.com',
            'password' => Hash::make(env('ADMIN_PASSWORD_1')),
        ]);

    }
}

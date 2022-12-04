<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AboutInfo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);
        AboutInfo::create([
            'user' => 1,
            'body' => 'In 2014, Lucky Fuds Catering Services was established. Making your catering experience as stress-free as possible is our main focus. We pledged to constantly go above and beyond for our customers. Our catering services are perfect for birthdays, weddings, christenings, debuts, private parties, and more. Additionally, we have equipment rentals that you may use to transform any space at your event. We can provide tables, chairs, linens, and more. Let us make your event stand out with our finishing touches.
            Contact Info
            Address: Balaoan, La Union, Philippines, 2517
            Phone: 0919 222 5291
            
            Follow Us on https://www.facebook.com/LuckyFuds'
        ]);

    }
}

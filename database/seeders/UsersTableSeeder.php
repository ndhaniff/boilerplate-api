<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'first_name' => 'Admin',
            'last_name' => 'Default',
            'email' => 'admin@admin.com',
            'phone' => '+60 13-234 5678',
            'dob' => '1993-01-01',
            'country_code' => 'MY',
            'avatar' => 'https://via.placeholder.com/45x45.png/0099dd?text=ratione',
            'is_king' => 0,
            'last_login_at' => now(),
            'email_verified_at' => now(),
            'password' => bcrypt('aaaaaa'), // password
            'remember_token' => Str::random(10),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\InternalAction;
use Illuminate\Database\Seeder;

class InternalActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InternalAction::insert([
            'action' => 'king_mode',
            'is_active' => 0,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}

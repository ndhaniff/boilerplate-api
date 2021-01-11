<?php

namespace Database\Seeders;

use App\Models\AccessGroup;
use App\Models\AccessGroupMember;
use App\Models\AccessPrivilege;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AccessGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccessGroup::insert([
            [
                'name' => 'SuperAdmin',
                'is_superuser' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'Admin',
                'is_superuser' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'Normal',
                'is_superuser' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);

        AccessPrivilege::insert([
            [
                'code' => 1,
                'name' => 'Manage Users',
                'description' => 'Manage users (CRUD)',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'code' => 2,
                'name' => 'Manage Access Group',
                'description' => 'Manage ACL',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);

        AccessGroupMember::insert([
            [
                'access_group_id' => 1,
                'user_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);
    }
}

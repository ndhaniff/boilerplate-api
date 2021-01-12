<?php

namespace App\Helpers\Traits;

use App\Models\AccessGroupMember;

trait AclUtil
{
    public function assignGroup($data)
    {
        return AccessGroupMember::firstOrCreate($data);
    }
}
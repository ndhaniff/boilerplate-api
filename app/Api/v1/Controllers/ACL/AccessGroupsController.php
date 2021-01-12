<?php

namespace App\Api\v1\Controllers\ACL;

use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;

class AccessGroupsController extends Controller
{
    public function assign(Request $request)
    {
        $user = auth()->user();
        $data = $request->only([
            'user_id',
            'access_group_id'
        ]);

        if ($user->assignGroup($data)) {
            return response()->json([
                'message' => __('acl.assign_group.success'),
            ], 200);
        } else {
            throw new ResourceException(__('acl.assign_group.failed'));
        }
    }
}

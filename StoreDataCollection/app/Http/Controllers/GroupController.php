<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Devices;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function getByUserId(User $user)
    {
        return $user->groups()->get()->mapInto(GroupResource::class);
    }

    public function getByGroupId(Groups $group)
    {
        return new GroupResource($group);
    }

    public function getAllGroups()
    {
        return Groups::all()->mapInto(GroupResource::class);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $group = Groups::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'uuid' =>  Str::uuid(),
        ]);

        return new GroupResource($group);
    }

    public function update(Request $request, Groups $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->name = $request->name;
        $group->save();

        return new GroupResource($group);
    }

    public function delete(Groups $group)
    {
        $group->delete();
        return response()->json([], 204);
    }
}

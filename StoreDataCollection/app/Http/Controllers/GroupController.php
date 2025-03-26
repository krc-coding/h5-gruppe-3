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

    public function addDeviceToGroup(Request $request, Groups $group)
    {
        $validated = $request->validate([
            'devicesIds' => 'required|array',
            'devicesIds.*' => 'required|exists:devices,id',
        ]);

        $group->devices()->attach($validated['devicesIds']);
        return response()->json(['message' => 'Devices attached successfully']);
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

    public function removeDeviceFromGroup(Groups $group, Devices $device)
    {
        $group->devices()->detach($device);
        return response()->json(['message' => 'Devices detached succesfully']);
    }

    public function delete(Groups $group)
    {
        $group->delete();
        return response()->json([], 204);
    }
}

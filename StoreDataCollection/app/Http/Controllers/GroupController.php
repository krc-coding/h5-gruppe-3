<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\GroupResource;
use App\Models\Data;
use App\Models\Devices;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string'
        ]);
        $group = Groups::where('uuid', $request->uuid)->first();
        if ($group) {
            return Data::whereIn('device_id', $group->devices()->pluck('id'))->get()->mapInto(DataResource::class);
        }

        $device = Devices::where('uuid', $request->uuid)->first();
        if ($device) {
            return $device->data()->get()->mapInto(DataResource::class);
        }

        return response()->json(['message' => 'No device found'], 404);
    }

    public function getByUserId(User $user)
    {
        return $user->groups()->get()->mapInto(GroupResource::class);
    }

    public function getByGroupId(Groups $group)
    {
        return new GroupResource($group);
    }

    public function getByGroupUuid(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:groups,uuid',
        ]);

        return new GroupResource(Groups::where('uuid', $request->uuid)->first());
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
        $request->validate([
            'devicesUuids' => 'required|array',
            'devicesUuids.*' => 'required|exists:devices,uuid',
        ]);

        $devices = Devices::whereIn('uuid', $request->devicesUuids)->get();
        $group->devices()->attach($devices);
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

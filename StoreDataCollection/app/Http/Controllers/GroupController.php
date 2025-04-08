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
            // If a group with the searched UUID exists.
            // Then return the data from all devices in the group.
            return Data::whereIn('device_id', $group->devices()->pluck('id'))->get()->mapInto(DataResource::class);
        }

        $device = Devices::where('uuid', $request->uuid)->first();
        if ($device) { // If we find a device on the uuid
            // We get the data from the relation.
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

        // Find the first group with the same uuid and makes an group resource out of it.
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
            // The dot star means that it's going through all the elements in the array.
            'devicesUuids.*' => 'required|exists:devices,uuid',
        ]);

        $existingIds = $group->devices()->pluck('devices.id')->toArray();

        // 'whereIn' gives us an array of all the devices there is in the array.
        $devices = Devices::whereIn('uuid', $request->devicesUuids)
            ->whereNotIn('id', $existingIds)
            ->get();

        if (count($request->devicesUuids) !== count($devices)) {
            return response(400);
        }

        // Only add devices to the group there isn't there before.
        $group->devices()->syncWithoutDetaching($devices);
        return response(204);
    }

    public function update(Request $request, Groups $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->name = $request->name;
        $group->save(); // Save changes on database

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

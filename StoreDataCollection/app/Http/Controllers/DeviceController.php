<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeviceResource;
use App\Models\Devices;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function getDevice(Devices $device)
    {
        return new DeviceResource($device);
    }

    public function getByGroup(Groups $group)
    {
        return $group->devices()->get()->mapInto(DeviceResource::class);
    }

    public function getAllDevices()
    {
        return Devices::all()->mapInto(DeviceResource::class);
    }

    public function createDevice()
    {
        $device = Devices::create([
            'uuid' => Str::uuid(),
        ]);
        return new DeviceResource($device);
    }

    public function delete(Devices $device)
    {
        $device->delete();
        return response()->json([], 204);
    }
}

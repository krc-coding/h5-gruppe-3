<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\Data;
use App\Models\Devices;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function getAllData()
    {
        return Data::all()->mapInto(DataResource::class);
    }

    public function getByGruop(Groups $group)
    {
        $deviceIds = $group->devices()->get()->pluck('id');
        return Data::whereIn('device_id', $deviceIds)->get()->mapInto(DataResource::class);
    }

    public function getByDevice(Devices $device)
    {
        return Data::where('device_id', $device->id)->get()->mapInto(DataResource::class);
    }

    public function createData(Request $request)
    {
        $request->validate([
            'people' => 'required|integer',
            'products_pr_person' => 'required|integer',
            'total_value' => 'required|decimal:0,2',
            'product_categories' => 'required|json',
            'packages_received' => 'nullable|integer',
            'packages_delivered' => 'nullable|integer',
            'device_uuid' => 'required|exists:devices,uuid',
        ]);

        $device = Devices::where('uuid', $request->device_uuid)->first();
        $data = Data::create([
            'people' => $request->people,
            'products_pr_person' => $request->products_pr_person,
            'total_value' => $request->total_value,
            'product_categories' => $request->product_categories,
            'packages_received' => $request->packages_received,
            'packages_delivered' => $request->packages_delivered,
            'device_id' => $device->id,
            'data_recorded_at' => Date::now(),
        ]);

        return new DataResource($data);
    }
}

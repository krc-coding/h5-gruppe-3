<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function getAllData()
    {
        return DB::table('data')->get()->mapInto(DataResource::class);
    }

    public function createData(Request $request)
    {
        $request->validate([
            'people' => 'required|integer',
            'products_pr_person' => 'required|integer',
            'total_value' => 'required|float',
            'reward' => 'required|integer',
            'product_categories' => 'required|json',
            'packages_received' => 'nullable|integer',
            'packages_delivered' => 'nullable|integer',
            'devices_id' => 'required|exists:devices,id',
        ]);

        $data = Data::create([
            'people' => $request->poeples,
            'products_pr_person' => $request->products_pr_person,
            'total_value' => $request->total_value,
            'reward' => $request->reward,
            'product_categories' => $request->product_categories,
            'packages_received' => $request->packages_received,
            'packages_delivered' => $request->packages_delivered,
            'devices_id' => $request->devices_id,
        ]);

        return new DataResource($data);
    }

    public function updateData(Request $request, Data $data)
    {
        $request->validate([
            'people' => 'required|integer',
            'products_pr_person' => 'required|integer',
            'total_value' => 'required|float',
            'reward' => 'required|integer',
            'product_categories' => 'required|json',
            'packages_received' => 'nullable|integer',
            'packages_delivered' => 'nullable|integer',
            'devices_id' => 'required|exists:devices,id',
        ]);

        $data->people = $request->poeples;
        $data->products_pr_person = $request->products_pr_person;
        $data->total_value = $request->total_value;
        $data->reward = $request->reward;
        $data->product_categories = $request->product_categories;
        $data->packages_received = $request->packages_received;
        $data->packages_delivered = $request->packages_delivered;
        $data->devices_id = $request->devices_id;

        $data->save();

        return new DataResource($data);
    }

    public function delete(Data $data)
    {
        $data->delete();
        return response()->json([], 204);
    }
}

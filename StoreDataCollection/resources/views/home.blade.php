@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Device Data</h1>

    <!-- Search Form route need to be changed to match the api -->
    <form action="{{ route('devices.search') }}" method="GET">
        <div class="mb-3">
            <label for="uuid" class="form-label">Enter Device UUID or leave empty for all data(Optional):</label>
            <input type="text" id="uuid" name="uuid" class="form-control" placeholder="Enter device UUID">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Results Section -->
    @if(isset($deviceData))
    <div class="mt-4">
        <h2>Search Results</h2>
        @if($deviceData->isEmpty())
        <p>No data found.</p>
        @else
        <table class="table">
            <thead>
                <tr>
                    <th>people</th>
                    <th>products pr person</th>
                    <th>total value</th>
                    <th>product categori</th>
                    <th>packages received</th>
                    <th>packages delivered</th>
                    <th>data recorded at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deviceData as $device)
                <tr>
                    <td>{{ $device->peoples }}</td>
                    <td>{{ $device->products_pr_person }}</td>
                    <td>{{ $device->total_values }}</td>
                    <td>{{ $device->product_categories }}</td>
                    <td>{{ $device->packages_received }}</td>
                    <td>{{ $device->packages_delivered }}</td>
                    <td>{{ $device->data_recorded_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif
</div>
@endsection

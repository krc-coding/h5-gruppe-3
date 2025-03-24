<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Device Data</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional CSS -->
</head>
<body>

<div class="container">
    <h1>Search Device Data</h1>

    <!-- Search Form -->
    <form id="searchForm">
        <div class="mb-3">
            <label for="uuid" class="form-label">Enter Device UUID or leave empty for all data (Optional):</label>
            <input type="text" id="uuid" name="uuid" class="form-control" placeholder="Enter device UUID">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Results Section -->
    <div id="results"></div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let uuid = document.getElementById('uuid').value.trim();
    let apiUrl = uuid ? `/api/data/device/${uuid}` : `/api/data`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            let resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = ''; // Clear previous results

            if (data.length === 0) {
                resultsDiv.innerHTML = '<p>No data found.</p>';
                return;
            }

            let table = `<table class="table">
                <thead>
                    <tr>
                        <th>People</th>
                        <th>Products per Person</th>
                        <th>Total Value</th>
                        <th>Product Category</th>
                        <th>Packages Received</th>
                        <th>Packages Delivered</th>
                        <th>Data Recorded At</th>
                    </tr>
                </thead>
                <tbody>`;

            data.forEach(device => {
                table += `<tr>
                    <td>${device.peoples}</td>
                    <td>${device.products_pr_person}</td>
                    <td>${device.total_values}</td>
                    <td>${device.product_categories}</td>
                    <td>${device.packages_received}</td>
                    <td>${device.packages_delivered}</td>
                    <td>${device.data_recorded_at}</td>
                </tr>`;
            });

            table += `</tbody></table>`;
            resultsDiv.innerHTML = table;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('results').innerHTML = '<p class="text-danger">Error fetching data.</p>';
        });
});
</script>

</body>
</html>

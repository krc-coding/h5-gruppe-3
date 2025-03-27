<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

</head>

<body>
    <!-- login Modal -->
    <x-login-modal />
    <x-sign-up-modal />

    <!-- Main content -->
    <div class="container center-container">
        <button id="loginBtn" class="login-btn">Login</button>

        <h1>Search Device Data</h1>

        <!-- Search Form -->
        <form id="searchForm" class="form-container">
            <div class="mb-3">
                <label for="uuid" class="form-label">Enter Device UUID or leave empty for all data:</label>
                <input type="text" id="uuid" name="uuid" class="form-control"
                    placeholder="Enter device UUID">
            </div>
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>

        <!-- Results Section -->
        <div id="results" class="table-container"></div>
    </div>

    <script>
        //search function and creation of data tables
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let uuid = document.getElementById('uuid').value.trim();
            let apiUrl = uuid ? `/api/data/device/${uuid}` : `/api/data`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    let resultsDiv = document.getElementById('results');
                    resultsDiv.innerHTML = '';

                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<p>No data found.</p>';
                        return;
                    }

                    data.sort((a, b) => new Date(b.data_recorded_at) - new Date(a.data_recorded_at));

                    let allCategories = new Set();
                    data.forEach(device => {
                        let categories = JSON.parse(device
                            .product_categories);
                        Object.keys(categories).forEach(category => allCategories.add(category));
                    });

                    let categoryColumns = Array.from(allCategories);

                    let table = `<table class="table">
                        <thead>
                        <tr>
                        <th>People</th>
                        <th>Products per Person</th>
                        <th>Total Value</th>`;

                    categoryColumns.forEach(category => {
                        table += `<th>${category}</th>`;
                    });

                    table += `
                        <th>Packages Received</th>
                        <th>Packages Delivered</th>
                        <th>Data Recorded At</th>
                        </tr>
                        </thead>
                        <tbody>`;

                    data.forEach(device => {
                        let categories = JSON.parse(device
                            .product_categories);

                        table += `<tr>
                            <td>${device.people}</td>
                            <td>${device.products_pr_person}</td>
                            <td>${device.total_value}</td>`;

                        categoryColumns.forEach(category => {
                            table +=
                                `<td>${categories[category] !== undefined ? categories[category] : 0}</td>`;
                        });

                        table += `
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
                    document.getElementById('results').innerHTML =
                        '<p class="text-danger">Error fetching data.</p>';
                });
        });
    </script>

</body>

</html>

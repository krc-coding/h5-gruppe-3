<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

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
            let apiUrl = uuid ? `/api/search?uuid=${uuid}` : `/api/data`;

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
                        let categories = JSON.parse(device.product_categories);
                        Object.keys(categories).forEach(category => allCategories.add(category));
                    });

                    let categoryColumns = Array.from(allCategories);
                    let table = `<table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">People <span></span></th>
                        <th onclick="sortTable(1)">Products per Person <span></span></th>
                        <th onclick="sortTable(2)">Total Value <span></span></th>`;

                    categoryColumns.forEach((category, index) => {
                        table += `<th onclick="sortTable(${index + 3})">${category} <span></span></th>`;
                    });

                    //indexes with categoryColumns.length to allow for more categories without having to change anything
                    table += `
                        <th onclick="sortTable(${categoryColumns.length + 3})">Packages Received <span></span></th>
                        <th onclick="sortTable(${categoryColumns.length + 4})">Packages Delivered <span></span></th>
                        <th onclick="sortTable(${categoryColumns.length + 5})">Data Recorded At <span>▼</span></th>
                    </tr>
                </thead>
                <tbody>`;

                    // fills in the data
                    data.forEach(device => {
                        let categories = JSON.parse(device.product_categories);
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

        // Sorting function
        function sortTable(columnIndex) {
            let table = document.getElementById("dataTable");
            let tbody = table.querySelector("tbody");
            let rows = Array.from(tbody.rows);
            let headers = table.querySelectorAll("th span");

            let isAscending = table.dataset.sortColumn == columnIndex && table.dataset.order == "asc";
            table.dataset.sortColumn = columnIndex;
            table.dataset.order = isAscending ? "desc" : "asc";

            rows.sort((rowA, rowB) => {
                let cellA = rowA.cells[columnIndex].innerText.trim();
                let cellB = rowB.cells[columnIndex].innerText.trim();

                // Try parsing as a number
                let numA = parseFloat(cellA.replace(/,/g, ""));
                let numB = parseFloat(cellB.replace(/,/g, ""));
                if (!isNaN(numA) && !isNaN(numB)) {
                    return isAscending ? numA - numB : numB - numA;
                }

                // Try parsing as date
                let dateA = new Date(cellA);
                let dateB = new Date(cellB);
                if (!isNaN(dateA) && !isNaN(dateB)) {
                    return isAscending ? dateA - dateB : dateB - dateA;
                }

                // Default: Sort as string
                return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            });

            rows.forEach(row => tbody.appendChild(row));

            // Update sorting arrow indicators
            headers.forEach(span => (span.innerHTML = ""));
            headers[columnIndex].innerHTML = isAscending ? " ▲" : " ▼";
        }
    </script>

</body>

</html>

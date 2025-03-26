<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <div id="loginModal" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login</h2>
            <form id="loginForm">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    
    <div class="container center-container">
        <button id="loginBtn" class="login-btn">Login</button>
        
        <h1>Search Device Data</h1>

        <!-- Search Form -->
        <form id="searchForm" class="form-container">
            <div class="mb-3">
                <label for="uuid" class="form-label">Enter Device UUID or leave empty for all data:</label>
                <input type="text" id="uuid" name="uuid" class="form-control" placeholder="Enter device UUID">
            </div>
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>

        <!-- Results Section -->
        <div id="results" class="table-container"></div>
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
                    resultsDiv.innerHTML = '';

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

        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("loginModal");
            let btn = document.getElementById("loginBtn");
            let closeBtn = document.querySelector(".close");
            
            btn.onclick = function() {
                modal.style.display = "flex"; // Show modal
                document.body.classList.add("modal-open"); 
            }
            
            closeBtn.onclick = function() {
                modal.style.display = "none"; // Hide modal
                document.body.classList.remove("modal-open");
            }
            
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none"; 
                    document.body.classList.remove("modal-open");
                }
            }
        });
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <!-- Login popup -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login</h2>
            <form id="loginForm">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">

                <button type="submit" class="btn btn-primary" id="loginBtnSubmit">Login</button>
                <button type="submit" class="btn btn-primary" id="signUpBtnSubmit">Sign up</button>
            </form>
        </div>
    </div>

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

        // Login/Sign up form
        document.addEventListener("DOMContentLoaded", function() {
            let loginForm = document.getElementById("loginForm");

            document.getElementById("loginBtnSubmit").addEventListener("click", function(event) {
                event.preventDefault();
                if (loginForm.checkValidity()) {
                    handleFormSubmit("/api/login"); //need the accual api
                } else {
                    loginForm.reportValidity();
                }
            });

            document.getElementById("signUpBtnSubmit").addEventListener("click", function(event) {
                event.preventDefault();
                if (loginForm.checkValidity()) {
                    handleFormSubmit("/api/signup"); //need the accual api
                } else {
                    loginForm.reportValidity();
                }
            });

            function handleFormSubmit(apiUrl) {
                let email = document.getElementById("email").value;
                let password = document.getElementById("password").value;

                fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            email,
                            password
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            document.getElementById("loginModal").style.display =
                                "none";
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred. Please try again.");
                    });
            }
        });

        // open/close login modal
        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("loginModal");
            let btn = document.getElementById("loginBtn");
            let closeBtn = document.querySelector(".close");

            btn.onclick = function() {
                modal.style.display = "flex";
                document.body.classList.add("modal-open");
            }

            closeBtn.onclick = function() {
                modal.style.display = "none";
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

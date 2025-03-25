<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('../css/home.css') }}">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            width: 90%;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }
        .container:hover {
            transform: translateY(-5px);
        }
        h1 {
            font-size: 26px;
            font-weight: bold;
            
            margin-bottom: 20px;
        }
        .form-container {
            width: 100%;
        }
        .form-label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            margin-top: 5px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.3);   
        }
        .btn-primary {
    background: #007bff;
    border: none;
    padding: 12px;
    width: 100%;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease-in-out, transform 0.2s;
}

.btn-primary:hover {
    background: #0056b3;
    transform: scale(1.05);
}

/* 🌟 Results Section */
.table-container {
    margin-top: 20px;
    max-width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
}

.table th, .table td {
    padding: 12px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
}

.table th {
    background: rgba(255, 255, 255, 0.3);
    font-weight: bold;
}

.table tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.1);
}

/* 🌟 Responsive Design */
@media (max-width: 768px) {
    .container {
        max-width: 90%;
        padding: 20px;
    }
    
    input[type="text"] {
        font-size: 14px;
    }

    .btn-primary {
        font-size: 14px;
    }

    h1 {
        font-size: 22px;
    }
}
        
    </style>
</head>
<body>

<div class="container center-container">
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
</script>

</body>
</html>

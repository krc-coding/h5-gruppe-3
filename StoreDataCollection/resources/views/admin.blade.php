<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">

        <!-- Navigation Buttons -->
        <div class="button-container">
            <button onclick="goHome()" class="btn-nav">Data Search</button>
            <button onclick="logout()" class="btn-nav logout">Logout</button>
        </div>

        <h1>Manage Groups</h1>

        <!-- Create Group -->
        <div>
            <input type="text" id="groupName" placeholder="Enter new group name">
            <button onclick="createGroup()" class="btn btn-primary w-100">Create Group</button>
        </div>

        <!-- Group List -->
        <h2>Groups</h2>
        <table id="groupTable" border="1">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Devices</th>
                    <th>Actions</th>
                    <th>Group UUID</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchGroups();
        });

        function fetchGroups() {
            let userID = localStorage.getItem("userID");
            fetch(`/api/group/user/${userID}`)
                .then(response => response.json())
                .then(groups => {
                    let tableBody = document.querySelector("#groupTable tbody");
                    tableBody.innerHTML = "";

                    groups.forEach(group => {
                        let row = document.createElement("tr");

                        row.innerHTML = `
                    <td>${group.name}</td>
                    <td id="devices-${group.id}">Loading...</td>
                    <td>
                        <button onclick="deleteGroup('${group.id}')" class="btn-danger">Delete Group</button>
                        <input type="text" id="device-${group.id}" placeholder="Device UUID" class="input-text">
                        <button onclick="addDevice('${group.id}')" class="btn-primary">Add Device</button>
                    </td>
                    <td>${group.uuid}</td>
                `;

                        tableBody.appendChild(row);
                        fetchDevicesForGroup(group.id);
                    });
                })
                .catch(error => console.error("Error loading groups:", error));
        }

        function fetchDevicesForGroup(groupId) {
            fetch(`/api/device/group/${groupId}`)
                .then(response => response.json())
                .then(devices => {
                    let deviceContainer = document.getElementById(`devices-${groupId}`);

                    if (devices.length === 0) {
                        deviceContainer.innerHTML = "<span class='text-muted'>No devices</span>";
                        return;
                    }

                    deviceContainer.innerHTML = devices.map(device =>
                        `<span>${device.uuid} 
                    <button onclick="removeDevice('${groupId}', '${device.id}')" class="btn-warning">Remove Device</button>
                </span>`
                    ).join("<br>");
                })
                .catch(error => console.error(`Error fetching devices for group ${groupId}:`, error));
        }

        function createGroup() {
            let groupName = document.getElementById("groupName").value.trim();
            if (!groupName) {
                alert("Group name cannot be empty!");
                return;
            }

            fetch("/api/group/create", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        user_id: localStorage.getItem("userID"),
                        name: groupName
                    })
                })
                .then(response => response.json())
                .then(() => {
                    document.getElementById("groupName").value = "";
                    fetchGroups();
                })
                .catch(error => console.error("Error creating group:", error));
        }

        function deleteGroup(groupId) {
            if (!confirm("Are you sure you want to delete this group?")) return;

            fetch(`/api/group/${groupId}`, {
                    method: "DELETE"
                })
                .then(() => fetchGroups())
                .catch(error => console.error("Error deleting group:", error));
        }

        function addDevice(groupId) {
            let deviceUUId = document.getElementById(`device-${groupId}`).value.trim();
            if (!deviceUUId) {
                alert("Enter a device UUID");
                return;
            }

            fetch(`/api/group/${groupId}/add`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        devicesUuids: [deviceUUId]
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message);
                        });
                    }
                    return response.json();
                })
                .then(() => fetchGroups())
                .catch(error => {
                    console.error("Error adding device:", error);
                    alert("Failed to add device: " + error.message);
                });
        }

        function removeDevice(groupId, deviceId) {
            fetch(`/api/group/${groupId}/remove/${deviceId}`, {
                    method: "PATCH"
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message);
                        });
                    }
                    return response.json();
                })
                .then(() => fetchGroups())
                .catch(error => {
                    console.error("Error adding device:", error);
                    alert("Failed to add device: " + error.message);
                });
        }

        function goHome() {
            window.location.href = "/";
        }

        function logout() {
            localStorage.removeItem("authToken");
            localStorage.removeItem("userID");
            localStorage.removeItem("userName");
            alert("Logged out successfully!");
            window.location.href = "/";
        }
    </script>
</body>

</html>

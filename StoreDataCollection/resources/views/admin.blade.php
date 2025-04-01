<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <div class="container">
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
            fetch(`/api/group/user/${user}`)
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.querySelector("#groupTable tbody");
                    tableBody.innerHTML = "";

                    data.forEach(group => {
                        let row = document.createElement("tr");

                        let deviceList = group.devices.map(device =>
                            `<span>${device.name} 
                                <button onclick="removeDevice('${group.id}', '${device.id}')">❌</button>
                            </span>`).join(", ");

                        row.innerHTML = `
                            <td>${group.name}</td>
                            <td>${deviceList || "No devices"}</td>
                            <td>
                                <button onclick="deleteGroup('${group.id}')">Delete Group</button>
                                <input type="text" id="device-${group.id}" placeholder="Device ID">
                                <button onclick="addDevice('${group.id}')">Add Device</button>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error loading groups:", error));
        }

        function createGroup() {
            let groupName = document.getElementById("groupName").value.trim();
            if (!groupName) {
                alert("Group name cannot be empty!");
                return;
            }

            fetch("/api/groups", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        name: groupName
                    })
                })
                .then(response => response.json())
                .then(() => {
                    document.getElementById("groupName").value = "";
                    fetchGroups(); // Refresh list
                })
                .catch(error => console.error("Error creating group:", error));
        }

        function deleteGroup(groupId) {
            if (!confirm("Are you sure you want to delete this group?")) return;

            fetch(`/api/groups/${groupId}`, {
                    method: "DELETE"
                })
                .then(() => fetchGroups()) // Refresh list
                .catch(error => console.error("Error deleting group:", error));
        }

        function addDevice(groupId) {
            let deviceId = document.getElementById(`device-${groupId}`).value.trim();
            if (!deviceId) {
                alert("Enter a device ID");
                return;
            }

            fetch(`/api/groups/${groupId}/devices`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        device_id: deviceId
                    })
                })
                .then(() => fetchGroups()) // Refresh list
                .catch(error => console.error("Error adding device:", error));
        }

        function removeDevice(groupId, deviceId) {
            fetch(`/api/groups/${groupId}/devices/${deviceId}`, {
                    method: "DELETE"
                })
                .then(() => fetchGroups()) // Refresh list
                .catch(error => console.error("Error removing device:", error));
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <a href="dashboard"><button>Back to Dashboard</button></a>
    <div class="users-container">
        <h2>User List</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>CP Number</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="userRow{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->password }}</td>
                        <td>{{ $user->cp_number }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->type }}</td>
                        <td>
                            @if ($user->status == 0)
                                <th>User Deleted</th>
                            @elseif ($user->type == 'admin')
                                <button onclick="deleteUser({{ $user->id }})">Delete User</button>
                                <button onclick="modifyUser({{ $user->id }})">Modify User</button>
                            @else
                                <button onclick="deleteUser({{ $user->id }})">Delete User</button>
                                <button onclick="makeAdmin({{ $user->id }})">Make Admin</button>
                                <button onclick="modifyUser({{ $user->id }})">Modify User</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="modifyUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUserModal()">&times;</span>
            <h2>Modify User</h2>
            <form id="modifyUserForm">
                @foreach ($users as $user)
                    <label for="newName{{ $user->id }}">Current Name: {{ $user->name }}</label>
                    <input type="text" id="newName{{ $user->id }}" name="newName{{ $user->id }}" style="display: none">
                    <button type="button" onclick="editField('newName{{ $user->id }}')">Change Name</button>

                    <br><br>

                    <label for="newEmail{{ $user->id }}">Current Email: {{ $user->email }}</label>
                    <input type="text" id="newEmail{{ $user->id }}" name="newEmail{{ $user->id }}" style="display: none">
                    <button type="button" onclick="editField('newEmail{{ $user->id }}')">Change Email</button>

                    <br><br>

                    <label for="newPassword{{ $user->id }}">Current Password: {{ $user->password }}</label>
                    <input type="text" id="newPassword{{ $user->id }}" name="newPassword{{ $user->id }}" style="display: none">
                    <button type="button" onclick="editField('newPassword{{ $user->id }}')">Change Password</button>

                    <br><br>

                    <label for="newCpNumber{{ $user->id }}">Current CP Number: {{ $user->cp_number }}</label>
                    <input type="text" id="newCpNumber{{ $user->id }}" name="newCpNumber{{ $user->id }}" style="display: none">
                    <button type="button" onclick="editField('newCpNumber{{ $user->id }}')">Change CP Number</button>

                    <br><br>

                    <label for="newAddress{{ $user->id }}">Current Address: {{ $user->address }}</label>
                    <input type="text" id="newAddress{{ $user->id }}" name="newAddress{{ $user->id }}" style="display: none">
                    <button type="button" onclick="editField('newAddress{{ $user->id }}')">Change Address</button>

                    <br><br>
                @endforeach
                <button type="button" onclick="saveChanges()">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function deleteUser(userId) {
            var confirmDelete = confirm("Are you sure you want to delete this user?");
            if (confirmDelete) {
                $.ajax({
                    type: "POST",
                    url: '/update-user-status/' + userId,
                    data: { status: 0 },
                    success: function(response) {
                        alert("User deleted successfully!");
                        location.reload();
                    },
                    error: function(error) {
                        alert("Failed to delete user. Please try again.");
                    }
                });
            }
        }

        function makeAdmin(userId) {
            var confirmMakeAdmin = confirm("Are you sure you want to make this user an admin?");
            if (confirmMakeAdmin) {
                $.ajax({
                    type: "POST",
                    url: '/update-user-type/' + userId,
                    data: { type: 'admin' },
                    success: function(response) {
                        alert("User made admin successfully!");
                        location.reload();
                    },
                    error: function(error) {
                        alert("Failed to make user an admin. Please try again.");
                    }
                });
            }
        }

        function modifyUser(userId) {
            document.getElementById('modifyUserForm').setAttribute('data-user-id', userId);

            $.ajax({
                type: "GET",
                url: "/get-user/" + userId,
                success: function(response) {
                    if (response && response.user) {
                        var user = response.user;
                        clearForm();
                        appendFormField('newName', 'Name', user.name);
                        appendFormField('newEmail', 'Email', user.email);
                        appendFormField('newPassword', 'Password', user.password);
                        appendFormField('newCpNumber', 'CP Number', user.cp_number);
                        appendFormField('newAddress', 'Address', user.address);
                        var saveChangesButton = document.createElement('button');
                        saveChangesButton.setAttribute('type', 'button');
                        saveChangesButton.setAttribute('onclick', 'saveChanges()');
                        saveChangesButton.textContent = 'Save Changes';
                        document.getElementById('modifyUserForm').appendChild(saveChangesButton);
                        document.getElementById('modifyUserModal').style.display = 'block';
                    } else {
                        alert("Failed to fetch user data. Please try again.");
                    }
                },
                error: function(error) {
                    alert("Failed to fetch user data. Please try again.");
                }
            });
        }

        function clearForm() {
            var form = document.getElementById('modifyUserForm');
            form.innerHTML = '';
        }

        function appendFormField(id, label, value) {
            var form = document.getElementById('modifyUserForm');

            var labelElement = document.createElement('label');
            labelElement.setAttribute('for', id);
            labelElement.textContent = label + ': ' + value;
            form.appendChild(labelElement);

            var inputElement = document.createElement('input');
            inputElement.setAttribute('type', 'text');
            inputElement.setAttribute('id', id);
            inputElement.setAttribute('name', id);
            inputElement.style.display = 'none';
            inputElement.value = value;
            form.appendChild(inputElement);

            var buttonElement = document.createElement('button');
            buttonElement.setAttribute('type', 'button');
            buttonElement.setAttribute('onclick', 'editField("' + id + '")');
            buttonElement.textContent = 'Change ' + label;
            form.appendChild(buttonElement);

            form.appendChild(document.createElement('br'));
            form.appendChild(document.createElement('br'));
        }

        function closeUserModal() {
            document.getElementById('modifyUserModal').style.display = 'none';
        }

        function editField(fieldId) {
            document.getElementById(fieldId).style.display = 'inline';
            document.querySelector(`label[for=${fieldId}]`).style.display = 'none';
        }

        function saveChanges() {
            var userId = document.getElementById('modifyUserForm').getAttribute('data-user-id');
            var newName = document.getElementById('newName').value;
            var newEmail = document.getElementById('newEmail').value;
            var newPassword = document.getElementById('newPassword').value;
            var newCpNumber = document.getElementById('newCpNumber').value;
            var newAddress = document.getElementById('newAddress').value;

            $.ajax({
                type: 'POST',
                url: '/modify-user/' + userId,
                data: {
                    newName: newName,
                    newEmail: newEmail,
                    newPassword: newPassword,
                    newCpNumber: newCpNumber,
                    newAddress: newAddress
                },
                success: function(response) {
                    closeUserModal();
                    alert('User modified successfully!');
                    location.reload();
                },
                error: function(error) {
                    alert('Failed to modify user. Please try again.');
                }
            });
        }

    </script>

</body>
</html>
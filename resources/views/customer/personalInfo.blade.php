<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Information</title>
    <style>
        .edit-mode input {
            display: none;
        }

        .view-mode span {
            display: inline;
        }
    </style>
</head>
<body>
    <a href="./dashboard"><button>Back</button></a>
    <h1>Personal Information</h1>
    
    <form method="POST" action="{{ route('updatePersonalInfo') }}">
        @csrf
        @method('PUT')
        <div>
            <p>Name: <span class="view-mode">{{ $user->name }}</span>
                <input class="edit-mode" type="text" style="display: none;" name="name" value="{{ $user->name }}" required>
            </p>
            <p>Email: <span class="view-mode">{{ $user->email }}</span>
                <input class="edit-mode" type="email" style="display: none;" name="email" value="{{ $user->email }}" required>
            </p>
            <p>Phone Number: <span class="view-mode">{{ $user->cp_number }}</span>
                <input class="edit-mode" type="text" style="display: none;" name="cp_number" value="{{ $user->cp_number }}">
            </p>
            <p>Address: <span class="view-mode">{{ $user->address }}</span>
                <input class="edit-mode" type="text" style="display: none;" name="address" value="{{ $user->address }}">
            </p>
        </div>

        <button type="button" onclick="toggleEditMode()">Edit Information</button>
        <button type="submit" class="edit-mode" style="display: none;">Save Changes</button>
    </form>

    <script>
        function toggleEditMode() {
            const editModeElements = document.querySelectorAll('.edit-mode');
            const viewModeElements = document.querySelectorAll('.view-mode span');

            editModeElements.forEach(element => {
                element.style.display = (element.style.display === 'none') ? 'inline' : 'none';
            });

            viewModeElements.forEach(element => {
                element.style.display = (element.style.display === 'none') ? 'inline' : 'none';
            });
        }
    </script>
</body>
</html>

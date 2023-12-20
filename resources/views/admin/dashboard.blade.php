<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="buttons">
        <a href="viewUsers"><button>View Users</button></a>
        <a href="viewProducts"><button>View Products</button></a>
        <a href="{{ route('logout') }}"><button>Logout</button></a>
    </div>
</body>

</html>

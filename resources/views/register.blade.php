<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="/register" method="post">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br><br>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
        <br><br>
        <label for="cp_number">Cellphone Number:</label>
        <input type="text" name="cp_number" required>
        <br><br>
        <label for="address">Address:</label>
        <input type="text" name="address" required>
        <br><br>
        <button type="submit">Register</button>
    </form>
    <a href="/">Already have an Account?</a>
</body>
</html>

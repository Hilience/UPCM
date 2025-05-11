<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - FH</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <h2>FH Login</h2>
@if(session('error'))
    <p class="error">{{ session('error') }}</p>
@endif
<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <label for="kennung">FH-Kennung:</label>
    <input type="text" name="kennung" required>

    <label for="password">Passwort:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>
</div>

<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>

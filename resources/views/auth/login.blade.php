<div>
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Gobog</title>
</head>
<body>
    <h1>Sistem Pengelolaan Koin Gobog</h1>
    <h2>Pasar Preng Sewu</h2>
    <hr>
    <h3>Login</h3>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color:red">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"
                   value="{{ old('username') }}" required autofocus>
        </div>
        <br>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
</div>

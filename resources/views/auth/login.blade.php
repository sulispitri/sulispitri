@extends('auth.layouts')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
</head><link href="{{ asset('css/login.css') }}" rel="stylesheet">
<body>
    <div class="login-container">
        <h1>Login</h1>
        <a href="{{ route('register') }}">Daftar</a>
        <form action="{{ route('authenticate') }}" method="post">
            @csrf
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
@endsection
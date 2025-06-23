<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

       
    </head>
    <body >
        <div style= "display:flex">
            <form method="POST" action="{{ route('register.store') }}" >
                 @csrf
                <h1>Crear cuenta</h1>
                <input type="email" name="email" placeholder="Correo electronico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">
                <button type="submit">Registrate</button>

            </form>
            @if (session('success'))
    <div style="color: green">{{ session('success') }}</div>
@endif

        </div>
        
    </body>
</html>
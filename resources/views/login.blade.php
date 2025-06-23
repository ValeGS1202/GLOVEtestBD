<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    </head>
    <body>
        <div style="display: flex; flex-direction: column; gap: 1rem">

        @if (session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <h1 style="text-align: center">Ingresar</h1>
                <input type="text" name="email" placeholder="Correo electronico" style= "align-self: center">
                <input type="password" name="password" placeholder="Contraseña" style= "align-self: center">
                <button name="ingresar" type="submit" style="align-self: center">Ingresar</button>
            </form>
        </div>
    </body>
</html>
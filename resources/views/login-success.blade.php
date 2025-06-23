<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Éxito</title>
</head>
<body>
    @if (session('success'))
        <h2 style="color: green; text-align: center;">{{ session('success') }}</h2>
    @endif

    <p style="text-align: center;">Bienvenido a GLOVE.</p>
</body>
</html>

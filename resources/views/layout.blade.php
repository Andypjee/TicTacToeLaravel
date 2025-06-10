<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Tic-Tac-Toe</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        nav a { margin-right: 15px; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('games.index') }}">Spellen</a>
        <a href="{{ route('games.create') }}">Nieuw spel</a>
        <a href="{{ route('players.index') }}">Spelers</a>
        <a href="{{ route('players.create') }}">Speler aanmaken</a>

    </nav>
    <hr>
    
    @yield('content')
</body>
</html>

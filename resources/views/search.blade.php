<!DOCTYPE html>
<html>
<head>
    <title>Semantic Category Search</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        input[type="text"] { padding: 10px; width: 300px; }
        button { padding: 10px 15px; }
        ul { margin-top: 20px; }
    </style>
</head>
<body>

    <h2>🔍 Semantic Search</h2>

    <form method="GET" action="/search">
        <input type="text" name="query" placeholder="Enter your query..." value="{{ $query ?? '' }}">

        <button type="submit">Search</button>
    </form>

    @if(isset($results) && count($results) > 0)
        <h3>Results:</h3>
        <ul>
            @foreach($results as $result)
                <li>{{ $result['name'] }} (Score: {{ number_format($result['score'], 4) }})</li>
            @endforeach
        </ul>
    @elseif(isset($query))
        <p>No results found.</p>
    @endif

</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoVote</title>
    @vite('resources/js/app.js')
</head>
<body>
    <div id="app" data-page="{{ json_encode($page) }}">
        <img src="/images/echovote.png" alt="EchoVote Logo" style="width: 200px;">
        <example-component></example-component>
    </div>
</body>
</html>

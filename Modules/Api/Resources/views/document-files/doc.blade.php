<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">

    <title>Cogent Health</title>

    <!-- Flatdoc -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src='https://cdn.rawgit.com/rstacruz/flatdoc/v0.9.0/legacy.js'></script>
    <script src='https://cdn.rawgit.com/rstacruz/flatdoc/v0.9.0/flatdoc.js'></script>

    <!-- Flatdoc theme -->
    <link href='{{ asset('plugins/api-documents/api-style.css') }}' rel='stylesheet'>
    <script src='{{ asset('plugins/api-documents/api-script.js') }}'></script>

    <!-- Initializer -->
    <script>
        Flatdoc.run({
            fetcher: Flatdoc.file("{{ asset('plugins/api-documents/main.md') }}")
        });
    </script>
</head>
<body role='flatdoc'>

<div class='header'>
    <div class='left'>
        <h1>Cogent Health : API Documentation</h1>
    </div>
    <div class='right'>
    </div>
</div>

<div class='content-root'>
    <div class='menubar'>
        <div class='menu section' role='flatdoc-menu'></div>
    </div>
    <div role='flatdoc-content' class='content'></div>
</div>

</body>
</html>

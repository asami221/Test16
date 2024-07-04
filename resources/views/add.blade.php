<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>@yield('title')</title>
</head>
<body>
    <h1>@yield('heading')</h1>

    <!-- 検索フォーム -->
    @yield('search_form')

    <!-- コンテンツ -->
    <div>
        @yield('content')
    </div>
</body>
</html>

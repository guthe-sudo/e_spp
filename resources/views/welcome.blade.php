<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
        <title>Laravel Vue</title>
         <link rel="stylesheet" href="/css/all.css">
         <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
        <script>
            (function () {
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                };
            })();
        </script>
    </head>
    <body>
        <div id="app">
            @if(Auth::check())
                <mainapp :user="{{Auth::user()}}"></mainapp>
            @else
                <mainapp :user="false"></mainapp>
            @endif
                
        </div>
    </body>
    <script src="{{ mix('/js/app.js') }}"></script>
    
</html>

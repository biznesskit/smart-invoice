<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{env('APP_NAME','eTIMS ESB')}} - Built for growth </title>
    </head>
    <body class="antialiased" style="text-align:center;margin-top:15px">
        <h1>{{env('APP_NAME','Biz Kit')}} Fiscal Invoicing - {{env('APP_ENV','local') == 'production' ? 'API' : 'Sandbox'}}</h1>
        <p>All rights reserved &copy @php echo date('Y') @endphp </p>
    </body>
</html>

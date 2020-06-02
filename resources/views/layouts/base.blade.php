<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? config('app.name')}}</title>
    <link rel="shortcut icon" type="image/png" href="{{url('images/favicon.jpg')}}"/>
    {{Html::style('css/app.css')}}
    {{Html::script('js/app.js')}}
</head>
<body>

<header class="text-center py-3">
    <div class="row justify-content-center">
        <img src="{{url('/images/LOGO.png')}}" alt="logo de l'Escape Game" height="80">
        <h1 class="my-auto ml-3"><a href="{{ url('/') }}">{{config('app.name')}}</a></h1>
    </div>
</header>

    <div class="sticky-top">
        @include('layouts.logout')
    </div>

<div id="tablist"></div>
<script>
    const tablist = new TabList('#tablist');
</script>

@yield('content')

</body>
</html>
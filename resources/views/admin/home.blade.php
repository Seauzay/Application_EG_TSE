<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administration</title>
</head>

<body>

<header style="text-align: center;">
    <h1>Administration</h1>
    <form action={{url('gm/logout')}}>
        <button type="submit">Déconnexion</button>
    </form>
</header>


<h2>Réinitialiser la base de données ? (cette action est irréversible)</h2>
<form id="refreshDB" action="{{ url('/admin/refreshDB') }}" method="post">
    <button id="refreshButton" onclick="confirm(e)">Je veux réinitialiser la base de données</button>
</form>

<script>
    document.querySelector('#refreshButton').addEventListener('click', function (e) {
        e.preventDefault();
        this.style.display = 'none';
        const newButton = document.createElement('button');
        newButton.textContent = 'Je comprends les conséquences tragiques de mon acte et je souhaite tout recommencer';
        newButton.type = 'submit';
        document.querySelector('#refreshDB').appendChild(newButton);
    });
</script>

<h2>Ajouter Game Master</h2>
<form action="{{url('admin/addGM')}}" method="post">
    <div>
        <label for="name">Identifiant : </label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="password">Mot de passe : </label>
        <input type="password" id="password" name="password">
    </div>
    <button type="submit">Envoyer</button>
</form>

<h2>Énigmes</h2>
@foreach($riddles as $riddle)
    <form action="{{ url('/admin/modifyRiddle') }}" method="post" style="margin-bottom: 2rem;">
        <input type="number" name="id" value="{{$riddle['id']}}" hidden>
        <div>
            <div>Ancien nom : {{$riddle['name']}}</div>
            <label for="name{{$loop->index}}">Nouveau nom : </label>
            <input type="text" id="name{{$loop->index}}" name="name">
        </div>
        <div>
            <div>Ancienne description : {{$riddle['description']}}</div>
            <label for="description{{$loop->index}}">Nouvelle description : </label>
            <input type="text" id="description{{$loop->index}}" name="description">
        </div>
        <div>
            <div>Ancien code : {{$riddle['code']}}</div>
            <label for="code{{$loop->index}}">Nouveau code : </label>
            <input type="text" id="code{{$loop->index}}" name="code">
        </div>
        <div>
            <label for="disable{{$loop->index}}">Désactiver ?</label>
            <input type="checkbox" id="disable{{$loop->index}}" name="disabled" {{$riddle['disabled'] ? 'checked' : ''}}>
        </div>
        <button type="submit">Envoyer</button>
    </form>
@endforeach

</body>

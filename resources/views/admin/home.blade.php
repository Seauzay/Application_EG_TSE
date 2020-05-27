@extends('layouts.base')
@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">

    </div>
@endsection

@section('content')
    <div id="reinit-base" class="card-admin">
        <h2>Réinitialiser la base de données ? (cette action est irréversible)</h2>
        <form id="refreshDB" action="{{ url('/admin/refreshDB') }}" method="post">
            <button class="btn btn-danger" id="refreshButton" onclick="confirm(e)">Je veux réinitialiser la base de données</button>
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
    </div>

    <div id="add-GM" class="card-admin">
        <h2>Ajouter Game Master</h2>
        <form action="{{url('admin/addGM')}}" method="post">
            <div>
                <input class="form-control" type="text" id="name" name="name" placeholder="Identifiant">
            </div>
            <div>
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
            </div>
            <button class="btn btn-primary validate-button my-1 center-block" type="submit">Ajouter</button>
        </form>
    </div>
    <div id="mod-riddles">
        @foreach($riddles as $riddle)
            <div class="card-admin">
                <form action="{{ url('/admin/modifyRiddle') }}" method="post" style="margin-bottom: 2rem;">
                    <input type="number" name="id" value="{{$riddle['id']}}" hidden>
                    <h2 class="current-ridlle-name">{{$riddle['name']}}</h2>
                    <div class="current-riddle-info">
                        <div class="current-riddle-descr">{{$riddle['description']}}</div>
                        <div class="current-riddle-code">{{$riddle['code']}}</div>
                        <a class="current-riddle-url" href="{{$riddle['url']}}">URL</a>
                        <div class="current-riddle-disable-cb">
                            <label for="disable{{$loop->index}}">Désactiver ?</label>
                            <input type="checkbox" id="disable{{$loop->index}}" name="disabled" {{$riddle['disabled'] ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="mod-riddle-info">
                        <input type="text" class="form-control" id="name{{$loop->index}}" name="name" placeholder="Nouveau nom">
                        <input type="text" class="form-control" id="description{{$loop->index}}" name="description" placeholder="Nouvelle description">
                        <input type="text" class="form-control" id="code{{$loop->index}}" name="code" placeholder="Nouveau code">
                        <input type="url" class="form-control" id="url{{$loop->index}}" name="url" placeholder="Nouvel URL">
                    </div>

                    <button class="btn btn-primary validate-button my-1" type="submit">Modifier</button>
                </form>
            </div>
        @endforeach
    </div>

    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Ajouter GM', active: true});
        tablist.addTab({title: 'Modifier les énigmes', active: false});
        tablist.addTab({title: 'Réinitialiser', active: false});
        roomlist.update();
    </script>

    <script>
        tablist.contentOfTab(1).append(document.querySelector('#add-GM'));
        tablist.contentOfTab(2).append(document.querySelector('#mod-riddles'));
        tablist.contentOfTab(3).append(document.querySelector('#reinit-base'));
    </script>

@endsection
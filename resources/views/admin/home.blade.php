@extends('layouts.base')
@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">

    </div>
@endsection

@section('content')
    <div id="reinit-base" >
        <div class="card-admin">
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
    </div>

    <div id="add-GM">
        <div class="card-admin">
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
                        <div class="current-riddle-post-msg" > {{($riddle['post-msg'])?$riddle['post-msg']:'Aucun message de résolution'}}</div>
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
                        <input type="text" class="form-control" id="post-msg{{$loop->index}}" name="post-msg" placeholder="Nouveau message de résolution">
                        <input type="url" class="form-control" id="url{{$loop->index}}" name="url" placeholder="Nouvel URL">
                    </div>

                    <button class="btn btn-primary validate-button my-1" type="submit">Modifier</button>
                </form>
            </div>
        @endforeach
    </div>

    {{-- Template pour la modification de parcours--}}
    <div id="mod-parcour-display-template">
        <div id="mod-bdd">
            <button id="btn-mod-bdd"class="btn btn-primary validate-button my-1" onclick="modParcours()">Modifier</button>
            <button id="btn-reset-display"class="btn btn-primary validate-button my-1" onclick="resetParcours()">Reset</button>

        </div>


        <div class="mod-parcour-container">
            <template id="mod-parcour-template">
                <div class="card-admin" draggable="true" ondragstart="drag(event)" ondragover="dragOver(event)" ondragend="dragEnd(event)">
                    <h2 class="current-riddle-name">title</h2>
                    <div class="collapse-content">Détails</div>
                    <span class="id-card" hidden></span>
                    <div class="current-riddle-info">
                        <div class="current-riddle-descr">descr</div>
                        <div class="current-riddle-code">code</div>
                        <div class="current-riddle-post-msg">Msg de resolution</div>
                        <a draggable="false" class="current-riddle-url" >URL</a>
                        <div class="current-riddle-disable-cb">
                            <label {{--for="disable{{$loop->index}}" --}}>Désactiver :</label>
                            <input type="checkbox" class="current-riddle-activated" {{-- id="disable{{$loop->index}}" name="disabled" {{$riddle['disabled'] ? 'checked' : ''}}--}}>
                        </div>
                    </div>
                </div>
            </template>


            <div id="parcour-mod-div">
                <ul id="possible-riddle" class="riddle-list"  ondrop="drop(event)" ondragover="allowDrop(event)">
                    <li>
                        <h2>Enigmes disponibles</h2>
                    </li>
                </ul>

                <ul id="mod-parcours" class="riddle-list"  ondrop="drop(event)" ondragover="allowDrop(event)">
                    <li id="header-mod-parcours">
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Ajouter GM', active: true});
        tablist.addTab({title: 'Modifier les énigmes', active: false});
        tablist.addTab({title: 'Réinitialiser', active: false});
        //roomlist.update();
    </script>

    <script>
        tablist.contentOfTab(1).append(document.querySelector('#add-GM'));
        tablist.contentOfTab(2).append(document.querySelector('#mod-riddles'));
        tablist.contentOfTab(3).append(document.querySelector('#reinit-base'));
    </script>

    <script>
        const createParcours = new CreateModParcourDisp(tablist);
        function allowDrop(ev) {
            createParcours.allowDrop(ev);
        }

        function drag(ev) {
            createParcours.drag(ev);
        }

        function drop(ev) {
            createParcours.drop(ev);
        }

        function dragOver(ev){
            createParcours.dragOver(ev);
        }

        function dragEnd(ev){
            var shadowContainer = document.getElementById('drag-over-shadow');
            if(shadowContainer != null){
                var parentCont = shadowContainer.parentNode;
                if(parentCont!=null)
                    parentCont.removeChild(shadowContainer);
                shadowContainer = null;
            }

        }
        function modParcours(){
            createParcours.modParcours();
        }
        function resetParcours(){
            createParcours.resetParcours();
        }
    </script>

@endsection

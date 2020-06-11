@extends('layouts.base')
@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">

    </div>
@endsection

@section('content')
    <div class="modal" tabindex="-1" role="dialog" id="success-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Succès</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-message">Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{url('/admin')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">OK</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="erros-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Erreur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-message">Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{url('/admin')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">OK</a>
                </div>
            </div>
        </div>
    </div>
    <div id="reinit-base" >
        <div class="card-admin">
            <h4>Réinitialiser la base de données ? (cette action est irréversible)</h4>
            <form id="refreshDB">
                <div>
                    <input type="checkbox" id="refreshRiddles" name="Riddles">
                    <label for="Riddles">Réinitialiser les énigmes</label>
                </div>
                <div>
                    <input type="checkbox" id="refreshGMs" name="GMs">
                    <label for="GMs">Réinitialiser les gamemasters</label>
                </div>
                <button class="btn btn-danger" id="refreshButton" onclick="confirm(e)">Je veux réinitialiser les parcours et recommencer une nouvelle partie.</button>
            </form>
        </div>
    </div>

    <div id="add-GM">
        <div class="card-admin">
            <h4>Ajouter/Modifier Game Master</h4>
            <form id="add-GM-form">
                <input class="form-control" type="text" id="name" name="name" placeholder="Identifiant">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                <button class="btn btn-primary validate-button my-1 center-block" type="submit">Ajouter</button>
            </form>
        </div>
    </div>
    <div id="mod-riddles">
        @foreach($riddles as $riddle)
            <div class="card-admin">
                <form class="modify-riddle" style="margin-bottom: 2rem;">
                    <input type="number" name="id" value="{{$riddle['id']}}" hidden>
                    <h3 class="current-ridlle-name">{{$riddle['name']}}</h3>
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
                    <h4 class="current-riddle-name">title</h4>
                    <span class="id-card" hidden></span>
                    <div class="current-riddle-info">
                        <div class="current-riddle-descr">descr</div>
                        <div class="current-riddle-code">code</div>
                        <div class="current-riddle-post-msg">Msg de resolution</div>
                        <a draggable="false" class="current-riddle-url" >URL</a>
                        <div class="current-riddle-activated"></div>
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
    <script>
        $("#log-out-container").css("display","block");
        $("#log-out-container").css("padding-right","10%");
    </script>
    <script>
        $(document).ready(function(){
            $(document).on('submit', '#add-GM-form', function() {
                $.ajax("{{url('admin/addGM')}}",{
                    data: $(this).serialize(),
                    success: function(data) // show response from the php script.
                    {
                        if (data.status.type === 'success') {
                            $('#success-modal').find('.modal-message').text(data.status.message);
                            $('#success-modal').modal('show');

                        }
                        if (data.status.type === 'error') {
                            $('#error-modal').find('.modal-message').text(data.status.message);
                            $('#error-modal').modal('show');
                        }
                    }
                });
                return false;
            });
            document.querySelector('#refreshButton').addEventListener('click', function (e) {
                e.preventDefault();
                this.style.display = 'none';
                const newButton = document.createElement('button');
                newButton.textContent = 'Je comprends les conséquences tragiques de mon acte et je souhaite tout recommencer';
                newButton.type = 'submit';
                document.querySelector('#refreshDB').appendChild(newButton);
            });
            $('#refreshDB').submit(function(e){
                e.preventDefault();
                $.ajax("{{url('admin/refreshDB')}}",{
                    data: $(this).serialize(),
                    success: function(data) // show response from the php script.
                    {
                        if (data.status.type === 'success') {
                            // show modal for success
                            $('#success-modal').find('.modal-message').text(data.status.message);
                            $('#success-modal').modal('show');

                        }
                        if (data.status.type === 'error') {
                            // show modal for error
                            $('#error-modal').find('.modal-message').text(data.status.message);
                            $('#error-modal').modal('show');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(".modify-riddle").submit(function(e){
            e.preventDefault();
            $.ajax("{{ url('/admin/modifyRiddle') }}",{
                data: $(this).serialize(),
                success: function(data) // show response from the php script.
                {
                    if (data.status.type === 'success') {
                        $('#success-modal').find('.modal-message').text(data.status.message);
                        $('#success-modal').modal('show');

                    }
                    if (data.status.type === 'error') {
                        $('#error-modal').find('.modal-message').text(data.status.message);
                        $('#error-modal').modal('show');
                    }
                }
            });
        })

    </script>

@endsection

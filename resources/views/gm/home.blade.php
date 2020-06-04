@extends('layouts.base')

@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">

    </div>
@endsection

@section('content')
    {{--div pour une énigme gm--}}
    {{--<div id="timer-handler">
         <form action="{{ url('gm/home') }}" method="post" style="margin-bottom: 2rem;">
             <button id="btn-timer"class="btn btn-primary validate-button my-1" type="submit">Lancer le timer</button>
             {{-- <span class="team-time"></span>--}}
    {{--   </form>
   </div> --}}
    <template id="gm-team-template">
        <div class="container jumbotron gm-team">
            <div class="row align-items-start gm-teams mb-3">
                <div class="col align-self-center text-center">
                    <span class="team-name"></span>&nbsp;:
                    <span class="team-time"></span>
                </div>
                <div class="col-8 gm-riddle-col">
                    <div class="row justify-content-center"><span class="current-riddle-title">Énigme actuelle&nbsp;: </span></div>
                    <div class="row justify-content-center"><span class="current-riddle"></span>&nbsp;: <span
                                class="current-riddle-time"></span></div>
                </div>
            </div>
            <div class="row progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="accordion">
                <div class="card">
                    <div class="card-header p-0 pt-2">
                        <div class="text-center my-auto">
                            <span data-toggle="collapse">Détail</span>
                        </div>
                        <i class="oi oi-chevron-bottom"></i>
                    </div>
                    <div class="collapse">
                        <div class="card-body">
                            Terminées :
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Template pour les messages --}}
    <template id="message-template">
        <div class="message">
            <div>
            <span class="msg-head">
                <span class="name">Name</span>
                à
                <span class="date">Date</span>
            </span>
            </div>
            <div>
                <div class="content">Content</div>
            </div>
        </div>
    </template>

    {{-- Template pour les salons --}}
    <template id="room-template">
        <div class="message-container">

        </div>

        <form action="msg/send/{id}" method="post" class="message-form">
            <input type="text" name="content">
        </form>
    </template>

    {{-- Template pour la modification de parcours--}}
    <div id="mod-parcour-display-template">
        <div id="mod-bdd">
                <button id="btn-mod-bdd"class="btn btn-primary validate-button my-1" onclick="modBDD()">Modifier</button>
                <button id="btn-reset-display"class="btn btn-primary validate-button my-1" onclick="resetBDD()">Reset</button>

        </div>



        <div class="mod-parcour-container">
            <template id="mod-parcour-template">
                <div class="card-admin" draggable="true" ondragstart="drag(event)" ondragover="dragOver(event)" ondragend="dragEnd(event)">
                    <h2 class="current-riddle-name">title</h2>
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
        tablist.addTab({title: 'Suivi des équipes', active: true});
        roomlist.update();
    </script>


    <script>
        const div = $('<div>');
        div.appendTo(tablist.contentOfTab(1));
        const gmTeamList = new GMTeamList(div);
        gmTeamList.update();

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

        function modBDD(){
            createParcours.modBDD();
        }
        function resetBDD(){
            createParcours.resetBDD();
        }
    </script>
@endsection
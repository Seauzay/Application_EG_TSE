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
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="error-modal">
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
            </div>
        </div>
    </div>

    {{--template pour une énigme gm--}}
    <template id="gm-team-template">
        <div class="container jumbotron gm-team" id="gm-team-jumbo">
            <div class="row align-items-start gm-teams mb-3">
                <div class="col align-self-center text-center">
                    <span class="bold-text">[</span>
                    <span class="classement"></span><span class="bold-text">&nbsp;]&nbsp;</span>
                    <span class="team-name"></span><span class="bold-text">&nbsp;avec</span>
                    <span class="team-score"></span><span class="bold-text">&nbsp;pts</span>
                    <div class="team-time"></div>
                </div>
                <div class="col-8 gm-riddle-col">
                    <div class="row justify-content-center"><span class="current-riddle-title">Énigme actuelle: </span></div>
                    <div class="row justify-content-center"><span class="current-riddle"></span>&nbsp;<span
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



    {{-- Template pour la modification de parcours--}}
    <div id="mod-parcour-display-template">
        <div id="mod-bdd">
                <button id="btn-mod-bdd"class="btn btn-primary validate-button my-1" onclick="modParcours(event)">Modifier</button>
                <button id="btn-reset-display"class="btn btn-primary validate-button my-1" onclick="resetParcours(event)">Reset</button>

        </div>



        <div class="mod-parcour-container">
            <template id="mod-parcour-template">
                <div class="card-admin" draggable="true" ondragstart="drag(event)" ondragover="dragOver(event)" ondragend="dragEnd(event)">
                    <h2 class="current-riddle-name">title</h2>
                    <div class="current-riddle-activated"></div>
                    <div class="collapse-content">Détails</div>
                    <span class="id-card" hidden></span>
                    <div class="current-riddle-info">
                        <div class="current-riddle-descr">descr</div>
                        <div class="current-riddle-code">code</div>
                        <div class="current-riddle-post-msg">Msg de resolution</div>
                        <a draggable="false" class="current-riddle-url" >URL</a>

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
        tablist.addTab({title: 'Chronométrage', active: false});
        //roomlist.update();
    </script>

    <script>
        //Partie bouton pour la récuperation des données des équipes dans un fichier csv
		let cont_but = document.createElement('div');
		cont_but.id = "cont_but";
        cont_but.className = "container";
		let but = document.createElement('button');
		but.innerHTML  = 'Exporter en CSV';
		but.class = "btn btn-secondary pull-right";
		but.id = 'export-button'
		but.addEventListener("click",function(){
			//console.log('click');
			$.ajax('admin/CSV', {method: 'GET', success :(response)=>{if (response=='true'){  startDownload()}}});
		} );
		function startDownload(){
			let frame = document.createElement('iframe');
			frame.style.display = "none";
			frame.src = "{{url('/report.csv')}}";
			frame.id = 'frame'
            divSuivi[0].appendChild(frame);

			$("#frame").click()

		};

		cont_but.appendChild(but);
		tablist.contentOfTab(1).append(cont_but);
        const divSuivi = $('<div>');
        divSuivi.appendTo(tablist.contentOfTab(1));
        const gmTeamList = new GMTeamList(divSuivi);
        gmTeamList.update();


        const divChrono = $('<div>',{id:'chronometrageTabContent'});
        divChrono.appendTo(tablist.contentOfTab(2));
        const chronometrage = new ChronometrageForm(divChrono);
        chronometrage.fillHTML();


        Echo.channel('application_tracking_escape_game_tse_database_gm-change').listen('.change', function(e) {
            gmTeamList.update();
        });

        const createParcours = new CreateModParcourDisp(tablist,false);
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

        function modParcours(ev){
            createParcours.modParcours(ev);
        }

        function resetParcours(ev){
            createParcours.resetParcours(ev);
        }

    </script>
    <script>
        $("#log-out-container").css("display","block");
        $("#log-out-container").css("padding-right","10%");
    </script>
@endsection

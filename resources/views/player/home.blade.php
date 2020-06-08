@extends('layouts.base')

@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">
      <span><svg class="bi bi-clock" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm8-7A8 8 0 1 1 0 8a8 8 0 0 1 16 0z"/>
  <path fill-rule="evenodd" d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
</svg> <span class="time"></span></span>
    </div>
    <div  class="row justify-content-start"><span id="score">Score : {{ Auth::user()->score }} pts</span>
    </div>
    <div id="score">Score : {{ Auth::user()->score }}</div>
    <div id="emoji">
        rang :
        <span class="rank">
            <script>
                emoji.display();
            </script>
        </span>
    </div>

		   <p>Avancement du jeu:
   	   <div id="myProgress">
  <div id="myBar"></div>
</div>

</p>

@endsection

@section('content')
    {{--modale de validation des énigmes--}}
    <div class="modal fade" id="validation-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <form class="col">
                        <div class="form-group">
                            <label for="validation-modal-code" class="form-control-label">Veuillez entrer le code que vous avez reçu à la fin de cette énigme</label>

                            <div class='alert alert-danger alert-block text-center'>
                               Code invalide !
                            </div>
                            <input type="text" class="form-control" name="code" id="validation-modal-code" placeholder="Entrez le code ici">
                        </div>
                        <button type="submit" class="btn btn-secondary pull-right">Vérifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--template pour une énigme joueur--}}
    <template id="player-riddle-template">
        <div class="card player-riddle-card my-2">
            <div class="card-body">
                <div class="row mx-auto justify-content-between">
                    <h5 class="card-title"></h5>
                    <span class="timer">00:00</span>
                </div>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <p class="card-text"></p>
                <p class="card-post-resolution-message" style="display: none;"></p>
                <a class="btn btn-info player-riddle-url my-1" target="_blank" style="display: none;">Lien vers l'énigme</a>

                <div class="row mx-auto">
                    <button class="btn btn-secondary start-button my-1">Commencer l'énigme</button>
                </div>
                <div class="row mx-auto">
                    <button class="btn btn-outline-secondary btn-block validate-button my-1" style="color: #f9c11c !important; background-color: #ffffff !important;border :white; display: none;" data-toggle="modal" data-target="#validation-modal" >Vous avez terminé l'énigme? Validez-là ici! </button>
                </div>
                <div class="row mx-auto">
                    <button class="btn btn-secondary cancel-button my-1" style="display: none;">Annuler l'énigme</button>
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

        <!-- Modal pop up system -->
    <div class="modal fade right" id="myModalDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="badge badge-danger">Nouveau !</span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                </div>

            </div>
        </div>
{{--TOO LATE--}}
    <div class="modal fade right" id="tooLate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" STYLE="background-color: #f44336 !important;color: white !important;">
                    <h4 class="modal-title">Vous avez dépassé deux heures <svg class="bi bi-emoji-frown" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path fill-rule="evenodd" d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683z"/>
                            <path d="M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                        </svg></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" STYLE="background-color: #f44336 !important;color: white !important;">
                    <p></p>
                </div>
            </div>

        </div>
    </div>

    {{-- Template pour les salons --}}
    <template id="room-template">
        <div class="messenger-container container-fluid">
            <div class="message-container"></div>

            <form action="msg/send/{id}" method="post" class="message-form">
            </form>
        </div>
    </template>

    <script>
        Echo.channel('application_tracking_escape_game_tse_database_channel-equipe').listen('.emoji', function(e) {
            /*
            $.ajax('player/classement', {method: 'GET', success: function(response){
                    if(response.rank==1)
                        $('#emoji .rank').text('🥇');
                    else if (response.rank==2)
                        $('#emoji .rank').text('🥈');
                    else if (response.rank==3)
                        $('#emoji .rank').text('🥉');
                    else
                        $('#emoji .rank').text('💩');
                }});
             */
            let rank ={{DB::table('teams')->where('score', '>',  Auth::user()->score)->count()}}+1;
            if(rank==1)
                $('#emoji .rank').text('🥇');
            else if (rank==2)
                $('#emoji .rank').text('🥈');
            else if (rank==3)
                $('#emoji .rank').text('🥉');
            else
                $('#emoji .rank').text('💩');
            console.log(rank);
        });
    </script>
    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Énigmes', active: true});
		const roomlist = new RoomList(tablist);
        roomlist.update();
		tablist.addTab({title: 'FAQ'});
		tablist.contentOfTab(2).append($('<div>',{id:'FaQ'}));
		const QRgGrid = new QRGrid('#FaQ');
		QRgGrid.remplissageQRgrid();
    </script>

    {{--Création des énigmes au chargement de la page--}}
    <script>
        tablist.contentOfTab(1).append($('<div>', {id: 'global-timer'}));
        tablist.contentOfTab(1).append($('<div>', {id: 'mySuperRiddleGrid'}));
                {{--div de base de la grille d'énigmes--}}
        const playerRiddleGrid = new PlayerRiddleGrid('#mySuperRiddleGrid');
        const res = playerRiddleGrid.waitForActivation();
        Echo.channel('application_tracking_escape_game_tse_database_channel-equipe').listen('.startChrono',function(){
            $.ajax('player/startDate', {method: 'GET', success:function(response){
                playerRiddleGrid.updateTimer(response.time);
                playerRiddleGrid.update();
            }});
        });
        Echo.channel('application_tracking_escape_game_tse_database_channel-equipe').listen('.resetChrono',function(){
            document.location.reload(true);
        });
    </script>

    <script>


        Echo.channel('application_tracking_escape_game_tse_database_validation-enigme').listen('.emoji', function(e) {

            emoji.display();

        });

    </script>
@endsection

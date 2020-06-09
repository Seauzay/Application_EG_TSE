@extends('layouts.base')

@section('nav-items')
    {{-- Timer global --}}

    <span id="emoji" class="row justify-content-space-between">  <span class="rank" ></span>
        <span id="score" style="color: #182949 !important;"><strong>{{ Auth::user()->score }} pts</strong></span>
    </span>
            <div class="progress row justify-content-space-between" style="width:35%;"></div>
    <div id="global-timer" class="row justify-content-space-between"  >
      <span><svg class="bi bi-clock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
</svg> <strong><span class="time" style="color: #182949"></span></strong></span>
    </div>

{{--
  <div id="myBar" class="progress-bar bg-warning" role="progressbar"  aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
--}}
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
                <div class="row">
                    <div class="col-auto mr-auto">
                    <h5 class="card-title" style="color: #182949 !important;"></h5>
                    </div>
                    <div class="col-auto">
                    <span class="timer badge badge-light "></span>
                    </div>
                </div>
                <h6 class="card-subtitle mb-2 text-muted">
                </h6>
                <p class="card-text font-italic font-weight-bold" style="color: #182949 !important;"></p>
                <a class="card-link player-riddle-url " target="_blank" style="display: none; color: #182949 !important ">Lien vers l'énigme <span><svg class="bi bi-link" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
  <path d="M6.764 6.5H7c.364 0 .706.097 1 .268A1.99 1.99 0 0 1 9 6.5h.236A3.004 3.004 0 0 0 8 5.67a3 3 0 0 0-1.236.83z"/>
  <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
  <path d="M8 11.33a3.01 3.01 0 0 0 1.236-.83H9a1.99 1.99 0 0 1-1-.268 1.99 1.99 0 0 1-1 .268h-.236c.332.371.756.66 1.236.83z"/>
</svg></span></a>

                <div class="row mx-auto">
                    <button class="btn btn-secondary start-button my-1">Commencer l'énigme</button>
                </div>
                <div class="row ">
                    <button class="ml-lg-0 btn btn-light btn-block validate-button my-1" style="color: #182949 !important; background-color: white !important;border :white; display: none;" data-toggle="modal" data-target="#validation-modal" >Vous avez terminé l'énigme? Validez-là ici! </button>
                </div>
                <hr>
                <div class="row ">
                    <div class="ml-auto mb-0">
                    <a href="#" class="ml-lg-1 badge  badge-secondary cancel-button " style="display: none;">Annuler l'énigme</a>
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

    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Énigmes', active: true, icon: '<svg class="bi bi-play-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                '  <path d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>' +
                '</svg>&nbsp;'});
		const roomlist = new RoomList(tablist);
        roomlist.update();
		tablist.addTab({title: 'FAQ',icon: '<svg class="bi bi-question-octagon-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                '  <path fill-rule="evenodd" d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM6.57 6.033H5.25C5.22 4.147 6.68 3.5 8.006 3.5c1.397 0 2.673.73 2.673 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.355H7.117l-.007-.463c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.901 0-1.358.603-1.358 1.384zm1.251 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927z"/>' +
                '</svg>&nbsp;'});
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
        emoji.display();
        Echo.channel('application_tracking_escape_game_tse_database_channel-equipe').listen('.emoji', function(e) {
            emoji.display();
        });

    </script>
@endsection

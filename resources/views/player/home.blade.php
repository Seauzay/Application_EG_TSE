@extends('layouts.base')

<style>
#Ajout de la barre de progression
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 0%;
  height: 30px;
  background-color: #4CAF50;
  text-align: center;
  line-height: 30px;
  color: white;
}

@section('nav-items')
<p>Avancement du jeu :
       
	   
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
                            <input type="text" class="form-control" name="code" id="validation-modal-code" placeholder="Entrez le code ici">
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">Vérifier</button>
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
                    <button class="btn btn-primary start-button my-1">Commencer l'énigme</button>
                </div>
                <div class="row mx-auto">
                    <button class="btn btn-primary validate-button my-1" data-toggle="modal" data-target="#validation-modal" style="display: none;">Valider l'énigme</button>
                </div>
                <div class="row mx-auto">
                    <button class="btn btn-primary cancel-button my-1" style="display: none;">Annuler l'énigme</button>
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
        <div class="messenger-container container-fluid">
            <div class="message-container"></div>

            <form action="msg/send/{id}" method="post" class="message-form">
                <input type="text" name="content">
            </form>
        </div>
    </template>


    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Énigmes', active: true});
        //tablist.addTab({title: 'Salon de clavardage avec les Game Masters'});
        roomlist.update();
    </script>

    {{--Création des énigmes au chargement de la page--}}
    <script>
        tablist.contentOfTab(1).append($('<div>', {id: 'global-timer'}));
        tablist.contentOfTab(1).append($('<div>', {id: 'mySuperRiddleGrid'}));
                {{--div de base de la grille d'énigmes--}}
        const playerRiddleGrid = new PlayerRiddleGrid('#mySuperRiddleGrid');
        const res = playerRiddleGrid.update();
    </script>
@endsection

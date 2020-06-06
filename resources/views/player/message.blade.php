@extends('layouts.base')
@section('content')

    <form class="player-login" method="GET" action="{{ url('player/play') }}">
        <div id="typewriter" class="container my-auto text-center" data-toggle="buttons">

        <script>
            var str = "<p class ='consigne'>Bienvenue sur l’application de l’Escape Game TSE ! </br>Elle vous permettra de résoudre certaines énigmes, de progresser dans le jeu… et d’obtenir des indices !</p>" +
                "<p class ='consigne'>Vous allez être amené à découvrir plusieurs lieux, dans l’Ecole et son quartier. Pour chaque lieu, il vous faudra :</p>" +
                "<p class ='consigne' style='left:30em'> cliquer sur “commencer l’énigme”</p>" +
                "<p class ='consigne' style='left:30em'> cliquer sur “lien vers l’énigme”, si l’énigme est hébergée sur une page web</p>" +
                "<p class ='consigne' style='left:30em'>une fois l’énigme résolue, entrer dans l’application le code de validation que le Game Master vous donnera.</p>" +
                // "<p class ='consigne'><span style='color:red' ><i>Cette application a été imaginée, prototypée et produite par des étudiants du M1 Design de Communication, des élèves de la DTA et des étudiants de </br>FISE 2. </i></br>Merci à eux !</span></p>"+
                "<p class ='consigne'>Vous allez être redirigé maintenant vers la page de jeu !</p>",
                i = 0,
                isTag,
                text;

            (function type() {
                text = str.slice(0, ++i);
                if (text === str)
                {
                    setTimeout(function(){ return  window.location = "{{ url('/') }}"; }, 4000);
                    return;

                }


                document.getElementById('typewriter').innerHTML = text;

                var char = text.slice(-1);
                if( char === '<' ) isTag = true;
                if( char === '>' ) isTag = false;

                if (isTag) return type();
                setTimeout(type, 40);


            }());
        </script>
            <script>
            </script>
        </div>

        {{ csrf_field() }}
      {{--  <div class="container text-center">
            <button id ="buuton" type="submit" class="btn btn-primary" value="play" >LETS PLAY</button>
        </div>--}}
        <script>
        </script>

    </form>
    @endsection
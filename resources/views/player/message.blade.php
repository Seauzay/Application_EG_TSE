@extends('layouts.base')
@section('content')

    <form class="player-login" method="GET" action="{{ url('player/login') }}">
        <div id="typewriter" class="container mb-3" data-toggle="buttons">

        <script>
            var str = "<div style='text-align: center'>Bienvenue sur l’application de l’Escape Game TSE ! </br>Elle vous permettra de résoudre certaines énigmes, de progresser dans le jeu… et d’obtenir des indices !</br>" +
                "Vous allez être amené à découvrir plusieurs lieux, dans l’Ecole et son quartier. Pour chaque lieu, il vous faudra :</br>" +
                "-  cliquer sur “commencer l’énigme”</br>" +
                "-  cliquer sur “lien vers l’énigme”, si l’énigme est hébergée sur une page web</br>" +
                "une fois l’énigme résolue, entrer dans l’application le code de validation que le Game Master vous donnera.</br>" +
                "<span style='color:red' ><i>Cette application a été imaginée, prototypée et produite par des étudiants du M1 Design de Communication, des élèves de la DTA et des étudiants de </br>FISE 2. </i></br>Merci à eux !</span>"+
                "</br>Vous allez être redirigé maintenant vers la page de connexion !</div>",
                i = 0,
                isTag,
                text;

            (function type() {
                text = str.slice(0, ++i);
                if (text === str)
                {
                    setTimeout(function(){ return  window.location = "{{ url('player/login') }}"; }, 4000);
                    return;

                }


                document.getElementById('typewriter').innerHTML = text;

                var char = text.slice(-1);
                if( char === '<' ) isTag = true;
                if( char === '>' ) isTag = false;

                if (isTag) return type();
                setTimeout(type, 35);


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
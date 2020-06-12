@extends('layouts.base')

@section('content')

    <form class="player-login text-center" method="GET" action="{{ url('player/play') }}">
        <div class="row">

        <div id="typewriter" class="container text-center" data-toggle="buttons">

        <script>

            var str = "<p class ='consigne'>Bienvenue sur l’application de l’Escape Game TSE ! </br>Elle vous permettra de résoudre certaines énigmes, de progresser dans le jeu… et d’obtenir des indices !</p>" +
                "<p class ='consigne'>Vous allez être amenés à découvrir plusieurs lieux, dans l’Ecole et son quartier. Pour chaque lieu, il vous faudra :</p>" +
                "<p class ='consigne' style='left:3.5em'> cliquer sur “commencer l’énigme”</p>" +
                "<p class ='consigne' style='left:3.5em'> cliquer sur “lien vers l’énigme”, si l’énigme est hébergée sur une page web</p>" +
                "<p class ='consigne' style='left:3.5em'>une fois l’énigme résolue, entrer dans l’application le code de validation que le Game Master vous donnera.</p>" +
                // "<p class ='consigne'><span style='color:red' ><i>Cette application a été imaginée, prototypée et produite par des étudiants du M1 Design de Communication, des élèves de la DTA et des étudiants de </br>FISE 2. </i></br>Merci à eux !</span></p>"+
                "<p class ='consigne'>Vous allez être redirigés maintenant vers la page de jeu !</p>",
                i = 0,
                isTag,
                text;
            var documentHeight = $(document).height();
            (function type() {
                text = str.slice(0, ++i);
                if (text === str)
                {
                    $(document.body).append(
                        '<footer id="sticky-footer" style="background-color: #e3e9ec">' +
                        '<div class="media">' +
                        // '  <div class="media-left media-middle">' +
                        '      <img class="align-self-center mr-3 ml-2" src="{{url('images/dev1.png')}}" alt="Dev">' +
/*                        '    </a>' +
                            '</div>'+*/
                    ' <div class="" style="color: #e3342f; font-size=0.5em">' +
                        ' <strong class="text-justify"> Cette application a été imaginée, prototypée et produite par des étudiants du M1 Design de Communication, des élèves de la DTA et des étudiants de FISE2. Merci à eux!</strong>' +
                        '  </div>'+
                        '  </div>'+
                       ' </footer>');
                    setTimeout(function() {
                        let newDocumentHeight = $(document).height();
                        if(documentHeight != newDocumentHeight) {
                            documentHeight = newDocumentHeight;
                            $('html, body').scrollTop(newDocumentHeight);
                        }
                    },500) ;
                    setTimeout(function(){ return  window.location = "{{ url('/') }}"; }, 8000);
                    return;

                }


                document.getElementById('typewriter').innerHTML = text;
                let newDocumentHeight = $(document).height();
                if(documentHeight != newDocumentHeight) {
                    documentHeight = newDocumentHeight;
                    $('html, body').scrollTop(newDocumentHeight);
                }

                var char = text.slice(-1);
                if( char === '<' ) isTag = true;
                if( char === '>' ) isTag = false;

                if (isTag) return type();
                setTimeout(type, 60);


            }());
        </script>
            <script>
            </script>
        </div>
        </div>

    </form>
    <script>
        $("#log-out-container").css("display","block");
        $("#log-out-container").css("padding-right","10%");
    </script>
    @endsection

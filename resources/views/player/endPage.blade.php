@extends('layouts.base')
@section('content')


	<div id='endContent' class="container mx-auto text-center">

		<img  src="{{url('images/victory_image.png')}}" id = 'Victory' style="  max-width:100%;
  height:auto;"/>

        <div id="typewriter" class="container text-center">

        <script>
            var str = "<p class ='endMessage'>Dossier complété et validé...</br>Félicitations, vous avez sauvé le WEI !</p>",
                i = 0,
                isTag,
                text;

            $(document).ready(function(){
                var documentHeight = $(document).height();
                $('html, body').scrollTop(documentHeight);
                (function type() {
                    text = str.slice(0, ++i);

                    if (text === str)
                    {
                        return;

                    }

                    document.getElementById('typewriter').innerHTML = text;
                    let newDocumentHeight = $(document).height();
                    if(documentHeight < newDocumentHeight) {
                        documentHeight = newDocumentHeight;
                        $('html, body').scrollTop(newDocumentHeight);
                    }

                    var char = text.slice(-1);
                    if( char === '<' ) isTag = true;
                    if( char === '>' ) isTag = false;

                    if (isTag) return type();
                    setTimeout(type, 40);


                }());
            });
        </script>
			<script>
            </script>
		</div>

	</div>
    <script>
        $("#log-out-container").css("display","block");
        $("#log-out-container").css("padding-right","10%");
    </script>
    @endsection


@extends('layouts.base')
@section('content')


	<div id='endContent' class="container my-auto text-center">

		<img  src="{{url('images/victory_image.png')}}" id = 'Victory' style="  max-width:100%;
  height:auto;"/>

        <div id="typewriter" class="container my-auto text-center">

        <script>
            var str = "<p class ='endMessage'>Dossier complété et validé...</br>Félicitations, vous avez sauvé le WEI !</p>",
                i = 0,
                isTag,
                text;

            (function type() {
                text = str.slice(0, ++i);

                if (text === str)
                {
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

	</div>
    @endsection


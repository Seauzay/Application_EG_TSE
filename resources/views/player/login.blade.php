@extends('layouts.base')
@section('content')
    @if(isset(Auth::user()->name))
        <script>window.location = "{{ url('/') }}";</script>
    @endif


    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block text-center">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Vous devez choisir une couleur et un numéro !</strong>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger text-center">
            <strong>Vous devez choisir une couleur et un numéro !</strong>
        </div>
    @endif
        <form class="player-login" method="post" action="{{ url('player/checklogin') }}">
            <div class="container mb-3  mx-auto" data-toggle="buttons">
                <div class="row">
                </div>
                <div class="row">
                    <div class="form-group col-md-4  mx-auto">
                        <label for="inputColor">Sélectionnez votre couleur d'équipe:</label>
                        <select id="color" name="color" class="custom-select mr-sm-2">
                            <option  disabled selected hidden>Couleurs</option>
                            <option data-iconurl="{{url('images/red-icon.png')}}" name="color" value="1">Rouge</option>
                            <option data-iconurl="{{url('images/green-icon.png')}}" name="color" value="2">Vert</option>
                            <option data-iconurl="{{url('images/blue-icon.png')}}" name="color" value="3">Bleu</option>
                            <option data-iconurl="{{url('images/yellow-icon.png')}}" name="color" value="4">Jaune</option>
                            <option data-iconurl="{{url('images/purple-icon.png')}}" name="color" value="5">Violet</option>
                        </select>
                        <label for="inputNum">Sélectionnez votre numéro d'équipe:</label>
                        <select id="num" name="num" class="custom-select mr-sm-2">
                            <option  disabled selected hidden>Numéro</option>
                            <option  name="num" value="1">1</option>
                            <option  name="num" value="2">2</option>
                            <option  name="num" value="3">3</option>
                            <option  name="num" value="4">4</option>
                            <option  name="num" value="5">5</option>
                            <option  name="num" value="6">6</option>
                            <option  name="num" value="7">7</option>
                            <option  name="num" value="8">8</option>
                            <option  name="num" value="9">9</option>
                            <option  name="num" value="10">10</option>

                        </select>

                    </div>

                    </div>
                </div>


            <div class="form-group col-md-4" data-toggle="buttons">
            </div>
            <div class="container text-center mx-auto ">
                <p class="firstMessage">
                    1 téléphone par équipe connecté à l'appli :)
                </p>
            </div>
            {{ csrf_field() }}
            <div class="container text-center">
                <button type="submit" class="btn btn-danger" id="goButton">Go&nbsp;! :)</button>
            </div>
        </form>
    <script>
        $("select").selectBoxIt(
            {
                autoWidth: false,
                isMobile: function(){return false;},
                theme: "jqueryui"
            });
    </script>
@endsection

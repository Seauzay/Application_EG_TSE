@extends('layouts.base')

@section('content')
    <form class="player-login" method="post" action="{{ url('player/checklogin') }}">
        <div class="container mb-3" data-toggle="buttons">
            <div class="row">
                <span class="text-left">Sélectionnez votre couleur d'équipe :</span>
            </div>
            <div class="row">
                <div class="btn-group btn-group-toggle w-100">
                    <label class="btn btn-rouge">
                        <input type="radio" name="color" value="1">Rouge
                    </label>
                    <label class="btn btn-vert">
                        <input type="radio" name="color" value="2">Vert
                    </label>
                    <label class="btn btn-bleu">
                        <input type="radio" name="color" value="3">Bleu
                    </label>
                    <label class="btn btn-jaune">
                        <input type="radio" name="color" value="4">Jaune
                    </label>
                    <label class="btn btn-violet">
                        <input type="radio" name="color" value="5">Violet
                    </label>
                </div>
            </div>
        </div>

        <div class="container mb-3" data-toggle="buttons">
            <div class="row">
                <span class="text-left">Sélectionnez votre numéro d'équipe :</span>
            </div>
            <div class="row">
                <div class="btn-group btn-group-toggle w-100">
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="1">1
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="2">2
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="3">3
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="4">4
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="5">5
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="btn-group btn-group-toggle w-100">
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="6">6
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="7">7
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="8">8
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="9">9
                    </label>
                    <label class="btn btn-light border">
                        <input type="radio" name="num" value="10">10
                    </label>
                </div>
            </div>
        </div>

        {{ csrf_field() }}
        <div class="container text-center">
            <button type="submit" class="btn btn-primary">Connexion</button>
        </div>
    </form>
@endsection
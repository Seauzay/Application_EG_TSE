@extends('layouts.base')

@section('nav-items')
    {{-- Timer global --}}
    <div id="global-timer" class="row justify-content-start">

    </div>
@endsection

@section('content')
    {{--template pour une énigme gm--}}
    <template id="gm-team-template">
        <div class="container jumbotron gm-team">
            <div class="row align-items-start gm-teams mb-3">
                <div class="col align-self-center text-center">
                    <span class="team-name"></span>&nbsp;:
                    <span class="team-time"></span>
                </div>
                <div class="col-8 gm-riddle-col">
                    <div class="row justify-content-center"><span class="current-riddle-title">Énigme actuelle&nbsp;: </span></div>
                    <div class="row justify-content-center"><span class="current-riddle"></span>&nbsp;: <span
                                class="current-riddle-time"></span></div>
                </div>
            </div>
            <div class="row progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="accordion">
                <div class="card">
                    <div class="card-header p-0 pt-2">
                        <div class="text-center my-auto">
                            <span data-toggle="collapse">Détail</span>
                        </div>
                        <i class="oi oi-chevron-bottom"></i>
                    </div>
                    <div class="collapse">
                        <div class="card-body">
                            Terminées :
                            <ul></ul>
                        </div>
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

    {{-- Template pour les salons --}}
    <template id="room-template">
        <div class="message-container">

        </div>

        <form action="msg/send/{id}" method="post" class="message-form">
            <input type="text" name="content">
        </form>
    </template>

    {{--Création des onglets--}}
    <script>
        tablist.addTab({title: 'Suivi des équipes', active: true});
        roomlist.update();
    </script>

    <script>
        const div = $('<div>');
        div.appendTo(tablist.contentOfTab(1));
        const gmTeamList = new GMTeamList(div);
        gmTeamList.update();

    </script>
@endsection
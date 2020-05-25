@extends('layouts.base')

@section('content')

    <div id="tablist"></div>
    <script>
        const tablist = new TabList('#tablist');
        tablist.addTab({title: 'Accueil', active: true});
        tablist.addTab({title: 'closeable', closeable: true});
        tablist.addTab({title: 'notifying'});
        tablist.addTab();
        tablist.addTab({title: 'Onglet inséré au milieu', position: 2});
        tablist.addTab({title: 'Onglet inséré au début', position: 'begin'});
        tablist.addTab({title: 'Onglet inséré avec un gros chiffre', position: 666});
        tablist.contentOfTab(2).text('Lorem ipsum accueillant');
        tablist.contentOfTab(3).text('J\'ai été ajouté avec JS');
        tablist.contentOfTab(4).append('<button class="btn btn-primary m-5">J\'ai été ajouté avec JS</button>');
        tablist.notify(5);
    </script>
@endsection
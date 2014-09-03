@extends("layout")
@section("title")
  Search results
@stop
@section("content")
@include('header')
<p>This is a list of all {{{count($players)}}} cards created. Click a name to view that player's wppr card.</p>
@include('cards.list', array('players' => $players))
@stop

@extends("layout")
@section("title")
  Search results
@stop
@section("content")
@include('header')

<p>{{{count($players)}}} matches for <em>{{{$query}}}</em>. Click a name to view their wppr card.</p>
@include('cards.list', array('players' => $players))
@stop

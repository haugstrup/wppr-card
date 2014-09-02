@extends("layout")
@section("title")
  Search results
@stop
@section("content")
@include('header')

<p>Search results for: <em>{{{$query}}}</em></p>
<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($players as $player)
      <tr>
        <td><a href="{{{ $player->link() }}}">{{{ $player->first_name }}} {{{ $player->last_name }}}</a></td>
        <td><a href="#">Claim&nbsp;me!</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
@stop

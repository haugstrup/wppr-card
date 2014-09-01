@extends("layout")
@section("title")
  Search results
@stop
@section("content")
<p>Search results for: <em>{{{$query}}}</em></p>
<table class="table">
  <thead>
    <tr>
      <th>IFPA #</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    @foreach($players as $player)
      <tr>
        <td><a href="{{{ $player->link() }}}">{{{ $player->player_id }}}</a></td>
        <td><a href="{{{ $player->link() }}}">{{{ $player->first_name }}} {{{ $player->last_name }}}</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
@stop

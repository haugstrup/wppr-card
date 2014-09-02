@extends("layout")
@section("title")
  Search results
@stop
@section("content")
@include('header')

<p>{{{count($players)}}} matches for <em>{{{$query}}}</em>. Click a name to view their wppr card.</p>
<table class="table table-striped table-condensed">
  <thead>
    <tr>
      <th>IFPA&nbsp;#</th>
      <th>Name</th>
      <!-- <th>Rank</th> -->
    </tr>
  </thead>
  <tbody>
    @foreach($players as $player)
      <tr>
        <td>{{{$player->player_id}}}</td>
        <td><a href="{{{ $player->link() }}}">{{{ $player->first_name }}} {{{ $player->last_name }}}</a></td>
        <!-- <td>{{{$player->info->wppr_rank}}}</td> -->
      </tr>
    @endforeach
  </tbody>
</table>
@stop

@extends("layout")
@section("content")
@section("title")
  {{{$player->first_name}}} {{{$player->last_name}}}
@stop
<div class="card">
  <div class="player-id">#{{{$player->player_id}}}</div>
  <div class="name">{{{$player->first_name}}} {{{$player->last_name}}}</div>
  <dl>
    <dt>Country</dt>
    <dd>{{{$info->player->country_name}}}</dd>
    <dt>Rank</dt>
    <dd>{{{$info->player_stats->current_wppr_rank}}}</dd>
    <dt>WPPRs</dt>
    <dd>{{{$info->player_stats->current_wppr_value}}}</dd>
    <dt>Active events</dt>
    <dd>{{{$info->player_stats->total_active_events}}}</dd>
    @if ($info->championshipSeries)
      <dt>{{{$info->championshipSeries[0]->group_name}}}</dt>
      <dd>{{{$info->championshipSeries[0]->rank}}}</dd>
    @endif
  </dl>
  <ul>
    <li><a href="{{{$player->link()}}}">{{{ $player->link() }}}</a></li>
    <li><a href="http://ifpapinball.com/player.php?player_id={{{$player->player_id}}}">View on IFPApinball.com</a></li>
    <li><a href="http://wpprnerdery.com/player_history/{{{$player->player_id}}}">Player ranking history WPPRnerdery.com</a></li>
  </ul>
</div>

@stop

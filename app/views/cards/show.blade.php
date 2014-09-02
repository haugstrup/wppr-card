@extends("layout")
@section("content")
@section("title")
  {{{$player->first_name}}} {{{$player->last_name}}}
@stop
<div class="card">
  <div class="card__name {{$name_class}}">{{{$player->first_name}}} {{{$player->last_name}}}</div>
  <div class="card__country">{{{$info->player->country_name}}}</div>
  <div class="card__player-id">Player #{{{$player->player_id}}}</div>
  <ul class="card__tiles list-unstyled">
    <li class="card__tile">
      <div class="card__tile-title">Rank</div>
      <div class="card__tile-fact">{{$info->player_stats->current_wppr_rank}}</div>
    </li>
    <li class="card__tile">
      <div class="card__tile-title">WPPRs</div>
      <div class="card__tile-fact">{{{$info->player_stats->current_wppr_value}}}</div>
    </li>
    <li class="card__tile">
      <div class="card__tile-title">Active Events</div>
      <div class="card__tile-fact">{{{$info->player_stats->total_active_events}}}</div>
    </li>
    @if ($info->championshipSeries)
    <li class="card__tile">
      <div class="card__tile-title">{{{$info->championshipSeries[0]->group_name}}}</div>
      <div class="card__tile-fact">{{$info->championshipSeries[0]->rank}}</div>
    </li>
    @endif
  </ul>
  <a class="card__ifpa btn btn-default" href="http://ifpapinball.com/player.php?player_id={{{$player->player_id}}}" target="_blank">View official IFPA profile</a>
  <!-- <a class="small card__banner" href="{{{URL::route('front')}}}">Get your wppr card</a> -->
  <a class="small card__player-link" href="{{{$player->link()}}}">{{{ $player->link() }}}</a>
</div>

@stop

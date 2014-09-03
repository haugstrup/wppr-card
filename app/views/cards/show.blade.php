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
      <div class="card__tile-title">RANK</div>
      <div class="card__tile-fact">{{$info->player_stats->current_wppr_rank}}</div>
    </li>
    <li class="card__tile">
      <div class="card__tile-title">WPPRs</div>
      <div class="card__tile-fact">{{{$info->player_stats->current_wppr_value}}}</div>
    </li>
    <li class="card__tile">
      <div class="card__tile-title">ACTIVE EVENTS</div>
      <div class="card__tile-fact">{{{$info->player_stats->total_active_events}}}</div>
    </li>
    @if (isset($info->championshipSeries))
    <li class="card__tile">
      <div class="card__tile-title">{{{strtoupper($info->championshipSeries[0]->group_name)}}}</div>
      <div class="card__tile-fact">{{$info->championshipSeries[0]->rank}}</div>
    </li>
    @endif
  </ul>
  <a class="card__button-full-width btn btn-default" href="http://ifpapinball.com/player.php?player_id={{{$player->player_id}}}" target="_blank">View official IFPA profile</a>
  <a class="small card__player-link" href="{{{$player->link()}}}">{{{ $player->link() }}}</a>
  @if (!$player->url_label && $info->player->ifpa_registered === 'Y')
    <div class="btn-group btn-group-justified">
      <a class="btn " href="{{{URL::route('claim', $player->player_id)}}}">Claim card</a>
      <a class="btn " href="{{{URL::route('front')}}}">Front page</a>
    </div>
  @else
    <a class="card__button-full-width btn" href="{{{URL::route('front')}}}">Back to front page</a>
  @endif
</div>

@stop

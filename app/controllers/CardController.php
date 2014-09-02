<?php

use Ifpa\IfpaApi;
use Carbon\Carbon;

class CardController extends BaseController {

  public function show($id)
  {

    if (is_numeric($id)) {

      // Get from cache or IFPA
      $info = $this->get_player_info($id);

      if (!$info || !$info->player->player_id) {
        return View::make('home.ifpaerror');
      }

      // See if we already have this player
      // Create if we don't
      $raw = (array)$info->player;
      $raw['player_id'] = (int)$raw['player_id'];
      $player = new Player($raw);

      if ($info && !Player::find($player->player_id)) {
        $player->url_label = null;
        $player->save();
      }

    } else {
      // Look up by url_label
      $player = Player::where('url_label', '=', $id)->first();

      if (!$player) {
        App::abort(404);
      }

      // We always have to get full info
      $info = $this->get_player_info($player->player_id);

      if (!$info || !$info->player->player_id) {
        return View::make('home.ifpaerror');
      }

    }

    if (!$info) {
      return View::make('home.ifpaerror');
    }

    // Update local DB if player first or last name changed.
    if ($player->first_name !== $info->player->first_name || $player->last_name !== $info->player->last_name) {
      $player->first_name = $info->player->first_name;
      $player->last_name = $info->player->last_name;
      $player->save();
    }

    // Format ordinals
    $info->player_stats->current_wppr_rank = $this->format_ordinal($info->player_stats->current_wppr_rank);
    if ($info->championshipSeries) {
      $info->championshipSeries[0]->rank = $this->format_ordinal($info->championshipSeries[0]->rank);
    }

    return View::make('cards.show', array('player' => $player, 'info' => $info, 'name_class' => strlen($player->first_name.$player->last_name) > 17 ? 'long' : 'short'));
  }

  public function search()
  {

    $query = Input::get('query');

    // Send player ids directly to permalink
    if (is_numeric($query)) {
      return Redirect::route('show_by_id', (int)($query));
    }

    // Show search results
    $key = 'search-'.$query;
    if (Cache::has($key)) {
      $result = Cache::get($key);
    } else {
      $ifpa = new IfpaApi($_ENV['IFPA_API_KEY']);
      if (stristr($query, '@')) {
        $result = $ifpa->searchPlayersByEmail($query);
      } else {
        $result = $ifpa->searchPlayersByName($query);
      }

      if (!$result) {
        return View::make('home.ifpaerror');
      }

      // Store result in cache
      Cache::put($key, $result, Carbon::now()->addMinutes(60));
    }

    $players = array();

    foreach ($result->search as $raw) {
      $players[] = new Player((array)$raw);
    }

    if (count($players) === 1) {
      return Redirect::route('show_by_id', $players[0]->player_id);
    }

    return View::make('cards.search', array('players' => $players, 'query' => $query));
  }

  public function get_player_info($id) {
    $key = 'player-'.$id;
    if (Cache::has($key)) {
      $result = Cache::get($key);
    } else {
      $ifpa = new IfpaApi($_ENV['IFPA_API_KEY']);
      $result = $ifpa->getPlayerInformation($id);
      if ($result) {
        Cache::put($key, $result, Carbon::now()->addMinutes(60));
      }
    }
    return $result;
  }

  public function format_ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13) {
       return $number. 'th';
    } else {
       return $number."<sup>".$ends[$number % 10]."</sup>";
    }
  }

}

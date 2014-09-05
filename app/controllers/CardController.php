<?php

use Ifpa\IfpaApi;
use Carbon\Carbon;

class CardController extends BaseController {

  public function index()
  {
    $players = Player::orderBy('first_name')->orderBy('last_name')->get();
    return View::make('cards.index', array('players' => $players));
  }

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

      $player = Player::find($raw['player_id']);

      if (!$player) {
        $player = new Player($raw);
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
    if (isset($info->championshipSeries)) {
      $info->championshipSeries[0]->rank = $this->format_ordinal($info->championshipSeries[0]->rank);
    }

    return View::make('cards.show', array('player' => $player, 'info' => $info, 'name_class' => strlen($player->first_name.$player->last_name) > 17 ? 'long' : 'short'));
  }

  public function search()
  {

    $rules = array(
      'query'    => 'required'
    );

    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Redirect::route('front')->withErrors($validator)->withInput();
    }

    $query = Input::get('query');

    // Send player ids directly to permalink
    if (is_numeric($query)) {
      return Redirect::route('show_by_id', (int)($query));
    }

    $result = $this->search_for_player($query);
    if (!$result) {
      return View::make('home.ifpaerror');
    }

    $players = array();

    foreach ($result->search as $raw) {
      $player = new Player((array)$raw);
      $player->info = $raw;
      $players[] = $player;
    }

    if (count($players) === 1) {
      return Redirect::route('show_by_id', $players[0]->player_id);
    }

    return View::make('cards.search', array('players' => $players, 'query' => $query));
  }

  public function claim($id = null) {

    if ($id) {
      $player = Player::find($id);

      if (!$player) {
        $info = $this->get_player_info($id);

        if (!$info || !$info->player->player_id) {
          return View::make('home.ifpaerror');
        }

        $raw = (array)$info->player;
        $raw['player_id'] = (int)$raw['player_id'];
        $player = new Player($raw);
      }
    } else {
      $player = null;
    }

    return View::make('cards.claim', array('player' => $player));
  }

  public function check_claim() {

    $label = Input::get('vanity_url');
    $id = Input::get('player_id');
    $email = Input::get('email');

    $rules = array(
      'email'    => 'required|email',
      'vanity_url' => 'required|min:3|not_in:claim,search,p,players|alpha_dash'
    );

    $validator = Validator::make(Input::all(), $rules);
    $error_route = Redirect::route('claim_bare');
    if ($id) {
      $error_route = Redirect::route('claim', Input::get('player_id'));
    }


    if ($validator->fails()) {
      return $error_route->withErrors($validator)->withInput();
    }

    // Test that url is not already spoken for
    if (Player::where('url_label', '=', $label)->first()) {
      return $error_route->with('error', 'Bummer, someone else already claimed that vanity URL.')->withInput();
    }

    // If ID passed: Get player from DB
    $existing_player = null;
    if ($id) {
      $existing_player = Player::find($id);
    }

    // Get player by email
    $result = $this->search_for_player($email);
    if (!$result) {
      return View::make('home.ifpaerror');
    }

    // Test that search result is there
    if (count($result->search) === 0) {
      return $error_route->with('error', 'Aww shucks, IFPA doesn\'t have a player registered with that email.')->withInput();
    }

    $api_player = array_shift($result->search);

    // If ID passed: Make sure search result matches id provided
    if ($existing_player) {
      if ($existing_player->player_id !== (int)$api_player->player_id) {
        return $error_route->with('error', 'The email you entered does not belong to the player you are trying to claim a card for.')->withInput();
      }
    }

    // Test that player doesn't already have a URL label
    $player = Player::find($api_player->player_id);

    if ($player && $player->url_label) {
      return $error_route->with('error', 'Player already has a vanity URL.')->withInput();
    }

    // Save url label on player
    if ($player) {
      $player->url_label = $label;
      $player->save();
    } else {
      $raw = (array)$api_player;
      $raw['player_id'] = (int)$raw['player_id'];
      $player = new Player($raw);
      $player->url_label = $label;
      $player->save();
    }

    return Redirect::route('show', $player->url_label)->with('success', "Groovy, man. You've claimed your card.");
  }

  public function search_for_player($query) {
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

      if ($result) {
        Cache::put($key, $result, Carbon::now()->addHours(24));
      }
    }
    return $result;
  }

  public function get_player_info($id) {
    $key = 'player-'.$id;
    if (Cache::has($key)) {
      $result = Cache::get($key);
    } else {
      $ifpa = new IfpaApi($_ENV['IFPA_API_KEY']);
      $result = $ifpa->getPlayerInformation($id);
      if ($result) {
        Cache::put($key, $result, Carbon::now()->addHours(24));
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

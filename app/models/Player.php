<?php

class Player extends Eloquent {

  protected $primaryKey = 'player_id';
  public $incrementing = false;
  protected $fillable = array('player_id', 'first_name', 'last_name', 'url_label');

  public function link() {
    return $this->url_label ? URL::route('show', $this->url_label) : URL::route('show_by_id', $this->player_id);
  }

}

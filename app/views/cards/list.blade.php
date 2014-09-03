<table class="table table-striped table-condensed">
  <thead>
    <tr>
      <th>IFPA&nbsp;#</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    @foreach($players as $player)
      <tr>
        <td>{{{$player->player_id}}}</td>
        <td><a href="{{{ $player->link() }}}">{{{ $player->first_name }}} {{{ $player->last_name }}}</a></td>
      </tr>
    @endforeach
  </tbody>
</table>

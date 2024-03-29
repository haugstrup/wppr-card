@extends("layout")
@section("title")
  Claim card
@stop
@section("content")
@include('header')
<p class="small">To claim your own WPPR Card, fill in the email address you registered with IFPA and your vanity URL below. If you haven't registered an email with IFPA, <a href="http://www.ifpapinball.com/user-profile">go do so right now</a>.</p>
<p class="small">After claiming your card it will be available at {{{URL::route('front')}}}/<b>YourVanityName</b></p>
{{ Form::open(array('route' => array('check_claim'), 'method' => 'post', 'class' => 'claim-form')) }}
  <div class="form-group">
    {{ Form::email('email', null, array('class' => 'form-control', 'autocorrect' => 'off', 'autocapitalize' => 'off', 'placeholder' => 'Enter your IFPA email address')) }}
  </div>
  <div class="form-group">
    {{ Form::text('vanity_url', null, array('class' => 'form-control', 'autocorrect' => 'off', 'autocapitalize' => 'off', 'placeholder' => 'Your vanity name - initials, nickname etc')) }}
  </div>
  {{ Form::hidden('player_id', $player ? $player->player_id : '') }}
  {{ Form::button('Claim wppr card', array('class' => 'btn btn-success', 'type' => 'submit')) }}
</div>
{{ Form::close() }}
@stop

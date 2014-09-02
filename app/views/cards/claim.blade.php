@extends("layout")
@section("title")
  Claim card
@stop
@section("content")
@include('header')
<p class="small">To claim your own WPPR Card, fill in the email address you registered with IFPA and your vanity URL below.</p>
<p class="small">After claiming your card it will be available at {{{URL::route('front')}}}/<b>YourVanityURL</b></p>
{{ Form::open(array('route' => array('check_claim', $player->player_id), 'method' => 'post', 'class' => 'claim-form')) }}
  <div class="form-group">
    {{ Form::email('query', null, array('class' => 'form-control', 'autocorrect' => 'off', 'autocapitalize' => 'off', 'placeholder' => 'Enter your IFPA email address')) }}
  </div>
  <div class="form-group">
    {{ Form::text('query', null, array('class' => 'form-control', 'autocorrect' => 'off', 'autocapitalize' => 'off', 'placeholder' => 'Enter your vanity URL')) }}
  </div>
  {{ Form::button('Claim wppr card', array('class' => 'btn btn-success', 'type' => 'submit')) }}
</div>
{{ Form::close() }}
@stop
